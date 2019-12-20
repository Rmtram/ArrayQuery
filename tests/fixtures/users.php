<?php

return [
    [
        'id' => 1,
        'profile' => [
            'name' => 'unknown1',
            'age' => 18,
            'sex' => null,
            'friend' => [
                'id' => 2,
                'name' => 'unknown2',
                'age' => 18,
            ]
        ],
        'created_at' => '2016-10-10'
    ],
    [
        'id' => 2,
        'profile' => [
            'name' => 'unknown2',
            'age' => 18,
            'sex' => 'man',
            'friend' => [
                'id' => 3,
                'name' => 'unknown3',
                'age' => 18,
            ]
        ],
        'created_at' => '2016-10-12'
    ],
    [
        'id' => 3,
        'profile' => [
            'name' => 'unknown3',
            'age' => 18,
            'sex' => 'woman',
        ],
        'created_at' => '2016-10-13'
    ],
    [
        'id' => 4,
        'profile' => [
            'name' => 'u%',
            'age' => 18
        ],
        'created_at' => '2016-10-13'
    ]
];
