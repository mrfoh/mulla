<?php

/*
 * This file is part of the Mulla package.
 *
 * (c) Patrick Foh <patrickfoh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mrfoh\Mulla\Api;

use Mrfoh\Mulla\Exceptions\EmptyDataException;
use Mrfoh\Mulla\Exceptions\InvalidFieldException;
use Mrfoh\Mulla\Exceptions\InvalidRiskActionException;

class Customers extends Endpoint
{

  public function __construct()
  {
    parent::__construct();
    $this->setEndpointUrl('/customer');
  }

    /**
     * @param array $requestBody
     * @throws EmptyDataException
     * @throws InvalidFieldException
     */
    private function validateCustomerCreateRequestBody(array $requestBody)
  {
    if(empty($requestBody)) throw new EmptyDataException("No customer data provided");

    if(!array_key_exists('email', $requestBody)) throw new InvalidFieldException("Email field is required");
  }


    /**
     * @param array $requestBody
     * @throws EmptyDataException
     */
    private function validateCustomerUpdateRequestBody(array $requestBody)
  {
    if(empty($requestBody)) throw new EmptyDataException("No customer data provided");
  }


    /**
     * @param int $perPage
     * @param int $page
     * @return mixed
     */
    public function list(int $perPage = 20, int $page = 1)
  {
    return $this->makeRequest('GET', [], null, ["perPage" => $perPage, "page" => $page])
        ->getRequestResponse()
        ->getResponsePayload();
  }


    /**
     * @param string $idCustomerCode
     * @return mixed
     */
    public function fetch(string $idCustomerCode)
  {
    return $this->makeRequest('GET', [], $idCustomerCode)->getRequestResponse()->getResponseData();
  }

    /**
     * @param array $customerData
     * @return mixed
     */
    public function create(array $customerData)
  {
    $this->validateCustomerCreateRequestBody($customerData);

    return $this->makeRequest('POST', $customerData)->getRequestResponse()->getResponseData();
  }


    /**
     * @param string $idCustomerCode
     * @param array $customerData
     * @return mixed
     */
    public function update(string $idCustomerCode, array $customerData)
  {
    $this->validateCustomerUpdateRequestBody($customerData);

    return $this->makeRequest('PUT', $customerData, $idCustomerCode)->getRequestResponse()->getResponseData();
  }

    /**
     * @param string $idCustomerCode
     * @param string $action
     * @return mixed
     * @throws InvalidRiskActionException
     */
    public function setRiskAction(string $idCustomerCode, string $action)
  {
    if(!in_array($action, ['allow', 'deny'])) throw new InvalidRiskActionException("Invalid risk action, allowed actions; allow, deny");

    $data = ['customer' => $idCustomerCode, 'risk_action' => $action];

    return $this->makeRequest('POST', $data , "set_risk_action")->getRequestResponse()->getResponseData();
  }


    /**
     * @param string $authorizationCode
     * @return mixed
     */
    public function deactivateAuthorization(string $authorizationCode)
  {
    return $this->makeRequest('POST', ['authorization_code' => $authorizationCode], "deactivate_authorization")
        ->getRequestResponse()
        ->getResponsePayload();
  }
}
