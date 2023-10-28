<?php

declare(strict_types=1);

namespace ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Buy ServiceType
 * @subpackage Services
 */
class Buy extends AbstractSoapClientBase
{
    /**
     * Sets the AuthHeader SoapHeader param
     * @uses AbstractSoapClientBase::setSoapHeader()
     * @param \StructType\AuthHeader $authHeader
     * @param string $namespace
     * @param bool $mustUnderstand
     * @param string|null $actor
     * @return \ServiceType\Buy
     */
    public function setSoapHeaderAuthHeader(\StructType\AuthHeader $authHeader, string $namespace = 'http://tempuri.org/', bool $mustUnderstand = false, ?string $actor = null): self
    {
        return $this->setSoapHeader($namespace, 'AuthHeader', $authHeader, $mustUnderstand, $actor);
    }
    /**
     * Method to call the operation originally named Buy
     * Meta information extracted from the WSDL
     * - SOAPHeaderNames: AuthHeader
     * - SOAPHeaderNamespaces: http://tempuri.org/
     * - SOAPHeaderTypes: \StructType\AuthHeader
     * - SOAPHeaders: required
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\Buy $parameters
     * @return \StructType\BuyResponse|bool
     */
    public function Buy(\StructType\Buy $parameters)
    {
        try {
            $this->setResult($resultBuy = $this->getSoapClient()->__soapCall('Buy', [
                $parameters,
            ], [], [], $this->outputHeaders));
        
            return $resultBuy;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \StructType\BuyResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
