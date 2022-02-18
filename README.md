# Laravel wire
[![Latest Version on Packagist](https://img.shields.io/packagist/v/druc/laravel-wire.svg?style=flat-square)](https://packagist.org/packages/druc/laravel-wire)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/druc/laravel-wire/run-tests?label=tests)](https://github.com/druc/laravel-wire/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/druc/laravel-wire/Check%20&%20fix%20styling?label=code%20style)](https://github.com/druc/laravel-wire/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/druc/laravel-wire.svg?style=flat-square)](https://packagist.org/packages/druc/laravel-wire)

Import files and database from your application environments.

**Warning**: This package is highly experimental and potentially dangerous :)

## Installation

You can install the package via composer:

```bash
composer require druc/laravel-wire
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag="wire-config"
```

This is the contents of the published config file:

```php
return [
    'default' => 'stage',
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
```

## Database import

```bash
# import all tables from default environment 
php artisan wire:db 

# import all tables from dev environment
php artisan wire:db dev

# import specific tables 
php artisan wire:db -t users,orders,order_items

# import while excluding specific tables 
php artisan wire:db -e users
```

## Files import
```bash
# import all files from paths found in the config file
php artisan wire:files

# import specific file paths
php artisan wire:files storage/avatars

# import while excluding specific file paths
php artisan wire:files storage --exclude=storage/media-library

# import from specific environment
php artisan wire:files --env=staging
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Constantin Druc](https://github.com/druc)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
