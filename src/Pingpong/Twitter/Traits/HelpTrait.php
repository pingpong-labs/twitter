<?php namespace Pingpong\Twitter\Traits;

trait HelpTrait
{

    /**
     * Returns the current configuration used by Twitter including twitter.com slugs which are not usernames,
     * maximum photo resolutions, and t.co URL lengths.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getHelpConfiguration(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get("help/configuration", $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns the list of languages supported by Twitter along with the language code supported by Twitter.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getHelpLanguages(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get("help/languages", $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns Twitter's Privacy Policy.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getHelpPrivacy(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get("help/privacy", $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns the Twitter Terms of Service. Note: these are not the same as the Developer Rules of the Road.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getHelpTos(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get("help/tos", $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns the current rate limits for methods belonging to the specified resource families.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getApplicationRateLimitStatus(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get("application/race_limit_status", $parameters, $multipart, $appOnlyAuth);
    }
}
