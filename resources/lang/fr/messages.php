<?php

return [
    'title' => 'Anniversaire',
    'reward_default' => 'Cadeau d\'anniversaire',

    'login' => 'Connectez-vous pour enregistrer votre anniversaire et recevoir votre cadeau.',
    'your_birthday' => 'Votre anniversaire',
    'locked' => 'La date ne peut plus être modifiée. En cas d\'erreur, contactez le staff.',

    'save' => [
        'title' => 'Enregistrez votre anniversaire',
        'info' => 'Indiquez le jour et le mois de votre anniversaire (pas besoin de l\'année) et recevez un cadeau chaque année.',
        'day' => 'Jour',
        'month' => 'Mois',
        'button' => 'Enregistrer ma date',
        'confirm' => 'Attention : une fois enregistrée, la date ne pourra plus être modifiée. Confirmer ?',
    ],

    'rules' => [
        'intro' => 'Conditions anti-abus pour recevoir le cadeau d\'une année :',
        'days_before' => '{1} la date doit être enregistrée au moins 1 jour avant l\'anniversaire ;|[2,*] la date doit être enregistrée au moins :count jours avant l\'anniversaire ;',
        'account_days' => '{1} votre compte doit avoir été créé au moins 1 jour avant l\'anniversaire.|[2,*] votre compte doit avoir été créé au moins :count jours avant l\'anniversaire.',
    ],

    'happy_birthday' => 'Joyeux anniversaire, :name !',
    'gift_waiting' => 'Votre cadeau d\'anniversaire vous attend !',
    'claim_until' => 'Vous pouvez le récupérer jusqu\'au :date.',
    'claim' => 'Récupérer : :reward',
    'already_claimed' => 'Vous avez déjà récupéré votre cadeau « :reward ». Passez une très belle journée !',
    'next_gift' => 'Prochain cadeau : le :date.',

    'in_days' => '{0} C\'est aujourd\'hui !|{1} Plus qu\'un jour : demain, le :date.|[2,*] Plus que :count jours : le :date.',

    'blocked' => [
        'too_late' => '{1} Vous avez enregistré votre date trop près de votre anniversaire : il fallait le faire au moins 1 jour avant. Pas de cadeau cette année, mais il vous attendra l\'an prochain !|[2,*] Vous avez enregistré votre date trop près de votre anniversaire : il fallait le faire au moins :count jours avant. Pas de cadeau cette année, mais il vous attendra l\'an prochain !',
        'account' => '{1} Votre compte est trop récent : il devait avoir été créé au moins 1 jour avant votre anniversaire. Pas de cadeau cette année, mais il vous attendra l\'an prochain !|[2,*] Votre compte est trop récent : il devait avoir été créé au moins :count jours avant votre anniversaire. Pas de cadeau cette année, mais il vous attendra l\'an prochain !',
    ],

    'blocked_next' => [
        'too_late' => '{1} Vous avez enregistré votre date trop près de votre anniversaire (il faut le faire au moins 1 jour avant) : pas de cadeau cette année, mais il vous attendra l\'an prochain.|[2,*] Vous avez enregistré votre date trop près de votre anniversaire (il faut le faire au moins :count jours avant) : pas de cadeau cette année, mais il vous attendra l\'an prochain.',
        'account' => '{1} Votre compte est trop récent (il doit avoir été créé au moins 1 jour avant l\'anniversaire) : pas de cadeau cette année, mais il vous attendra l\'an prochain.|[2,*] Votre compte est trop récent (il doit avoir été créé au moins :count jours avant l\'anniversaire) : pas de cadeau cette année, mais il vous attendra l\'an prochain.',
    ],

    'reward' => [
        'title' => 'Le cadeau',
        'money' => ':money sur votre compte',
        'ingame' => 'Un bonus en jeu',
        'none' => 'Le cadeau n\'est pas encore configuré.',
        'grace' => '{0} À récupérer le jour même.|{1} À récupérer le jour même ou le lendemain.|[2,*] À récupérer le jour même ou dans les :count jours qui suivent.',
    ],

    'saved' => 'Votre anniversaire est enregistré !',
    'claimed' => 'Joyeux anniversaire ! Vous avez reçu : :reward.',

    'errors' => [
        'date' => 'Cette date n\'existe pas.',
        'already_saved' => 'Votre anniversaire est déjà enregistré.',
        'no_birthday' => 'Enregistrez d\'abord votre anniversaire.',
        'not_now' => 'Votre cadeau n\'est pas disponible aujourd\'hui.',
        'too_late' => 'Vous avez enregistré votre date trop près de votre anniversaire pour recevoir le cadeau cette année.',
        'account' => 'Votre compte est trop récent pour recevoir le cadeau cette année.',
        'already_claimed' => 'Vous avez déjà récupéré votre cadeau cette année.',
        'delivery' => 'Le cadeau n\'a pas pu être livré (serveur de jeu injoignable ?). Rien n\'a été utilisé, réessayez plus tard.',
    ],

    'notifications' => [
        'today' => 'Joyeux anniversaire ! Votre cadeau « :reward » vous attend.',
        'waiting' => 'Votre cadeau d\'anniversaire « :reward » est encore disponible jusqu\'au :date.',
    ],
];
