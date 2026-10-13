<?php

return [
    'nav' => [
        'title' => 'Birthdays',
        'players' => 'Players',
        'settings' => 'Settings',
    ],

    'permissions' => [
        'admin' => 'Manage birthdays (players and settings)',
    ],

    'players' => [
        'title' => 'Players\' birthdays',
        'stats' => [
            'total' => 'Birthdays saved',
            'soon' => 'In the next 30 days',
            'claimed' => 'Gifts claimed in :year',
        ],
        'search' => 'Search a player',
        'results' => 'Members found',
        'none' => 'No birthday has been saved yet.',
        'birthday' => 'Birthday',
        'next' => 'Next birthday',
        'gift' => 'Gift',
        'manage' => 'Manage',
        'trusted' => 'Date set by the staff: the anti-abuse delays do not apply',
        'today' => 'Today',
        'claimed' => 'Claimed',
        'not_eligible' => 'Not eligible',
        'available' => 'Available',
        'player' => 'Birthday of :user',
        'no_birthday' => 'No birthday saved yet.',
        'reasons' => [
            'too_late' => 'Date saved too close to the birthday.',
            'account' => 'Account too recent.',
        ],
        'set' => 'Set or change the date',
        'set_info' => 'A date set by the staff is not subject to the anti-abuse delays.',
        'reset_claim' => 'Reset the gift',
        'reset_claim_info' => 'Resetting lets the player claim the gift of the current birthday again (useful for testing).',
        'delete' => 'Delete the date',
        'delete_confirm' => 'Delete the birthday of this player? They will be able to save a new one.',
        'history' => 'Gifts claimed',
        'no_claims' => 'No gift claimed yet.',
        'claim_year' => 'Birthday :year',
    ],

    'settings' => [
        'title' => 'Birthday settings',
        'reward' => 'Gift',
        'reward_name' => 'Gift name',
        'reward_name_info' => 'Shown to the players. Leave empty for "Birthday gift" (translated to the language of the website).',
        'money' => 'Money to give',
        'money_info' => 'Amount added to the balance of the player when they claim the gift. Leave empty to give no money.',
        'servers' => 'Servers where the commands are run',
        'commands' => 'Commands to run',
        'commands_info' => 'Run on the chosen servers when the player claims the gift. Available variable: :placeholders.',
        'need_online' => 'The player must be online on the server to receive the commands',
        'rules' => 'Rules',
        'days' => 'days',
        'grace_days' => 'Time to claim the gift',
        'grace_days_info' => 'Number of days after the birthday during which the gift stays available (0 = the day itself).',
        'min_days_before' => 'Date saved at least',
        'min_days_before_info' => 'Before the birthday, to be entitled to the gift of the year: prevents saving a fake date on the day itself.',
        'min_account_days' => 'Account created at least',
        'min_account_days_info' => 'Before the birthday, to be entitled to the gift of the year: limits throwaway accounts.',
        'page' => 'Page and notifications',
        'notifications' => 'Notify the player on their birthday (on their first visit)',
        'user_menu' => 'Show the page in the user menu (click on the username)',
        'welcome' => 'Welcome text (shown at the top of the page, Markdown allowed)',
    ],

    'logs' => [
        'settings' => 'Birthday settings updated',
        'player' => 'Birthday of a player changed (#:id)',
    ],
];
