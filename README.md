Simple Twitter OAuth for Laravel 4
==================================

### Installation
Open your composer.json file, and add the new required package.

```
  "pingpong/twitter": "dev-master" 
```

Next, open a terminal and run.

```
  composer update 
```

After the composer updated. Add new service provider in app/config/app.php.

```
  'Pingpong\Twitter\TwitterServiceProvider'
```

Next, open a terminal and run.

```
  php artisan config:publish pingpong/twitter 
```

Done.

### Configuration File

Add your consumer key and consumer secret in `[root]/app/config/packages/pingpong/twitter/twitter.php`

```php

return array(
	'consumer_key'		=>	'',
	'consumer_secret'	=>	''
);
```

### Example

Authorize:

```php
Route::get('/', function()
{
	$callback = url('twitter/callback');
	return Twitter::authorize($callback);
});
```
Get callback after authorize:

```php
Route::get('callback', function()
{
	$callback = Twitter::getCallback();

	if($callback == 200)
	{
		return Redirect::to('share')->withFlashSuccess('Your twitter is connected.');
	}
	return Redirect::to('/')->withFlashError('Connecting failed.');
});
```

Post Tweet :

```php
Route::get('share', function()
{
	$tweet = Twitter::tweet('Hello....123');
	if($tweet->isOk())
	{
		return 'Okay';
	}
	return 'No';
});
```

Upload Image/Media to Twitter:

```php
Route::get('upload', function()
{
	$options = array(
		'status'	=>	'Example upload message';
		'media[]'	=>	'path/to/your/image.jpg'
	);
	$tweet = Twitter::upload($options);
	if($tweet->isOk())
	{
		return 'Okay';
	}
	return 'No';
});
```

Search :

```php
Route::get('search', function()
{
	$query = Input::get('q');
	$tweet = Twitter::search($query);
	if($tweet->isOk())
	{
		return $tweet->getResponse();
		// return $tweet->getResponseJson();
	}
	return 'No';
});
```

More documentation, please check this repo [jublonet/codebird-php](https://github.com/jublonet/codebird-php). This package is using that package as base twitter api. 
