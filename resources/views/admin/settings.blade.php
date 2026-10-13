@extends('admin.layouts.admin')

@section('title', trans('birthday::admin.settings.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('birthday.admin.settings.save') }}" method="POST">
                @csrf

                <h5>{{ trans('birthday::admin.settings.reward') }}</h5>

                <div class="row gx-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="rewardNameInput">{{ trans('birthday::admin.settings.reward_name') }}</label>
                        <input type="text" class="form-control @error('reward_name') is-invalid @enderror" id="rewardNameInput" name="reward_name" value="{{ old('reward_name', $rewardName) }}" placeholder="{{ trans('birthday::messages.reward_default') }}" maxlength="100">
                        @error('reward_name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <div class="form-text">{{ trans('birthday::admin.settings.reward_name_info') }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="moneyInput">{{ trans('birthday::admin.settings.money') }}</label>
                        <div class="input-group @error('money') has-validation @enderror">
                            <input type="number" min="0" step="0.01" class="form-control @error('money') is-invalid @enderror" id="moneyInput" name="money" value="{{ old('money', $money ?: '') }}">
                            <span class="input-group-text">{{ money_name() }}</span>
                            @error('money')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-text">{{ trans('birthday::admin.settings.money_info') }}</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="serversSelect">{{ trans('birthday::admin.settings.servers') }}</label>
                    <select class="form-select @error('servers') is-invalid @enderror" id="serversSelect" name="servers[]" multiple>
                        @foreach($servers as $server)
                            <option value="{{ $server->id }}" @selected(in_array($server->id, old('servers', $serverIds)))>{{ $server->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ trans('birthday::admin.settings.commands') }}</label>

                    @include('admin.elements.list-input', ['name' => 'commands', 'values' => old('commands', $commands)])

                    <div class="form-text">
                        {!! game()->trans('commands') !!}
                        @lang('birthday::admin.settings.commands_info', ['placeholders' => '<code>{reward}</code>'])
                    </div>
                </div>

                <div class="mb-4 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="needOnlineSwitch" name="need_online" @checked(old('need_online', $needOnline))>
                    <label class="form-check-label" for="needOnlineSwitch">{{ trans('birthday::admin.settings.need_online') }}</label>
                </div>

                <h5>{{ trans('birthday::admin.settings.rules') }}</h5>

                <div class="row gx-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="graceInput">{{ trans('birthday::admin.settings.grace_days') }}</label>
                        <div class="input-group @error('grace_days') has-validation @enderror">
                            <input type="number" min="0" max="60" class="form-control @error('grace_days') is-invalid @enderror" id="graceInput" name="grace_days" value="{{ old('grace_days', $graceDays) }}" required>
                            <span class="input-group-text">{{ trans('birthday::admin.settings.days') }}</span>
                            @error('grace_days')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-text">{{ trans('birthday::admin.settings.grace_days_info') }}</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="daysBeforeInput">{{ trans('birthday::admin.settings.min_days_before') }}</label>
                        <div class="input-group @error('min_days_before') has-validation @enderror">
                            <input type="number" min="0" max="365" class="form-control @error('min_days_before') is-invalid @enderror" id="daysBeforeInput" name="min_days_before" value="{{ old('min_days_before', $daysBefore) }}" required>
                            <span class="input-group-text">{{ trans('birthday::admin.settings.days') }}</span>
                            @error('min_days_before')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-text">{{ trans('birthday::admin.settings.min_days_before_info') }}</div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="accountDaysInput">{{ trans('birthday::admin.settings.min_account_days') }}</label>
                        <div class="input-group @error('min_account_days') has-validation @enderror">
                            <input type="number" min="0" max="3650" class="form-control @error('min_account_days') is-invalid @enderror" id="accountDaysInput" name="min_account_days" value="{{ old('min_account_days', $accountDays) }}" required>
                            <span class="input-group-text">{{ trans('birthday::admin.settings.days') }}</span>
                            @error('min_account_days')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-text">{{ trans('birthday::admin.settings.min_account_days_info') }}</div>
                    </div>
                </div>

                <h5 class="mt-2">{{ trans('birthday::admin.settings.page') }}</h5>

                <div class="mb-2 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="notificationsSwitch" name="notifications" @checked(old('notifications', $notifications))>
                    <label class="form-check-label" for="notificationsSwitch">{{ trans('birthday::admin.settings.notifications') }}</label>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="userMenuSwitch" name="user_menu" @checked(old('user_menu', $userMenu))>
                    <label class="form-check-label" for="userMenuSwitch">{{ trans('birthday::admin.settings.user_menu') }}</label>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="welcomeInput">{{ trans('birthday::admin.settings.welcome') }}</label>
                    <textarea class="form-control @error('welcome') is-invalid @enderror" id="welcomeInput" name="welcome" rows="3">{{ old('welcome', $welcome) }}</textarea>
                    @error('welcome')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
