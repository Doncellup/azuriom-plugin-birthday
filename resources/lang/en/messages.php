<?php

return [
    'title' => 'Birthday',
    'reward_default' => 'Birthday gift',

    'login' => 'Log in to save your birthday and receive your gift.',
    'your_birthday' => 'Your birthday',
    'locked' => 'The date can no longer be changed. In case of a mistake, contact the staff.',

    'save' => [
        'title' => 'Save your birthday',
        'info' => 'Enter the day and the month of your birthday (no need for the year) and receive a gift every year.',
        'day' => 'Day',
        'month' => 'Month',
        'button' => 'Save my date',
        'confirm' => 'Warning: once saved, the date cannot be changed. Confirm?',
    ],

    'rules' => [
        'intro' => 'Anti-abuse conditions to receive the gift of a given year:',
        'days_before' => '{1} the date must be saved at least 1 day before the birthday;|[2,*] the date must be saved at least :count days before the birthday;',
        'account_days' => '{1} your account must have been created at least 1 day before the birthday.|[2,*] your account must have been created at least :count days before the birthday.',
    ],

    'happy_birthday' => 'Happy birthday, :name!',
    'gift_waiting' => 'Your birthday gift is waiting for you!',
    'claim_until' => 'You can claim it until :date.',
    'claim' => 'Claim: :reward',
    'already_claimed' => 'You have already claimed your gift ":reward". Have a great day!',
    'next_gift' => 'Next gift: :date.',

    'in_days' => '{0} It\'s today!|{1} Only one more day: tomorrow, :date.|[2,*] :count more days: :date.',

    'blocked' => [
        'too_late' => '{1} You saved your date too close to your birthday: it had to be at least 1 day before. No gift this year, but it will be waiting for you next year!|[2,*] You saved your date too close to your birthday: it had to be at least :count days before. No gift this year, but it will be waiting for you next year!',
        'account' => '{1} Your account is too recent: it had to be created at least 1 day before your birthday. No gift this year, but it will be waiting for you next year!|[2,*] Your account is too recent: it had to be created at least :count days before your birthday. No gift this year, but it will be waiting for you next year!',
    ],

    'blocked_next' => [
        'too_late' => '{1} You saved your date too close to your birthday (it must be at least 1 day before): no gift this year, but it will be waiting for you next year.|[2,*] You saved your date too close to your birthday (it must be at least :count days before): no gift this year, but it will be waiting for you next year.',
        'account' => '{1} Your account is too recent (it must have been created at least 1 day before the birthday): no gift this year, but it will be waiting for you next year.|[2,*] Your account is too recent (it must have been created at least :count days before the birthday): no gift this year, but it will be waiting for you next year.',
    ],

    'reward' => [
        'title' => 'The gift',
        'money' => ':money on your account',
        'ingame' => 'An in-game bonus',
        'none' => 'The gift has not been configured yet.',
        'grace' => '{0} To claim on the day itself.|{1} To claim on the day itself or the day after.|[2,*] To claim on the day itself or within the following :count days.',
    ],

    'date' => ':month :day',

    'months' => [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ],

    'months_short' => [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'May',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Aug',
        9 => 'Sep',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Dec',
    ],

    'saved' => 'Your birthday has been saved!',
    'claimed' => 'Happy birthday! You received: :reward.',

    'errors' => [
        'date' => 'This date does not exist.',
        'already_saved' => 'Your birthday is already saved.',
        'no_birthday' => 'Save your birthday first.',
        'not_now' => 'Your gift is not available today.',
        'too_late' => 'You saved your date too close to your birthday to receive the gift this year.',
        'account' => 'Your account is too recent to receive the gift this year.',
        'already_claimed' => 'You have already claimed your gift this year.',
        'delivery' => 'The gift could not be delivered (game server unreachable?). Nothing was used up, please try again later.',
    ],

    'notifications' => [
        'today' => 'Happy birthday! Your gift ":reward" is waiting for you.',
        'waiting' => 'Your birthday gift ":reward" is still available until :date.',
    ],
];
