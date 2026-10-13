@extends('admin.layouts.admin')

@section('title', trans('birthday::admin.players.title'))

@section('content')
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ trans('birthday::admin.players.stats.total') }}</div>
                    <div class="fs-3 fw-bold">{{ $total }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ trans('birthday::admin.players.stats.soon') }}</div>
                    <div class="fs-3 fw-bold">{{ $soon }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="text-muted small">{{ trans('birthday::admin.players.stats.claimed', ['year' => today()->year]) }}</div>
                    <div class="fs-3 fw-bold">{{ $claimedThisYear }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form class="row gx-2 align-items-end mb-3" method="GET" action="{{ route('birthday.admin.players.index') }}">
                <div class="col-md-10 mb-2">
                    <label class="form-label" for="searchInput">{{ trans('birthday::admin.players.search') }}</label>
                    <input type="search" class="form-control" id="searchInput" name="search" value="{{ $search }}">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
                </div>
            </form>

            @if($users->isNotEmpty())
                <h6>{{ trans('birthday::admin.players.results') }}</h6>
                <div class="mb-3 d-flex flex-wrap gap-2">
                    @foreach($users as $foundUser)
                        <a href="{{ route('birthday.admin.players.show', $foundUser) }}" class="btn btn-outline-primary btn-sm">
                            {{ $foundUser->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if($birthdays->isEmpty())
                <p class="text-muted mb-0">{{ trans('birthday::admin.players.none') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th scope="col">{{ trans('messages.fields.name') }}</th>
                            <th scope="col">{{ trans('birthday::admin.players.birthday') }}</th>
                            <th scope="col">{{ trans('birthday::admin.players.next') }}</th>
                            <th scope="col">{{ trans('birthday::admin.players.gift') }}</th>
                            <th scope="col">{{ trans('messages.fields.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($birthdays as $entry)
                            @php($current = $entry->currentOccurrence($grace))
                            @php($next = $entry->nextOccurrence())
                            <tr>
                                <td>{{ $entry->user->name }}</td>
                                <td>
                                    {{ \Azuriom\Plugin\Birthday\Services\BirthdayManager::formatDay($entry->day, $entry->month) }}
                                    @if($entry->trusted)
                                        <i class="bi bi-patch-check text-primary" title="{{ trans('birthday::admin.players.trusted') }}" data-bs-toggle="tooltip"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($next->isToday())
                                        <span class="badge bg-warning">{{ trans('birthday::admin.players.today') }}</span>
                                    @else
                                        {{ format_date($next) }}
                                    @endif
                                </td>
                                <td>
                                    @if($current === null)
                                        <span class="text-muted">—</span>
                                    @elseif(in_array($entry->user_id.':'.$current->year, $claims, true))
                                        <span class="badge bg-success">{{ trans('birthday::admin.players.claimed') }}</span>
                                    @elseif($entry->ineligibleReason($current) !== null)
                                        <span class="badge bg-secondary">{{ trans('birthday::admin.players.not_eligible') }}</span>
                                    @else
                                        <span class="badge bg-primary">{{ trans('birthday::admin.players.available') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('birthday.admin.players.show', $entry->user) }}" title="{{ trans('birthday::admin.players.manage') }}" data-bs-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $birthdays->links() }}
            @endif
        </div>
    </div>
@endsection
