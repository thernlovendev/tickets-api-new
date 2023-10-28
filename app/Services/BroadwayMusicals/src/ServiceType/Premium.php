<?php

declare(strict_types=1);

namespace ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Premium ServiceType
 * @subpackage Services
 */
class Premium extends AbstractSoapClientBase
{
    /**
     * Sets the AuthHeader SoapHeader param
     * @uses AbstractSoapClientBase::setSoapHeader()
     * @param \StructType\AuthHeader $authHeader
     * @param string $namespace
     * @param bool $mustUnderstand
     * @param string|null $actor
     * @return \ServiceType\Premium
     */
    public function setSoapHeaderAuthHeader(\StructType\AuthHeader $authHeader, string $namespace = 'http://tempuri.org/', bool $mustUnderstand = false, ?string $actor = null): self
    {
        return $this->setSoapHeader($namespace, 'AuthHeader', $authHeader, $mustUnderstand, $actor);
    }
    /**
     * Method to call the operation originally named PremiumSeats
     * Meta information extracted from the WSDL
     * - SOAPHeaderNames: AuthHeader
     * - SOAPHeaderNamespaces: http://tempuri.org/
     * - SOAPHeaderTypes: \StructType\AuthHeader
     * - SOAPHeaders: required
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\PremiumSeats $parameters
     * @return \StructType\PremiumSeatsResponse|bool
     */
    public function PremiumSeats(\StructType\PremiumSeats $parameters)
    {
        try {
            $this->setResult($resultPremiumSeats = $this->getSoapClient()->__soapCall('PremiumSeats', [
                $parameters,
            ], [], [], $this->outputHeaders));
        
            return $resultPremiumSeats;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \StructType\PremiumSeatsResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
