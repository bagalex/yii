<?php
return [
    'dashboard' => [
        'type' => 2,
        'description' => 'Админ панель',
    ],
    'guest' => [
        'type' => 1,
        'description' => 'Гость',
        'ruleName' => 'userRole',
    ],
    'user' => [
        'type' => 1,
        'description' => 'Пользователь',
        'ruleName' => 'userRole',
        'children' => [
            'guest',
            'dashboard',
        ],
    ],
    'admin' => [
        'type' => 1,
        'description' => 'Администратор',
        'ruleName' => 'userRole',
        'children' => [
            'user',
        ],
    ],
];
