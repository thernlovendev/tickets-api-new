<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Performances StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class Performances extends AbstractStructBase
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
     * The DateEnds
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $DateEnds;
    /**
     * The ShowCodes
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $ShowCodes;
    /**
     * The AvailabilityType
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $AvailabilityType;
    /**
     * The BestSeatsOnly
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BestSeatsOnly;
    /**
     * The LastChangeDate
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $LastChangeDate;
    /**
     * The DateBegins
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * - nillable: true
     * @var string
     */
    protected ?string $DateBegins;
    /**
     * Constructor method for Performances
     * @uses Performances::setSaleTypesCode()
     * @uses Performances::setShowCityCode()
     * @uses Performances::setDateEnds()
     * @uses Performances::setShowCodes()
     * @uses Performances::setAvailabilityType()
     * @uses Performances::setBestSeatsOnly()
     * @uses Performances::setLastChangeDate()
     * @uses Performances::setDateBegins()
     * @param string $saleTypesCode
     * @param string $showCityCode
     * @param string $dateEnds
     * @param string $showCodes
     * @param string $availabilityType
     * @param string $bestSeatsOnly
     * @param string $lastChangeDate
     * @param string $dateBegins
     */
    public function __construct(string $saleTypesCode, string $showCityCode, string $dateEnds, string $showCodes, string $availabilityType, string $bestSeatsOnly, string $lastChangeDate, ?string $dateBegins)
    {
        $this
            ->setSaleTypesCode($saleTypesCode)
            ->setShowCityCode($showCityCode)
            ->setDateEnds($dateEnds)
            ->setShowCodes($showCodes)
            ->setAvailabilityType($availabilityType)
            ->setBestSeatsOnly($bestSeatsOnly)
            ->setLastChangeDate($lastChangeDate)
            ->setDateBegins($dateBegins);
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
     * @return \StructType\Performances
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
     * @return \StructType\Performances
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
     * Get DateEnds value
     * @return string
     */
    public function getDateEnds(): string
    {
        return $this->DateEnds;
    }
    /**
     * Set DateEnds value
     * @param string $dateEnds
     * @return \StructType\Performances
     */
    public function setDateEnds(string $dateEnds): self
    {
        // validation for constraint: string
        if (!is_null($dateEnds) && !is_string($dateEnds)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($dateEnds, true), gettype($dateEnds)), __LINE__);
        }
        $this->DateEnds = $dateEnds;
        
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
     * @return \StructType\Performances
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
     * Get AvailabilityType value
     * @return string
     */
    public function getAvailabilityType(): string
    {
        return $this->AvailabilityType;
    }
    /**
     * Set AvailabilityType value
     * @param string $availabilityType
     * @return \StructType\Performances
     */
    public function setAvailabilityType(string $availabilityType): self
    {
        // validation for constraint: string
        if (!is_null($availabilityType) && !is_string($availabilityType)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($availabilityType, true), gettype($availabilityType)), __LINE__);
        }
        $this->AvailabilityType = $availabilityType;
        
        return $this;
    }
    /**
     * Get BestSeatsOnly value
     * @return string
     */
    public function getBestSeatsOnly(): string
    {
        return $this->BestSeatsOnly;
    }
    /**
     * Set BestSeatsOnly value
     * @param string $bestSeatsOnly
     * @return \StructType\Performances
     */
    public function setBestSeatsOnly(string $bestSeatsOnly): self
    {
        // validation for constraint: string
        if (!is_null($bestSeatsOnly) && !is_string($bestSeatsOnly)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bestSeatsOnly, true), gettype($bestSeatsOnly)), __LINE__);
        }
        $this->BestSeatsOnly = $bestSeatsOnly;
        
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
     * @return \StructType\Performances
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
     * Get DateBegins value
     * @return string
     */
    public function getDateBegins(): string
    {
        return $this->DateBegins;
    }
    /**
     * Set DateBegins value
     * @param string $dateBegins
     * @return \StructType\Performances
     */
    public function setDateBegins(?string $dateBegins): self
    {
        // validation for constraint: string
        if (!is_null($dateBegins) && !is_string($dateBegins)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($dateBegins, true), gettype($dateBegins)), __LINE__);
        }
        $this->DateBegins = $dateBegins;
        
        return $this;
    }
}
