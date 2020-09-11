<?php

namespace aWhere;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;

/**
 * Class Client
 *
 * @package aWhere
 */
class Client
{
    /**
     * @var string Client ID
     */
    protected $clientId;

    /**
     * @var string Client Secret
     */
    protected $clientSecret;

    /**
     * @var string|null Current Token
     */
    protected $token = null;

    /**
     * @var GuzzleHttp/Client Client to make requests
     */
    protected $httpClient;

    /**
     * Create a new Client
     *
     * @param string Client ID
     * @param string Client Secret
     * @param string Optional OAuth Token
     */
    public function __construct($clientId, $clientSecret, $token = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->httpClient = new HttpClient([
            'base_uri' => 'https://api.awhere.com'
        ]);

        if ($token) {
            if (is_array($token)) {
                $this->token = new AccessToken($token);
            } elseif ($token instanceof AccessToken) {
                $this->token = $token;
            }
        }
    }

    public function getToken()
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $this->clientId,
            'clientSecret'            => $this->clientSecret,
            'redirectUri'             => '',
            'urlAuthorize'            => 'https://api.awhere.com/oauth/authorize',
            'urlAccessToken'          => 'https://api.awhere.com/oauth/token',
            'urlResourceOwnerDetails' => 'https://api.awhere.com/oauth/resource'
        ], ['optionProvider' => new HttpBasicAuthOptionProvider()]);

        $this->token = $provider->getAccessToken('client_credentials');
        return $this->token;
    }

    public function isTokenValid()
    {
        return !empty($this->token)
            && !$this->token->hasExpired();
    }

    /**
     * Forecast Weather by Geolocation
     *
     * @param string $latitude Latitude
     * @param string $longitude Longitude
     * @param array  $options Additional parameters
     *
     * @return string JSON response
     *
     * @throws \InvalidTokenException, \ApiException
     */
    public function getWeatherForecastByGeolocation($latitude, $longitude, $options = [])
    {
        if (!$this->isTokenValid()) {
            throw new InvalidTokenException;
        }

        $options = array_only($options, ['limit', 'offset', 'sort', 'properties', 'units', 'blockSize', 'conditionsType', 'useLocalTime', 'utcOffset']);

        $uri = sprintf('/v2/weather/locations/%s,%s/forecasts', $latitude, $longitude);
        if (sizeof($options) > 0) {
            $uri .= '?' . http_build_query(array_map(function ($value) {
                return is_bool($value) ? ($value ? 'true' : 'false') : $value;
            }, $options));
        }

        try {
            $response = $this->httpClient->get($uri, [
              'headers' => [
                  'Authorization' => 'Bearer ' . $this->token,
                  'Content-Type' => 'application/json'
              ]
            ]);
        } catch (RequestException $ex) {
            throw ApiException::create(json_decode($ex->getResponse()->getBody()));
        }
        return $response->getBody();
    }
}
