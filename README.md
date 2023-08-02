# Filesystem adapter for the ImageKit API

A configuration adapter for [ImageKit](https://imagekit.io/) inspired by [TaffoVelikof](https://github.com/TaffoVelikoff).
This package can be used for php or Laravel project. Let's install to your project by the following instructions below:

## Contents

[⚙️ Installation](#installation)

[🛠️ Setup imagekit.io](#setup)

[👩‍💻 Usage in PHP Native](#usage)

[🚀 Usage in Laravel](#usage-in-laravel)

[👩‍💻 Usage with CKEditor & Laravel](#usage-with-ckeditor-laravel)

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
      'endpoint_url' => env('IMAGEKIT_ENDPOINT_URL'),
      'throw' => false,
  ]
]
```

Don't forget to add your keys in `.env`:

```php
IMAGEKIT_PUBLIC_KEY=your-public-key
IMAGEKIT_PRIVATE_KEY=your-private-key
IMAGEKIT_ENDPOINT_URL=your-endpint-url
```

And now you can use Laravel's Storage facade:

```php
$result = Storage::disk('imagekit')->put('index.txt', 'This is an index file.');
return response($result);
```

## Usage with CKEditor & Laravel

Install ckeditor 5 from the official documentation here https://ckeditor.com/docs/index.html and make sure you have installed [Upload Adapter](https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/simple-upload-adapter.html#installation)

then setup your client side like so:

```html
<script>
  ClassicEditor.create(document.querySelector("#editor"), {
    simpleUpload: {
      // The URL that the images are uploaded to.
      uploadUrl: "{{ route('ckeditor.upload') . '?_token=' . csrf_token() }}",
    },
  }).catch(error => {
    console.error(error);
  });
</script>
```

then you can create the method controller like so:

```php
public function upload(Request $request)
{
    if ($request->hasFile('upload')) {
        $request->validate([
            'upload' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $file = $request->file('upload');

        $result = Storage::disk('imagekit')->put('ckeditor', $file);
        $url = env('IMAGEKIT_ENDPOINT_URL') . $result;
        return response()->json(['fileName' => $result, 'uploaded' => 1, 'url' => $url]);
    }
}
```

Ultimately you would see the magic if you click the insert image on the ckeditor toolbar.

Happy Coding Thank You :)
