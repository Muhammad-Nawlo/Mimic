<?php

return [
    'role_structure' => [
        'super' => [
            'users'       => 'c,r,u,d',
            'roles'       => 'c,r,u,d',
            'countries'   => 'c,r,u,d',
            'cities'      => 'c,r,u,d',
            'categories'  => 'c,r,u,d',
            'clients'     => 'c,r,u,d',
            'ranks'       => 'c,r,u,d',
            'hashtags'    => 'c,r,u,d',
            'challenges'  => 'c,r,u,d',
            'videos'      => 'c,r,u,d',
            'stories'      => 'c,r,u,d',
            'reports'      => 'c,r,u,d',
            'notifications'      => 'c,r,u,d',

        ],
    ],
    // 'permission_structure' => [
    //     'cru_user' => [
    //         'profile' => 'c,r,u'
    //     ],
    // ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
