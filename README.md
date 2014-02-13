Simple Twitter OAuth for Laravel 4
==================================

### Example
```php
Route::get('/', function()
{
	$callback = url('twitter/callback');
	return Twitter::authorize($callback);
});

Route::get('callback', function()
{
	$callback = Twitter::getCallback();

	if($callback == 200)
	{
		return Redirect::to('twitter/share')->withFlashSuccess('Your twitter is connected.');
	}
	return Redirect::to('/')->withFlashError('Connecting failed.');
});

Route::get('share', function()
{
	$tweet = Twitter::tweet('Hello....123');
	if($tweet->isOk())
	{
		return 'Okey';
	}
	return 'No';
});
```