<?php

declare(strict_types=1);

namespace ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Performances ServiceType
 * @subpackage Services
 */
class Performances extends AbstractSoapClientBase
{
    /**
     * Sets the AuthHeader SoapHeader param
     * @uses AbstractSoapClientBase::setSoapHeader()
     * @param \StructType\AuthHeader $authHeader
     * @param string $namespace
     * @param bool $mustUnderstand
     * @param string|null $actor
     * @return \ServiceType\Performances
     */
    public function setSoapHeaderAuthHeader(\StructType\AuthHeader $authHeader, string $namespace = 'http://tempuri.org/', bool $mustUnderstand = false, ?string $actor = null): self
    {
        return $this->setSoapHeader($namespace, 'AuthHeader', $authHeader, $mustUnderstand, $actor);
    }
    /**
     * Method to call the operation originally named Performances
     * Meta information extracted from the WSDL
     * - SOAPHeaderNames: AuthHeader
     * - SOAPHeaderNamespaces: http://tempuri.org/
     * - SOAPHeaderTypes: \StructType\AuthHeader
     * - SOAPHeaders: required
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\Performances $parameters
     * @return \StructType\PerformancesResponse|bool
     */
    public function Performances(\StructType\Performances $parameters)
    {
        try {
            $this->setResult($resultPerformances = $this->getSoapClient()->__soapCall('Performances', [
                $parameters,
            ], [], [], $this->outputHeaders));
        
            return $resultPerformances;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named PerformancesPOHPricesAvailability
     * Meta information extracted from the WSDL
     * - SOAPHeaderNames: AuthHeader
     * - SOAPHeaderNamespaces: http://tempuri.org/
     * - SOAPHeaderTypes: \StructType\AuthHeader
     * - SOAPHeaders: required
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\PerformancesPOHPricesAvailability $parameters
     * @return \StructType\PerformancesPOHPricesAvailabilityResponse|bool
     */
    public function PerformancesPOHPricesAvailability(\StructType\PerformancesPOHPricesAvailability $parameters)
    {
        try {
            $this->setResult($resultPerformancesPOHPricesAvailability = $this->getSoapClient()->__soapCall('PerformancesPOHPricesAvailability', [
                $parameters,
            ], [], [], $this->outputHeaders));
        
            return $resultPerformancesPOHPricesAvailability;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named
     * PerformancesPOHPricesAvailabilityMC
     * Meta information extracted from the WSDL
     * - SOAPHeaderNames: AuthHeader
     * - SOAPHeaderNamespaces: http://tempuri.org/
     * - SOAPHeaderTypes: \StructType\AuthHeader
     * - SOAPHeaders: required
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\PerformancesPOHPricesAvailabilityMC $parameters
     * @return \StructType\PerformancesPOHPricesAvailabilityMCResponse|bool
     */
    public function PerformancesPOHPricesAvailabilityMC(\StructType\PerformancesPOHPricesAvailabilityMC $parameters)
    {
        try {
            $this->setResult($resultPerformancesPOHPricesAvailabilityMC = $this->getSoapClient()->__soapCall('PerformancesPOHPricesAvailabilityMC', [
                $parameters,
            ], [], [], $this->outputHeaders));
        
            return $resultPerformancesPOHPricesAvailabilityMC;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \StructType\PerformancesPOHPricesAvailabilityMCResponse|\StructType\PerformancesPOHPricesAvailabilityResponse|\StructType\PerformancesResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
