<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ShowBasics StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class ShowBasics extends AbstractStructBase
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
     * The ShowCityCode
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $ShowCityCode;
    /**
     * The ShowAddedDate
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $ShowAddedDate;
    /**
     * Constructor method for ShowBasics
     * @uses ShowBasics::setSaleTypesCode()
     * @uses ShowBasics::setShowCityCode()
     * @uses ShowBasics::setShowAddedDate()
     * @param string $saleTypesCode
     * @param string $showCityCode
     * @param string $showAddedDate
     */
    public function __construct(string $saleTypesCode, string $showCityCode, string $showAddedDate)
    {
        $this
            ->setSaleTypesCode($saleTypesCode)
            ->setShowCityCode($showCityCode)
            ->setShowAddedDate($showAddedDate);
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
     * @return \StructType\ShowBasics
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
     * @return \StructType\ShowBasics
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
    /**
     * Get ShowAddedDate value
     * @return string
     */
    public function getShowAddedDate(): string
    {
        return $this->ShowAddedDate;
    }
    /**
     * Set ShowAddedDate value
     * @param string $showAddedDate
     * @return \StructType\ShowBasics
     */
    public function setShowAddedDate(string $showAddedDate): self
    {
        // validation for constraint: string
        if (!is_null($showAddedDate) && !is_string($showAddedDate)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($showAddedDate, true), gettype($showAddedDate)), __LINE__);
        }
        $this->ShowAddedDate = $showAddedDate;
        
        return $this;
    }
}
