<?php

declare(strict_types=1);

namespace ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Show ServiceType
 * @subpackage Services
 */
class Show extends AbstractSoapClientBase
{
    /**
     * Sets the AuthHeader SoapHeader param
     * @uses AbstractSoapClientBase::setSoapHeader()
     * @param \StructType\AuthHeader $authHeader
     * @param string $namespace
     * @param bool $mustUnderstand
     * @param string|null $actor
     * @return \ServiceType\Show
     */
    public function setSoapHeaderAuthHeader(\StructType\AuthHeader $authHeader, string $namespace = 'http://tempuri.org/', bool $mustUnderstand = false, ?string $actor = null): self
    {
        return $this->setSoapHeader($namespace, 'AuthHeader', $authHeader, $mustUnderstand, $actor);
    }
    /**
     * Method to call the operation originally named ShowBasics
     * Meta information extracted from the WSDL
     * - SOAPHeaderNames: AuthHeader
     * - SOAPHeaderNamespaces: http://tempuri.org/
     * - SOAPHeaderTypes: \StructType\AuthHeader
     * - SOAPHeaders: required
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\ShowBasics $parameters
     * @return \StructType\ShowBasicsResponse|bool
     */
    public function ShowBasics(\StructType\ShowBasics $parameters)
    {
        try {
            $this->setResult($resultShowBasics = $this->getSoapClient()->__soapCall('ShowBasics', [
                $parameters,
            ], [], [], $this->outputHeaders));
        
            return $resultShowBasics;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Method to call the operation originally named ShowDetails
     * Meta information extracted from the WSDL
     * - SOAPHeaderNames: AuthHeader
     * - SOAPHeaderNamespaces: http://tempuri.org/
     * - SOAPHeaderTypes: \StructType\AuthHeader
     * - SOAPHeaders: required
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\ShowDetails $parameters
     * @return \StructType\ShowDetailsResponse|bool
     */
    public function ShowDetails(\StructType\ShowDetails $parameters)
    {
        try {
            $this->setResult($resultShowDetails = $this->getSoapClient()->__soapCall('ShowDetails', [
                $parameters,
            ], [], [], $this->outputHeaders));
        
            return $resultShowDetails;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \StructType\ShowBasicsResponse|\StructType\ShowDetailsResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
