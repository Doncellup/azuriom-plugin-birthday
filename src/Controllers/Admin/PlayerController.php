<?php

namespace Azuriom\Plugin\Birthday\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\User;
use Azuriom\Plugin\Birthday\Models\Birthday;
use Azuriom\Plugin\Birthday\Models\Claim;
use Azuriom\Plugin\Birthday\Services\BirthdayManager;
use Azuriom\Plugin\Birthday\Services\RewardSettings;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlayerController extends Controller
{
    private const PER_PAGE = 25;

    public function index(Request $request)
    {
        $search = $request->input('search');

        $all = Birthday::with('user')
            ->when($search, fn ($query) => $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")))
            ->get()
            ->filter(fn (Birthday $birthday) => $birthday->user !== null)
            ->sortBy(fn (Birthday $birthday) => $birthday->nextOccurrence()->timestamp)
            ->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $birthdays = new LengthAwarePaginator(
            $all->forPage($page, self::PER_PAGE),
            $all->count(),
            self::PER_PAGE,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $grace = RewardSettings::graceDays();
        $year = today()->year;
        $claims = Claim::whereIn('year', [$year, $year - 1])
            ->whereIn('user_id', $birthdays->getCollection()->pluck('user_id'))
            ->get()
            ->map(fn (Claim $claim) => $claim->user_id.':'.$claim->year)
            ->all();

        $limit = today()->addDays(30);

        return view('birthday::admin.players.index', [
            'birthdays' => $birthdays,
            'claims' => $claims,
            'grace' => $grace,
            'search' => $search,
            'users' => $search ? User::where('name', 'like', "%{$search}%")->take(10)->get() : collect(),
            'total' => Birthday::count(),
            'soon' => Birthday::all()->filter(fn (Birthday $birthday) => $birthday->nextOccurrence()->lte($limit))->count(),
            'claimedThisYear' => Claim::where('year', $year)->count(),
        ]);
    }

    public function show(User $user)
    {
        $birthday = Birthday::with('user')->where('user_id', $user->id)->first();

        return view('birthday::admin.players.show', [
            'user' => $user,
            'birthday' => $birthday,
            'status' => BirthdayManager::status($birthday),
            'claims' => Claim::where('user_id', $user->id)->orderByDesc('year')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $this->validate($request, [
            'action' => ['required', Rule::in(['save', 'reset_claim', 'delete'])],
            'day' => ['required_if:action,save', 'nullable', 'integer', 'between:1,31'],
            'month' => ['required_if:action,save', 'nullable', 'integer', 'between:1,12'],
        ]);

        $birthday = Birthday::where('user_id', $user->id)->first();

        switch ($validated['action']) {
            case 'save':
                if (! checkdate((int) $validated['month'], (int) $validated['day'], 2000)) {
                    return back()->withInput()->with('error', trans('birthday::messages.errors.date'));
                }

                // A date set by the staff is trusted: the anti-abuse delays do not apply to it.
                Birthday::updateOrCreate(['user_id' => $user->id], [
                    'day' => (int) $validated['day'],
                    'month' => (int) $validated['month'],
                    'trusted' => true,
                    'notified_year' => null,
                ]);
                break;
            case 'reset_claim':
                if ($birthday !== null) {
                    // The gift of the birthday in progress (or of this year) can be claimed again.
                    $year = ($birthday->currentOccurrence(RewardSettings::graceDays()) ?? today())->year;

                    DB::transaction(function () use ($birthday, $user, $year) {
                        Claim::where('user_id', $user->id)->where('year', $year)->delete();
                        $birthday->forceFill(['notified_year' => null])->save();
                    });
                }
                break;
            case 'delete':
                $birthday?->delete();
                break;
        }

        ActionLog::log('birthday.players.updated', $user, ['action' => $validated['action']]);

        return to_route('birthday.admin.players.show', $user)
            ->with('success', trans('messages.status.success'));
    }
}
