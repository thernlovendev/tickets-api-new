<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PhoneInfo StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class PhoneInfo extends AbstractStructBase
{
    /**
     * The CountryCode
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $CountryCode = null;
    /**
     * The AreaCode
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $AreaCode = null;
    /**
     * The Number
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $Number = null;
    /**
     * The Ext
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $Ext = null;
    /**
     * Constructor method for PhoneInfo
     * @uses PhoneInfo::setCountryCode()
     * @uses PhoneInfo::setAreaCode()
     * @uses PhoneInfo::setNumber()
     * @uses PhoneInfo::setExt()
     * @param string $countryCode
     * @param string $areaCode
     * @param string $number
     * @param string $ext
     */
    public function __construct(?string $countryCode = null, ?string $areaCode = null, ?string $number = null, ?string $ext = null)
    {
        $this
            ->setCountryCode($countryCode)
            ->setAreaCode($areaCode)
            ->setNumber($number)
            ->setExt($ext);
    }
    /**
     * Get CountryCode value
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->CountryCode;
    }
    /**
     * Set CountryCode value
     * @param string $countryCode
     * @return \StructType\PhoneInfo
     */
    public function setCountryCode(?string $countryCode = null): self
    {
        // validation for constraint: string
        if (!is_null($countryCode) && !is_string($countryCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($countryCode, true), gettype($countryCode)), __LINE__);
        }
        $this->CountryCode = $countryCode;
        
        return $this;
    }
    /**
     * Get AreaCode value
     * @return string|null
     */
    public function getAreaCode(): ?string
    {
        return $this->AreaCode;
    }
    /**
     * Set AreaCode value
     * @param string $areaCode
     * @return \StructType\PhoneInfo
     */
    public function setAreaCode(?string $areaCode = null): self
    {
        // validation for constraint: string
        if (!is_null($areaCode) && !is_string($areaCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($areaCode, true), gettype($areaCode)), __LINE__);
        }
        $this->AreaCode = $areaCode;
        
        return $this;
    }
    /**
     * Get Number value
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->Number;
    }
    /**
     * Set Number value
     * @param string $number
     * @return \StructType\PhoneInfo
     */
    public function setNumber(?string $number = null): self
    {
        // validation for constraint: string
        if (!is_null($number) && !is_string($number)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($number, true), gettype($number)), __LINE__);
        }
        $this->Number = $number;
        
        return $this;
    }
    /**
     * Get Ext value
     * @return string|null
     */
    public function getExt(): ?string
    {
        return $this->Ext;
    }
    /**
     * Set Ext value
     * @param string $ext
     * @return \StructType\PhoneInfo
     */
    public function setExt(?string $ext = null): self
    {
        // validation for constraint: string
        if (!is_null($ext) && !is_string($ext)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($ext, true), gettype($ext)), __LINE__);
        }
        $this->Ext = $ext;
        
        return $this;
    }
}
