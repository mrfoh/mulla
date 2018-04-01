<?php

namespace Mrfoh\Mulla\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Mrfoh\Mulla\Exceptions\InvalidResponseException;
use Mrfoh\Mulla\Exceptions\NullMethodException;

class Endpoint
{
  /**
  * Paystack secret key
  * @var string
  */
  protected $secretKey;

  /**
  *  Response from requests made to Paystack
  * @var mixed
  */
  protected $response;

  /**
  * Paystack base url
  * @var string
  */
  protected $baseUrl = "https://api.paystack.co";

  /**
  * Paystack request endpoint url
  * @var string
  */
  protected $endpointUrl;

  /**
  * Http Client
  * @var GuzzleHttp\Client
  */
  protected $httpClient;

  public function __construct()
  {
    $this->setKey();
    $this->getHttpClient();
  }

  /**
  * Set paystack secret key
  * @return void
  */
  private function setKey()
  {
    $this->secretKey = Config::get('mulla.secret_key');
  }

/**
 * @param string $url
 */
    public function setEndpointUrl(string $url)
  {
    $this->endpointUrl = $url;
  }

  private function getRequestOptions()
  {

    $authorizationHeaderString = 'Bearer '. $this->secretKey;

    return [
      'base_uri' => $this->baseUrl,
      'headers' => [
          'Authorization' => $authorizationHeaderString,
          'Content-Type' => 'application/json',
          'Acccept' => 'application/json'
      ]
    ];
  }

  /**
  * Create a new GuzzleHttp\Client
  * @access private
  * @return void
  */
  private function getHttpClient()
  {
    $this->httpClient = new Client($this->getRequestOptions());
  }


  /**
   * Make API request
   * @param $requestMethod
   * @param array $requestBody
   * @param string $requestPath
   * @param array $urlQueryParams
   * @throws NullMethodException
   * @throws GuzzleHttp\ClientException
   * @return $this
   */
  public function makeRequest($requestMethod, $requestBody = [], $requestPath = null, $urlQueryParams = [])
  {
    if(!is_null($requestPath)) {
      $requestUrl = $this->baseUrl . $this->endpointUrl . "/" . $requestPath;
    }
    else {
      $requestUrl = $this->baseUrl . $this->endpointUrl;
    }

    if(!empty($urlQueryParams)) {
      $requestUrl = $requestUrl . "?" . http_build_query($urlQueryParams);
    }

    if(is_null($requestMethod)) {
      throw new NullMethodException("No request method specified");
    }

    try {
      $this->response = $this->httpClient->{strtolower($requestMethod)}($requestUrl, ["body" => json_encode ($requestBody)]);
    } catch(ClientException $e) {
        $this->response = $e->getResponse();
    }

    return $this;
  }

    /**
     * @return $this
     */
    protected function getRequestResponse()
  {
    $this->response = json_decode($this->response->getBody(), true);

    return $this;
  }

    /**
     * Get data field of response body
     * @return array
     */
    protected function getResponseData() : array
    {
      return $this->response['data'];
    }

    protected function getResponsePayload()
    {
        return $this->response;
    }

    /**
     * @throws InvalidResponseException
     */
    protected function handleResponse()
    {
        if (!isset($this->response['status'])) {
            throw new InvalidResponseException("No status returned from payment gateway");
        } else {
            if ($this->response['status'] === true) {
                return $this->getResponseData();
            } else {
                throw new InvalidResponseException($this->response['message']);
            }
        }
    }
}
