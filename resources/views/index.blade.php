@extends('layouts.app')

@section('title', trans('birthday::messages.title'))

@push('styles')
    <link href="{{ plugin_asset('birthday', 'css/style.css?v=1.0.0') }}" rel="stylesheet">
@endpush

@section('content')
    <h1>{{ trans('birthday::messages.title') }}</h1>

    @if($welcome)
        <div class="mb-3">{!! Str::markdown($welcome, ['html_input' => 'escape', 'allow_unsafe_links' => false]) !!}</div>
    @endif

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card h-100 bd-card">
                <div class="card-body">
                    @guest
                        <div class="alert alert-info mb-0" role="alert">
                            <i class="bi bi-info-circle"></i> {{ trans('birthday::messages.login') }}
                            <a href="{{ route('login') }}" class="alert-link">{{ trans('auth.login') }}</a>
                        </div>
                    @else
                        @php($state = $status['state'])

                        @if($state === 'none')
                            <h2 class="h4"><i class="bi bi-calendar-heart bd-accent"></i> {{ trans('birthday::messages.save.title') }}</h2>
                            <p>{{ trans('birthday::messages.save.info') }}</p>

                            <form action="{{ route('birthday.store') }}" method="POST"
                                  onsubmit="return confirm(@js(trans('birthday::messages.save.confirm')))">
                                @csrf

                                <div class="row gx-3">
                                    <div class="col-5 col-md-4 mb-3">
                                        <label class="form-label" for="daySelect">{{ trans('birthday::messages.save.day') }}</label>
                                        <select class="form-select @error('day') is-invalid @enderror" id="daySelect" name="day" required>
                                            <option value="" disabled @selected(! old('day'))>—</option>
                                            @for($d = 1; $d <= 31; $d++)
                                                <option value="{{ $d }}" @selected((int) old('day') === $d)>{{ $d }}</option>
                                            @endfor
                                        </select>
                                        @error('day')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="col-7 col-md-5 mb-3">
                                        <label class="form-label" for="monthSelect">{{ trans('birthday::messages.save.month') }}</label>
                                        <select class="form-select @error('month') is-invalid @enderror" id="monthSelect" name="month" required>
                                            <option value="" disabled @selected(! old('month'))>—</option>
                                            @for($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}" @selected((int) old('month') === $m)>{{ trans('birthday::messages.months.'.$m) }}</option>
                                            @endfor
                                        </select>
                                        @error('month')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> {{ trans('birthday::messages.save.button') }}
                                </button>
                            </form>

                            @if($daysBefore > 0 || $accountDays > 0)
                                <div class="small text-muted mt-3">
                                    <i class="bi bi-shield-check"></i>
                                    {{ trans('birthday::messages.rules.intro') }}
                                    <ul class="mb-0">
                                        @if($daysBefore > 0)
                                            <li>{{ trans_choice('birthday::messages.rules.days_before', $daysBefore, ['count' => $daysBefore]) }}</li>
                                        @endif
                                        @if($accountDays > 0)
                                            <li>{{ trans_choice('birthday::messages.rules.account_days', $accountDays, ['count' => $accountDays]) }}</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        @else
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bd-date">
                                    <span class="bd-date-month">{{ trans('birthday::messages.months_short.'.$birthday->month) }}</span>
                                    <span class="bd-date-day">{{ $birthday->day }}</span>
                                </div>
                                <div>
                                    <div class="text-muted small">{{ trans('birthday::messages.your_birthday') }}</div>
                                    <div class="fs-4 fw-bold">{{ \Azuriom\Plugin\Birthday\Services\BirthdayManager::formatDay($birthday->day, $birthday->month) }}</div>
                                </div>
                            </div>

                            @if($state === 'claimable')
                                <div class="bd-hero text-center">
                                    <div class="bd-hero-icon"><i class="bi bi-cake2"></i></div>
                                    <h2 class="h3">{{ $status['date']->isToday() ? trans('birthday::messages.happy_birthday', ['name' => auth()->user()->name]) : trans('birthday::messages.gift_waiting') }}</h2>
                                    <p class="mb-3">
                                        {{ trans('birthday::messages.claim_until', ['date' => format_date($status['deadline'])]) }}
                                    </p>
                                    <form action="{{ route('birthday.claim') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-lg bd-btn">
                                            <i class="bi bi-gift"></i> {{ trans('birthday::messages.claim', ['reward' => $rewardName]) }}
                                        </button>
                                    </form>
                                </div>
                            @elseif($state === 'claimed')
                                <div class="alert alert-success mb-3" role="alert">
                                    <i class="bi bi-check-circle"></i> {{ trans('birthday::messages.already_claimed', ['reward' => $rewardName]) }}
                                </div>
                                <p class="mb-0 text-muted">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ trans('birthday::messages.next_gift', ['date' => format_date($status['next'])]) }}
                                </p>
                            @elseif($state === 'blocked')
                                <div class="alert alert-warning mb-3" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    {{ trans_choice('birthday::messages.blocked.'.$status['reason'], $status['reason'] === 'too_late' ? $daysBefore : $accountDays, ['count' => $status['reason'] === 'too_late' ? $daysBefore : $accountDays]) }}
                                </div>
                                <p class="mb-0 text-muted">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ trans('birthday::messages.next_gift', ['date' => format_date($status['next'])]) }}
                                </p>
                            @else
                                <p class="fs-5 mb-2">
                                    <i class="bi bi-hourglass-split bd-accent"></i>
                                    {{ trans_choice('birthday::messages.in_days', $status['days'], ['count' => $status['days'], 'date' => format_date($status['date'])]) }}
                                </p>

                                @if($status['reason'] !== null)
                                    <div class="alert alert-warning mb-0" role="alert">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ trans_choice('birthday::messages.blocked_next.'.$status['reason'], $status['reason'] === 'too_late' ? $daysBefore : $accountDays, ['count' => $status['reason'] === 'too_late' ? $daysBefore : $accountDays]) }}
                                    </div>
                                @endif
                            @endif

                            <p class="small text-muted mt-3 mb-0">
                                <i class="bi bi-lock"></i> {{ trans('birthday::messages.locked') }}
                            </p>
                        @endif
                    @endguest
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-gift"></i> {{ trans('birthday::messages.reward.title') }}
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item fw-bold">{{ $rewardName }}</li>
                    @if($rewardMoney > 0)
                        <li class="list-group-item">
                            <i class="bi bi-coin bd-accent"></i> {{ trans('birthday::messages.reward.money', ['money' => format_money($rewardMoney)]) }}
                        </li>
                    @endif
                    @if($rewardHasCommands)
                        <li class="list-group-item">
                            <i class="bi bi-controller bd-accent"></i> {{ trans('birthday::messages.reward.ingame') }}
                        </li>
                    @endif
                    @if($rewardMoney <= 0 && ! $rewardHasCommands)
                        <li class="list-group-item text-muted">{{ trans('birthday::messages.reward.none') }}</li>
                    @endif
                    <li class="list-group-item text-muted small">
                        <i class="bi bi-calendar-check"></i>
                        {{ trans_choice('birthday::messages.reward.grace', $graceDays, ['count' => $graceDays]) }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('footer-scripts')
    <script src="{{ plugin_asset('birthday', 'js/birthday.js') }}"></script>
    <script>
        birthday.init({ celebrate: @json((bool) session('birthday.celebrate')) });
    </script>
@endpush
