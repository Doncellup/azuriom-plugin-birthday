@extends('admin.layouts.admin')

@section('title', trans('birthday::admin.players.player', ['user' => $user->name]))

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $user->getAvatar(64) }}" alt="{{ $user->name }}" class="rounded" width="48" height="48">
                        <div>
                            <div class="fw-bold fs-5">{{ $user->name }}</div>
                            @if($birthday !== null)
                                <div class="text-muted">
                                    {{ \Azuriom\Plugin\Birthday\Services\BirthdayManager::formatDay($birthday->day, $birthday->month) }}
                                    @if($birthday->trusted)
                                        <i class="bi bi-patch-check text-primary" title="{{ trans('birthday::admin.players.trusted') }}" data-bs-toggle="tooltip"></i>
                                    @endif
                                </div>
                            @else
                                <div class="text-muted">{{ trans('birthday::admin.players.no_birthday') }}</div>
                            @endif
                        </div>
                    </div>

                    @if($birthday !== null)
                        @php($state = $status['state'])
                        <p>
                            @if($state === 'claimable')
                                <span class="badge bg-primary">{{ trans('birthday::admin.players.available') }}</span>
                            @elseif($state === 'claimed')
                                <span class="badge bg-success">{{ trans('birthday::admin.players.claimed') }}</span>
                            @elseif($state === 'blocked')
                                <span class="badge bg-secondary">{{ trans('birthday::admin.players.not_eligible') }}</span>
                                {{ trans('birthday::admin.players.reasons.'.$status['reason']) }}
                            @else
                                {{ trans('birthday::admin.players.next') }} : {{ format_date($status['date']) }}
                                @if($status['reason'] !== null)
                                    <br><span class="text-muted small">{{ trans('birthday::admin.players.reasons.'.$status['reason']) }}</span>
                                @endif
                            @endif
                        </p>
                    @endif

                    <form action="{{ route('birthday.admin.players.update', $user) }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="action" value="save">

                        <label class="form-label">{{ trans('birthday::admin.players.set') }}</label>
                        <div class="row gx-2">
                            <div class="col-4">
                                <select class="form-select @error('day') is-invalid @enderror" name="day" aria-label="{{ trans('birthday::messages.save.day') }}" required>
                                    @for($d = 1; $d <= 31; $d++)
                                        <option value="{{ $d }}" @selected((int) old('day', $birthday?->day) === $d)>{{ $d }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-5">
                                <select class="form-select @error('month') is-invalid @enderror" name="month" aria-label="{{ trans('birthday::messages.save.month') }}" required>
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" @selected((int) old('month', $birthday?->month) === $m)>{{ trans('birthday::messages.months.'.$m) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i></button>
                            </div>
                        </div>
                        <div class="form-text">{{ trans('birthday::admin.players.set_info') }}</div>
                    </form>

                    @if($birthday !== null)
                        <div class="d-flex flex-wrap gap-2">
                            <form action="{{ route('birthday.admin.players.update', $user) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="reset_claim">
                                <button type="submit" class="btn btn-outline-warning"><i class="bi bi-arrow-counterclockwise"></i> {{ trans('birthday::admin.players.reset_claim') }}</button>
                            </form>

                            <form action="{{ route('birthday.admin.players.update', $user) }}" method="POST"
                                  onsubmit="return confirm(@js(trans('birthday::admin.players.delete_confirm')))">
                                @csrf
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i> {{ trans('birthday::admin.players.delete') }}</button>
                            </form>
                        </div>
                        <div class="form-text mt-2">{{ trans('birthday::admin.players.reset_claim_info') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">{{ trans('birthday::admin.players.history') }}</div>
                @if($claims->isEmpty())
                    <div class="card-body text-muted">{{ trans('birthday::admin.players.no_claims') }}</div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($claims as $claim)
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-gift"></i> {{ trans('birthday::admin.players.claim_year', ['year' => $claim->year]) }}</span>
                                <span class="text-muted">{{ format_date($claim->created_at, true) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <a href="{{ route('birthday.admin.players.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ trans('messages.actions.back') }}
            </a>
        </div>
    </div>
@endsection
