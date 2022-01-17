<?php

return [
    /*
    | Specify which of the environments below you wish
    | to use as your default wire.
    */
    'default' => 'stage',

    /*
    | Here you can setup a list of all your project environments.
    |
    | url - the environment url
    | auth_key - should be a hard to guess, randomized string
    | file_paths - file paths to download when using the wire:files command
    | excluded_file_paths - file paths to exclude when using the wire:files command
    | basic_auth - if your environment has basic auth in place, enable it here
    |              and add the username and password so wire can authenticate
    |
    */
    'environments' => [
        'stage' => [
            'url' => 'https://your-stage-environment.com',
            'auth_key' => 'BqNbqyoxswma4bYzj8rnsAhfySp0york',
            'file_paths' => ['storage'],
            'excluded_file_paths' => [],
            'basic_auth' => [
                'enabled' => false,
                'username' => 'johndoe',
                'password' => 'supersecret'
            ]
        ]
    ]
];
