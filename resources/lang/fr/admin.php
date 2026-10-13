<?php

return [
    'nav' => [
        'title' => 'Anniversaires',
        'players' => 'Joueurs',
        'settings' => 'Paramètres',
    ],

    'permissions' => [
        'admin' => 'Gérer les anniversaires (joueurs et paramètres)',
    ],

    'players' => [
        'title' => 'Anniversaires des joueurs',
        'stats' => [
            'total' => 'Anniversaires enregistrés',
            'soon' => 'Dans les 30 prochains jours',
            'claimed' => 'Cadeaux récupérés en :year',
        ],
        'search' => 'Rechercher un joueur',
        'results' => 'Membres trouvés',
        'none' => 'Aucun anniversaire enregistré pour le moment.',
        'birthday' => 'Anniversaire',
        'next' => 'Prochain anniversaire',
        'gift' => 'Cadeau',
        'manage' => 'Gérer',
        'trusted' => 'Date définie par le staff : les délais anti-abus ne s\'appliquent pas',
        'today' => 'Aujourd\'hui',
        'claimed' => 'Récupéré',
        'not_eligible' => 'Non éligible',
        'available' => 'À récupérer',
        'player' => 'Anniversaire de :user',
        'no_birthday' => 'Pas encore d\'anniversaire enregistré.',
        'reasons' => [
            'too_late' => 'Date enregistrée trop près de l\'anniversaire.',
            'account' => 'Compte trop récent.',
        ],
        'set' => 'Définir ou modifier la date',
        'set_info' => 'Une date définie par le staff n\'est pas soumise aux délais anti-abus.',
        'reset_claim' => 'Réinitialiser le cadeau',
        'reset_claim_info' => 'Réinitialiser permet au joueur de récupérer à nouveau le cadeau de l\'anniversaire en cours (utile pour tester).',
        'delete' => 'Supprimer la date',
        'delete_confirm' => 'Supprimer la date d\'anniversaire de ce joueur ? Il pourra en enregistrer une nouvelle.',
        'history' => 'Cadeaux récupérés',
        'no_claims' => 'Aucun cadeau récupéré pour le moment.',
        'claim_year' => 'Anniversaire :year',
    ],

    'settings' => [
        'title' => 'Paramètres des anniversaires',
        'reward' => 'Cadeau',
        'reward_name' => 'Nom du cadeau',
        'reward_name_info' => 'Affiché aux joueurs. Laissez vide pour « Cadeau d\'anniversaire » (traduit selon la langue du site).',
        'money' => 'Argent à donner',
        'money_info' => 'Montant ajouté au solde du joueur quand il récupère son cadeau. Laissez vide pour ne pas donner d\'argent.',
        'servers' => 'Serveurs où exécuter les commandes',
        'commands' => 'Commandes à exécuter',
        'commands_info' => 'Exécutées sur les serveurs choisis quand le joueur récupère son cadeau. Variable disponible : :placeholders.',
        'need_online' => 'Le joueur doit être connecté au serveur pour recevoir les commandes',
        'rules' => 'Règles',
        'days' => 'jours',
        'grace_days' => 'Délai pour récupérer le cadeau',
        'grace_days_info' => 'Nombre de jours après l\'anniversaire pendant lesquels le cadeau reste disponible (0 = le jour même).',
        'min_days_before' => 'Date enregistrée au moins',
        'min_days_before_info' => 'Avant l\'anniversaire, pour avoir droit au cadeau de l\'année : empêche d\'enregistrer une fausse date le jour même.',
        'min_account_days' => 'Compte créé au moins',
        'min_account_days_info' => 'Avant l\'anniversaire, pour avoir droit au cadeau de l\'année : limite les comptes jetables.',
        'page' => 'Page et notifications',
        'notifications' => 'Notifier le joueur le jour de son anniversaire (à sa première visite)',
        'user_menu' => 'Afficher la page dans le menu du joueur (clic sur son pseudo)',
        'welcome' => 'Texte d\'accueil (affiché en haut de la page, Markdown accepté)',
    ],

    'logs' => [
        'settings' => 'Mise à jour des paramètres des anniversaires',
        'player' => 'Modification de l\'anniversaire d\'un joueur (#:id)',
    ],
];
