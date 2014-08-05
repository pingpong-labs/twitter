### Simple Twitter OAuth for Laravel 4

### Server Requirements

    Require PHP 5.4+ or higher.
    
### Installation

Open your composer.json file, and add the new required package.

```
  "pingpong/twitter": "1.0.*" 
```

Next, open a terminal and run.

```
  composer update 
```

After the composer updated. Add new service provider in app/config/app.php.

```php
	'Pingpong\Twitter\TwitterServiceProvider'
```

Next, Add new alias.

```php
    'Twitter'           => 'Pingpong\Twitter\Facades\Twitter',
```

Next, open a terminal and run.

```
  php artisan config:publish pingpong/twitter 
```

Done.

### Configuration File

```php
return array(
	'consumer_key'		=>	'',
	'consumer_secret'	=>  '',
	
	'oauth_token'		=>	null,
	'oauth_token_secret'=>  null,

	'bearer_token'		=>	null,
	
	'callback_url'		=>  url('twitter/callback'),
    'fallback_url'      =>  url('/')
);
```

### Usage

Authorize the user.

```php
	Twitter::authorize();
```

Authenticate the user.

```php
	Twitter::authenticate();
```

You can also override the callback url when authorize or authenticate the user.

```php
$callbackUrl = url('twitter/getcallback');

Twitter::authorize($callbackUrl);

Twitter::authenticate($callbackUrl);
```

Get callback after authorize or authenticate the user.

```php
Twitter::getCallback();

// or using `callback` method

Twitter::callback();
```

Get account verify credentials.

```php
Twitter::getAccountVerifyCredentials();

// you can also specify what parameters want you use

$parameters = array();

Twitter::getAccountVerifyCredentials($parameters);

// or using `getCredentials` method

Twitter::getCredentials($parameters);
```

Global API call. 

```php
Twitter::api($method, $path, $parameters, $multipart, $appOnlyAuth);

Twitter::api('GET', '/path');

Twitter::api('POST', '/path', $parameters);

Twitter::api('PUT', '/path', $parameters);

Twitter::api('PATCH', '/path', $parameters);

Twitter::api('DELETE', '/path/to', $parameters);
```

Helper method for call Twitter API.

GET Request
```php
Twitter::get('/path', $parameters);
```

POST Request
```php
Twitter::post('/path', $parameters);
```

PUT Request
```php
Twitter::put('/path', $parameters);
```

PATCH Request
```php
Twitter::patch('/me', $parameters);
```

DELETE Request
```php
Twitter::delete('/me', $parameters);
```

### Example

Authenticate the user.
```php
Route::get('twitter/authenticate', function()
{
    return Twitter::authenticate();
});
```

Authorize the user.
```php
Route::get('twitter/authorize', function()
{
    return Twitter::authorize();
});
```

Get twitter callback.
```php
Route::get('twitter/callback', function()
{
    try
    {
        $callback = Twitter::getCallback();
        
        dd($callback);
    }
    catch(Pingpong\Twitter\Exceptions\TwitterApiException $e)
    {
        var_dump($e->getMessage());
        var_dump($e->getResponse());
    }
});
```

Logout the user.
```php
Route::get('twitter/logout', function()
{
    Twitter::logout();
    
    return Redirect::home();
});
```

Post tweet.
```php
Route::get('twitter/tweet', function()
{
    try
    {
        $status = 'Hello world!';
        
        $response = Twitter::tweet($status);
        
        dd($response);
    }
    catch(Pingpong\Twitter\Exceptions\TwitterApiException $e)
    {
        var_dump($e->getMessage());
        var_dump($e->getResponse());
    }
});
```

Upload media.
```php
Route::get('twitter/upload', function()
{
    try
    {
        $status = 'Hello world!';
        $media  = '/path/to/your-media.ext';
        
        $response = Twitter::upload($status, $media);
        
        dd($response);
    }
    catch(Pingpong\Twitter\Exceptions\TwitterApiException $e)
    {
        var_dump($e->getMessage());
        var_dump($e->getResponse());
    }
});
```

### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).