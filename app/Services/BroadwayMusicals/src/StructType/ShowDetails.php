<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ShowDetails StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class ShowDetails extends AbstractStructBase
{
    /**
     * The SaleTypesCode
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $SaleTypesCode;
    /**
     * The LastChangeDate
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $LastChangeDate;
    /**
     * The ShowCodes
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $ShowCodes;
    /**
     * The ShowCityCode
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $ShowCityCode;
    /**
     * Constructor method for ShowDetails
     * @uses ShowDetails::setSaleTypesCode()
     * @uses ShowDetails::setLastChangeDate()
     * @uses ShowDetails::setShowCodes()
     * @uses ShowDetails::setShowCityCode()
     * @param string $saleTypesCode
     * @param string $lastChangeDate
     * @param string $showCodes
     * @param string $showCityCode
     */
    public function __construct(string $saleTypesCode, string $lastChangeDate, string $showCodes, string $showCityCode)
    {
        $this
            ->setSaleTypesCode($saleTypesCode)
            ->setLastChangeDate($lastChangeDate)
            ->setShowCodes($showCodes)
            ->setShowCityCode($showCityCode);
    }
    /**
     * Get SaleTypesCode value
     * @return string
     */
    public function getSaleTypesCode(): string
    {
        return $this->SaleTypesCode;
    }
    /**
     * Set SaleTypesCode value
     * @param string $saleTypesCode
     * @return \StructType\ShowDetails
     */
    public function setSaleTypesCode(string $saleTypesCode): self
    {
        // validation for constraint: string
        if (!is_null($saleTypesCode) && !is_string($saleTypesCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($saleTypesCode, true), gettype($saleTypesCode)), __LINE__);
        }
        $this->SaleTypesCode = $saleTypesCode;
        
        return $this;
    }
    /**
     * Get LastChangeDate value
     * @return string
     */
    public function getLastChangeDate(): string
    {
        return $this->LastChangeDate;
    }
    /**
     * Set LastChangeDate value
     * @param string $lastChangeDate
     * @return \StructType\ShowDetails
     */
    public function setLastChangeDate(string $lastChangeDate): self
    {
        // validation for constraint: string
        if (!is_null($lastChangeDate) && !is_string($lastChangeDate)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lastChangeDate, true), gettype($lastChangeDate)), __LINE__);
        }
        $this->LastChangeDate = $lastChangeDate;
        
        return $this;
    }
    /**
     * Get ShowCodes value
     * @return string
     */
    public function getShowCodes(): string
    {
        return $this->ShowCodes;
    }
    /**
     * Set ShowCodes value
     * @param string $showCodes
     * @return \StructType\ShowDetails
     */
    public function setShowCodes(string $showCodes): self
    {
        // validation for constraint: string
        if (!is_null($showCodes) && !is_string($showCodes)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($showCodes, true), gettype($showCodes)), __LINE__);
        }
        $this->ShowCodes = $showCodes;
        
        return $this;
    }
    /**
     * Get ShowCityCode value
     * @return string
     */
    public function getShowCityCode(): string
    {
        return $this->ShowCityCode;
    }
    /**
     * Set ShowCityCode value
     * @param string $showCityCode
     * @return \StructType\ShowDetails
     */
    public function setShowCityCode(string $showCityCode): self
    {
        // validation for constraint: string
        if (!is_null($showCityCode) && !is_string($showCityCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($showCityCode, true), gettype($showCityCode)), __LINE__);
        }
        $this->ShowCityCode = $showCityCode;
        
        return $this;
    }
}
