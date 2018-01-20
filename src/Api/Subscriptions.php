<?php

namespace Mrfoh\Mulla\Api;

use Mrfoh\Mulla\Exceptions\InvalidRequestException;

class Subscriptions extends Endpoint
{
    public function __construct()
    {
        parent::__construct();
        $this->setEndpointUrl('/subscription');
    }

    private function validateSubscriptionCreateData(array $data) {
        $errors = [];

        if(!array_key_exists('customer', $data)) {
            $errors['customer'][] = "customer field is required";
        }

        if(!array_key_exists('plan', $data)) {
            $errors['plan'][] = "plan field is required";
        }

        return $errors;
    }

    /**
     * Create paystack subscription
     *
     * @param array $data
     * Request data; customer, plan, authorization
     * @return array
     */
    public function create(array $data)
    {
        $validate = $this->validateSubscriptionCreateData($data);

        if(count($validate) > 0) {
            throw new InvalidRequestException("Invalid request", $validate);
        }

        return $this->makeRequest('POST', $data, null)
            ->getRequestResponse()
            ->getResponseData();
    }

    /**
     * List paystack subscriptions
     *
     * @param array $queryParams
     * @return array
     */
    public function list(array $queryParams)
    {
         return $this->makeRequest('GET', [], null, $queryParams)
            ->getRequestResponse()
            ->getResponsePayload();
    }

    /**
     * Fetch paystack subcription
     *
     * @param string $id
     * @return array
     */
    public function fetch(string $id)
    {
        return $this->makeRequest('GET', [], $id)
            ->getRequestResponse()
            ->getResponseData();
    }

    /**
     * Disable paystack subscription
     *
     * @param string $code
     * @param string $token
     * @return array
     */
    public function disable(string $code, string $token)
    {
        $data = ['code' => $code, 'token' => $token];

        return $this->makeRequest('POST', $data, "disable")
            ->getRequestResponse()
            ->getResponsePayload();
    }

    /**
     * Enable paystack subscription
     *
     * @param string $code
     * @param string $token
     * @return array
     */
    public function enable(string $code, string $token)
    {
        $data = ['code' => $code, 'token' => $token];
        
        return $this->makeRequest('POST', $data, "enable")
            ->getRequestResponse()
            ->getResponsePayload();
    }
}
