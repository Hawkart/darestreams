<?php

namespace App\Acme\Helpers\Streamlabs;

/**
 * Class StreamlabsApi
 * @package App\Acme\Helpers\Streamlabs
 */
class StreamlabsApi extends StreamlabsRequest
{
    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var array
     */
    protected $scope;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * Instantiate a new TwitchApi instance
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        /*if (isset($options['client_id'])) {
            throw new ClientIdRequiredException();
        }

        $this->setClientId($options['client_id']);*/
        $this->setClientSecret(isset($options['client_secret']) ? $options['client_secret'] : null);
        $this->setRedirectUri(isset($options['redirect_uri']) ? $options['redirect_uri'] : null);
        $this->setScope(isset($options['scope']) ? $options['scope'] : []);
    }

    /**
     * Set client ID
     *
     * @param string
     */
    public function setClientId($clientId)
    {
        $this->clientId = (string) $clientId;
    }

    /**
     * Get client ID
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set client secret
     *
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = (string) $clientSecret;
    }

    /**
     * Get client secret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set redirect URI
     *
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = (string) $redirectUri;
    }

    /**
     * Get redirect URI
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * Set scope
     *
     * @param array $scope
     * @throws InvalidTypeException
     */
    public function setScope($scope)
    {
        if (!is_array($scope)) {
            //throw new InvalidTypeException('Scope', 'array', gettype($scope));
        }

        $this->scope = $scope;
    }

    /**
     * Get scope
     *
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }

    public function alert($params)
    {
        $data = [
            "type" => "host",
            "image_href" => "https://darestreams.com/static/images/logo_small.png",
            "sound_href" => "",
            "message" => "",
            "user_message" => "",
            "duration" => 5000, //5secs
            "access_token" => ""
        ];

        $data = array_merge($data, $params);

        return $this->post('alerts', $data, null);
    }
}