<?php

return  array(
    array(
        'id' => 1,
        'profile' => array(
            'name' => 'unknown1',
            'age' => 18,
            'friend' => array(
                'id'   => 2,
                'name' => 'unknown2',
                'age'  => 18
            )
        ),
        'created_at' => '2016-10-10'
    ),
    array(
        'id' => 2,
        'profile' => array(
            'name' => 'unknown2',
            'age' => 18,
            'friend' => array(
                'id'   => 3,
                'name' => 'unknown3',
                'age'  => 18
            )
        ),
        'created_at' => '2016-10-12'
    ),
    array(
        'id' => 3,
        'profile' => array(
            'name' => 'unknown3',
            'age' => 18
        ),
        'created_at' => '2016-10-13'
    ),
    array(
        'id' => 4,
        'profile' => array(
            'name' => 'u%',
            'age' => 18
        ),
        'created_at' => '2016-10-13'
    )
);