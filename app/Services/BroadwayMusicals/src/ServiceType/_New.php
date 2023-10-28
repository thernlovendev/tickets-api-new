<?php

declare(strict_types=1);

namespace ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for New ServiceType
 * @subpackage Services
 */
class _New extends AbstractSoapClientBase
{
    /**
     * Sets the AuthHeader SoapHeader param
     * @uses AbstractSoapClientBase::setSoapHeader()
     * @param \StructType\AuthHeader $authHeader
     * @param string $namespace
     * @param bool $mustUnderstand
     * @param string|null $actor
     * @return \ServiceType\_New
     */
    public function setSoapHeaderAuthHeader(\StructType\AuthHeader $authHeader, string $namespace = 'http://tempuri.org/', bool $mustUnderstand = false, ?string $actor = null): self
    {
        return $this->setSoapHeader($namespace, 'AuthHeader', $authHeader, $mustUnderstand, $actor);
    }
    /**
     * Method to call the operation originally named NewOrder
     * Meta information extracted from the WSDL
     * - SOAPHeaderNames: AuthHeader
     * - SOAPHeaderNamespaces: http://tempuri.org/
     * - SOAPHeaderTypes: \StructType\AuthHeader
     * - SOAPHeaders: required
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\NewOrder $parameters
     * @return \StructType\NewOrderResponse|bool
     */
    public function NewOrder(\StructType\NewOrder $parameters)
    {
        try {
            $this->setResult($resultNewOrder = $this->getSoapClient()->__soapCall('NewOrder', [
                $parameters,
            ], [], [], $this->outputHeaders));
        
            return $resultNewOrder;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \StructType\NewOrderResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
