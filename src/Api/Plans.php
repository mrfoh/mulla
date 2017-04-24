<?php

namespace Mrfoh\Mulla\Api;

use Mrfoh\Mulla\Exceptions\InvalidRequestException;

class Plans extends Endpoint
{
    public function __construct()
    {
        parent::__construct();
        $this->setEndpointUrl('/plan');
    }

    /**
     * Validate plan creation data
     *
     * @param array $data
     * @return array
     */
    private function validatePlanCreateData(array $data)
    {
        $errors = [];
        if(!array_key_exists('name', $data)) {
            $errors['name'][] = 'name field is required';
        }

        if(!array_key_exists('amount', $data)) {
            $errors['amount'][] = 'amount field is required';
        }

        if(array_key_exists('amount', $data) && $data['amount'] < 0) {
            $errors['amount'][] = 'amount field invalid. amount must be >= 0';
        }

        if(!array_key_exists('interval', $data)) {
            $errors['interval'][] = "interval field is required";
        }

        if(array_key_exists('interval', $data) && !in_array($data['interval'], ['monthly', 'hourly', 'weekly', 'annually'])) {
            $errors['interval'][] = "interval field invalid. interval must be monthly, hourly, weekly. annually";
        }

        return $errors;
    }
    
     /**
     * Validate plan update data
     *
     * @param array $data
     * @return array
     */
    private function validatePlanUpdateData(array $data)
    {
        $errors = [];

        if(array_key_exists('amount', $data) && $data['amount'] < 0) {
            $errors['amount'][] = 'amount field invalid. amount must be >= 0';
        }

        if(array_key_exists('interval', $data) && !in_array($data['interval'], ['monthly', 'hourly', 'weekly', 'annually'])) {
            $errors['interval'][] = "interval field invalid. interval must be monthly, hourly, weekly. annually";
        }

        return $errors;
    }

    /**
     * Create a new paystack plan
     *
     * @param array $data
     * plan data; name, amount, inteval
     * @return array
     */
    public function create(array $data)
    {
        $validate = $this->validatePlanCreateData($data);

        if(count($validate) > 0) {
            throw new InvalidRequestException("Invalid request", $validate);
        }

        return $this->makeRequest('POST', $data, null)
            ->getRequestResponse()
            ->getResponseData();
    }

    /**
     * List paystack plans
     *
     * @param array $queryParams
     * request query parameters; perPage, page, interval, amount
     * @return array
     */
    public function list(array $queryParams = [])
    {
        return $this->makeRequest('GET', [], null)
            ->getRequestResponse()
            ->getResponsePayload();
    }

    /**
     * Fetch paystack plan
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
     * Update paystack plan
     *
     * @param string $id
     * @param array $data
     * @return array
     */
    public function update(string $id, array $data)
    {
        $validate = $this->validatePlanUpdateData($data);

        if(count($validate) > 0) {
            throw new InvalidRequestException("Invalid request", $validate);
        }

        return $this->makeRequest('PUT', $data, $id)
            ->getRequestResponse()
            ->getResponsePayload();
    }
}
