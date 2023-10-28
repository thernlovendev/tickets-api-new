<?php

declare(strict_types=1);

namespace ServiceType;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Select ServiceType
 * @subpackage Services
 */
class Select extends AbstractSoapClientBase
{
    /**
     * Sets the AuthHeader SoapHeader param
     * @uses AbstractSoapClientBase::setSoapHeader()
     * @param \StructType\AuthHeader $authHeader
     * @param string $namespace
     * @param bool $mustUnderstand
     * @param string|null $actor
     * @return \ServiceType\Select
     */
    public function setSoapHeaderAuthHeader(\StructType\AuthHeader $authHeader, string $namespace = 'http://tempuri.org/', bool $mustUnderstand = false, ?string $actor = null): self
    {
        return $this->setSoapHeader($namespace, 'AuthHeader', $authHeader, $mustUnderstand, $actor);
    }
    /**
     * Method to call the operation originally named Select
     * Meta information extracted from the WSDL
     * - SOAPHeaderNames: AuthHeader
     * - SOAPHeaderNamespaces: http://tempuri.org/
     * - SOAPHeaderTypes: \StructType\AuthHeader
     * - SOAPHeaders: required
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\Select $parameters
     * @return \StructType\SelectResponse|bool
     */
    public function Select(\StructType\Select $parameters)
    {
        try {
            $this->setResult($resultSelect = $this->getSoapClient()->__soapCall('Select', [
                $parameters,
            ], [], [], $this->outputHeaders));
        
            return $resultSelect;
        } catch (SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
        
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \StructType\SelectResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
