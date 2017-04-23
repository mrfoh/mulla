<?php

namespace Mrfoh\Mulla\Api;

use Mrfoh\Mulla\Exceptions\InvalidRequestException;

class Transaction extends Endpoint
{

    /**
     * Transaction constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setEndpointUrl('/transaction');
    }

    /**
     * Validate transaction data
     * @param array $data
     * @return array
     */
    private function validateTransactionData(array $data) {
        $errors = [];

        if(!array_key_exists('email', $data)) {
            $errors['email'][] = "email field is required";
        }

        if(!array_key_exists('amount', $data)) {
            $errors['amount'][] = "amount field is required";
        }

        if(array_key_exists('amount', $data) && $data['amount'] < 0) {
            $errors['amount'][] = "Invalid amount, amount must be > 0";
        }

        if(array_key_exists('transaction_charge', $data) && $data['transaction_charge'] < 0) {
            $errors['transaction_charge'][] = "Invalid amount, amount must be > 0";
        }

        if(array_key_exists('bearer', $data) && !in_array($data['bearer'], ['account', 'subaccount'])) {
            $errors['bearer'][] = "Invalid bearer, bearer must be; account or subaccount";
        }

        return $errors;
    }

    private function validateAuthorizationData(array $data) {
        $errors = [];

        if(!array_key_exists('authorization_code', $data)) {
            $errors['email'][] = "authorization_code is required";
        }

        if(!array_key_exists('email', $data)) {
            $errors['email'][] = "email field is required";
        }

        if(!array_key_exists('amount', $data)) {
            $errors['amount'][] = "amount field is required";
        }

        if(array_key_exists('amount', $data) && $data['amount'] < 0) {
            $errors['amount'][] = "Invalid amount, amount must be > 0";
        }

        if(array_key_exists('transaction_charge', $data) && $data['transaction_charge'] < 0) {
            $errors['transaction_charge'][] = "Invalid amount, amount must be > 0";
        }

        if(array_key_exists('bearer', $data) && !in_array($data['bearer'], ['account', 'subaccount'])) {
            $errors['bearer'][] = "Invalid bearer, bearer must be; account or subaccount";
        }


        return $errors;
    }

    /**
     * Initialize a transaction
     * @param array $transactionData
     * @return array
     * @throws InvalidRequestException
     */
    public function initialize(array $transactionData) : array
    {

        $validationErrors = $this->validateTransactionData($transactionData);

        if(count($validationErrors) > 0) {
            throw new InvalidRequestException("Invalid request", $validationErrors);
        }

        return $this->makeRequest('POST', $transactionData, "initialize")
            ->getRequestResponse()
            ->getResponseData();
    }

    /**
     * Verify a transaction
     * @param string $reference
     * transaction reference
     * @return array
     */
    public function verify(string $reference)
    {

        return $this->makeRequest('GET', [], "verify/" . $reference)
            ->getRequestResponse()
            ->getResponseData();
    }

    /**
     * @param array $queryParams
     * @return array
     */
    public function list(array $queryParams)
    {

        return $this->makeRequest('GET', [], null, $queryParams)
            ->getRequestResponse()
            ->getResponseData();
    }

    /**
     * @param int $id
     * @return array
     */
    public function fetch(int $id)
    {
        return $this->makeRequest('GET', [], $id)->getRequestResponse()->getResponseData();
    }

    /**
     * @param array $data
     * @return array
     * @throws InvalidRequestException
     */
    public function chargeAuthorization(array $data)
    {
        $validationErrors = $this->validateAuthorizationData($data);

        if(count($validationErrors) > 0) {
            throw new InvalidRequestException("Invalid request", $validationErrors);
        }

        return $this->makeRequest('POST', $data, "charge_authorization")->getRequestResponse()->getResponseData();
    }

    public function requestReauthorization(array $data)
    {
        $validationErrors = $this->validateAuthorizationData($data);

        if(count($validationErrors) > 0) {
            throw new InvalidRequestException("Invalid request", $validationErrors);
        }

        return $this->makeRequest('POST', $data, "request_reauthorization")->getRequestResponse()->getResponseData();
    }
}