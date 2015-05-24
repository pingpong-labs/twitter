Twitter OAuth for Laravel
==================================

[![Build Status](https://travis-ci.org/pingpong-labs/twitter.svg)](https://travis-ci.org/pingpong-labs/twitter)
[![Latest Stable Version](https://poser.pugx.org/pingpong/twitter/v/stable.svg)](https://packagist.org/packages/pingpong/twitter)
[![Total Downloads](https://poser.pugx.org/pingpong/twitter/downloads.svg)](https://packagist.org/packages/pingpong/twitter)
[![Latest Unstable Version](https://poser.pugx.org/pingpong/twitter/v/unstable.svg)](https://packagist.org/packages/pingpong/twitter)
[![License](https://poser.pugx.org/pingpong/twitter/license.svg)](https://packagist.org/packages/pingpong/twitter)
[![HHVM Status](http://hhvm.h4cc.de/badge/pingpong/twitter.svg)](http://hhvm.h4cc.de/package/pingpong/twitter)

### Server Requirements

    Require PHP 5.4+ or higher.
    
### Installation

> For Laravel 4.* please use version `1.*`.

Open your composer.json file, and add the new required package.

```
  "pingpong/twitter": "~2" 
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
  php artisan vendor:publish --provider="Pingpong\Twitter\TwitterServiceProvider" 
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

Set return format.
```php
Twitter::format('json');

Twitter::format('array');

Twitter::format('object');
```

Enable and disable curl.
```php
Twitter::enableCurl();

Twitter::disableCurl();
```

Set connection and request timeout.
```php
Twitter::setConnectionTimeout(2000);

Twitter::setTimeout(500);
```

Allows a Consumer application to exchange the OAuth Request Token for an OAuth Access Token with xAuth.
```php
Twitter::xAuth($username, $password);
```

Set token.
```php
Twitter::setToken($oauthToken, $oauthTokenSecret);
```

Get bearer token.
```php
$token = Twitter::getBearerToken();
```

Set bearer token.
```php
Twitter::setBearerToken($token);
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

### REST API v1.1 Resources

#### Timelines

Timelines are collections of Tweets, ordered with the most recent first.

###### GET statuses/mentions_timeline

Returns the 20 most recent mentions (tweets containing a users's @screen_name) for the authenticating user.

```php
Twitter::getStatusesMentionsTimeline($parameters, $multipart, $appOnlyAuth);
```

###### GET statuses/user_timeline

Returns a collection of the most recent Tweets posted by the user indicated by the screen_name or user_id parameters.

```php
Twitter::getStatusesUserTimeline($parameters, $multipart, $appOnlyAuth);
```
###### GET statuses/home_timeline

Returns a collection of the most recent Tweets and retweets posted by the authenticating user and the users they follow. 

```php
Twitter::getStatusesHomeTimeline($parameters, $multipart, $appOnlyAuth);
```
###### GET statuses/retweet_of_me

Returns the most recent tweets authored by the authenticating user that have been retweeted by others. 

```php
Twitter::getStatusesRetweetsOfMe($parameters, $multipart, $appOnlyAuth);
```

#### Tweets

Tweets are the atomic building blocks of Twitter, 140-character status updates with additional associated metadata.
 
###### GET statuses/retweets/:id

Returns a collection of the 100 most recent retweets of the tweet specified by the id parameter.

```php
Twitter::getStatusesRetweets($id, $parameters, $multipart, $appOnlyAuth);
```

###### GET statuses/show/:id

Returns a single Tweet, specified by the id parameter. 

```php
Twitter::getStatusesShow($id, $parameters, $multipart, $appOnlyAuth);
```

###### POST statuses/destroy/:id

Destroys the status specified by the required ID parameter. 

```php
Twitter::postStatusesDestroy($id, $parameters, $multipart, $appOnlyAuth);
```

###### POST statuses/update

Updates the authenticating user's current status, also known as tweeting. 

```php
Twitter::postStatusesUpdate($parameters, $multipart, $appOnlyAuth);
```

###### POST statuses/retweet/:id

Retweets a tweet.

```php
Twitter::postStatusesRetweet($id, $parameters, $multipart, $appOnlyAuth);
```

###### POST statuses/update_with_media

Updates the authenticating user's current status and attaches media for upload.

```php
Twitter::postStatusesUpdateWithMedia($parameters, $appOnlyAuth);
```

###### GET statuses/oembed

Returns information allowing the creation of an embedded representation of a Tweet on third party sites. 

```php
Twitter::getStatusesOembed($parameters, $multipart, $appOnlyAuth);
```

###### GET statuses/retweeters/ids

Returns a collection of up to 100 user IDs belonging to users who have retweeted the tweet specified by the id parameter.

```php
Twitter::getStatusesRetweetersIds($parameters, $multipart, $appOnlyAuth);
```

#### Search

Find relevant Tweets based on queries performed by your users.

###### GET search/tweets

Returns a collection of relevant Tweets matching a specified query. 

```php
Twitter::getSearchTweets($parameters, $multipart, $appOnlyAuth);
```

###### Direct Messages

Direct Messages are short, non-public messages sent between two users.

```php
Twitter::getSearchTweets($parameters, $multipart, $appOnlyAuth);
```

###### GET direct_messages

Returns the 20 most recent direct messages sent to the authenticating user. 

```php
Twitter::getDirectMessages($parameters, $multipart, $appOnlyAuth);
```

###### GET direct_messages/sent

Returns the 20 most recent direct messages sent by the authenticating user.

```php
Twitter::getDirectMessagesSent($parameters, $multipart, $appOnlyAuth);
```

###### GET direct_messages/show

Returns a single direct message, specified by an id parameter. 

```php
Twitter::getDirectMessagesShow($parameters, $multipart, $appOnlyAuth);
```

###### POST direct_messages/destroy

Destroys the direct message specified in the required ID parameter. 

```php
Twitter::postDirectMessagesDestroy($parameters, $multipart, $appOnlyAuth);
```

###### POST direct_messages/new

Sends a new direct message to the specified user from the authenticating user.

```php
Twitter::postDirectMessagesNew($parameters, $multipart, $appOnlyAuth);
```

#### Favorites
Users favorite tweets to give recognition to awesome tweets, to curate the best of Twitter, to save for reading later, and a variety of other reasons. Likewise, developers make use of "favs" in many different ways.

###### GET favorites/list
Returns the 20 most recent Tweets favorited by the authenticating or specified user.

```php
Twitter::getFavoritesList($parameters, $multipart, $appOnlyAuth);
```
	
###### POST favorites/destroy
Un-favorites the status specified in the ID parameter as the authenticating user. 

```php
Twitter::postFavoritesDestroy($parameters, $multipart, $appOnlyAuth);
```

###### POST favorites/create
Favorites the status specified in the ID parameter as the authenticating user.

```php
Twitter::postFavoritesCreate($parameters, $multipart, $appOnlyAuth);
```

**NOTE: Not all functions and Facade APIs documented**

### License

This package is open-sourced software licensed under [The BSD 3-Clause License](http://opensource.org/licenses/BSD-3-Clause)