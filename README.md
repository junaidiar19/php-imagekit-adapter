# Filesystem adapter for the ImageKit API

A configuration adapter for [ImageKit](https://imagekit.io/) inspired by [TaffoVelikof](https://github.com/TaffoVelikoff).
This package can be used for php or Laravel project. Let's install to your project by the following instructions below:

## Contents

[⚙️ Installation](#installation)

[🛠️ Setup imagekit.io](#setup)

[👩‍💻 Usage in PHP Native](#usage)

[🚀 Usage in Laravel](#usage-in-laravel)

## Installation

You can install the package via composer:

```bash
composer require junaidiar/imagekit-adapter
```

## Setup

First of all you have to sign up for an [ImageKit](https://imagekit.io/) account. Then you can go to [https://imagekit.io/dashboard/developer/api-keys](https://imagekit.io/dashboard/developer/api-keys) to get your public key, private key and url endpoint.

## Usage in PHP Native

```php
use ImageKit\ImageKit;
use League\Flysystem\Filesystem;
use JunaidiAR\ImageKitAdapter\ImageKitAdapter;

// Setup Client
$client = new ImageKit (
    'your_public_key',
    'your_private_key',
    'your_endpoint_url'
);

// Adapter
$adapter = new ImagekitAdapter($client);

// Filesystem
$fsys = new Filesystem($adapter);

// Check if file exists example
$file = $fsys->fileExists('default-image.jpg');
```

## Usage in Laravel

You can add a new driver by extending the Storage in the `boot()` method of `AppServiceProvider` like so.

```php
public function boot()
{
    Storage::extend('imagekit', function ($app, $config) {
        $adapter = new ImagekitAdapter(
          new ImageKit(
              $config['key'],
              $config['secret'],
              $config['endpoint_url']
          ),
        );

        return new FilesystemAdapter(
            new Filesystem($adapter, $config),
            $adapter,
            $config
        );
    });
}
```

Then create a new disk in `config/filesystems.php`:

```php

'disks' => [

  ...

  'imagekit' => [
      'driver' => 'imagekit',
      'key' => env('IMAGEKIT_PUBLIC_KEY'),
      'secret' => env('IMAGEKIT_PRIVATE_KEY'),
      'url_endpoint' => env('IMAGEKIT_URL_ENDPOINT'),
      'throw' => false,
  ]
]
```

Don't forget to add your keys in `.env`:

```php
IMAGEKIT_PUBLIC_KEY = your-public-key
IMAGEKIT_PRIVATE_KEY = your-private-key
IMAGEKIT_URL_ENDPOINT = your-endpint-url
```

And now you can use Laravel's Storage facade:

```php
$result = Storage::disk('imagekit')->put('index.txt', 'This is an index file.');
return response($result);
```

Happy Coding:) Thank You.
