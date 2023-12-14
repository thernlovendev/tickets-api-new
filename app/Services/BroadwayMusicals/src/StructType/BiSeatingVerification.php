<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for BiSeatingVerification StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class BiSeatingVerification extends AbstractStructBase
{
    /**
     * The NumberTickets
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    public ?string $NumberTickets = null;
    /**
     * The Area
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    public ?string $Area = null;
    /**
     * The LowSeatNum
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    public ?string $LowSeatNum = null;
    /**
     * The HighSeatNum
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    public ?string $HighSeatNum = null;
    /**
     * The Row
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    public ?string $Row = null;
    /**
     * The Section
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    public ?string $Section = null;
    /**
     * Constructor method for BiSeatingVerification
     * @uses BiSeatingVerification::setNumberTickets()
     * @uses BiSeatingVerification::setArea()
     * @uses BiSeatingVerification::setLowSeatNum()
     * @uses BiSeatingVerification::setHighSeatNum()
     * @uses BiSeatingVerification::setRow()
     * @uses BiSeatingVerification::setSection()
     * @param string $numberTickets
     * @param string $area
     * @param string $lowSeatNum
     * @param string $highSeatNum
     * @param string $row
     * @param string $section
     */
    public function __construct(?string $numberTickets = null, ?string $area = null, ?string $lowSeatNum = null, ?string $highSeatNum = null, ?string $row = null, ?string $section = null)
    {
        $this
            ->setNumberTickets($numberTickets)
            ->setArea($area)
            ->setLowSeatNum($lowSeatNum)
            ->setHighSeatNum($highSeatNum)
            ->setRow($row)
            ->setSection($section);
    }
    /**
     * Get NumberTickets value
     * @return string|null
     */
    public function getNumberTickets(): ?string
    {
        return $this->NumberTickets;
    }
    /**
     * Set NumberTickets value
     * @param string $numberTickets
     * @return \StructType\BiSeatingVerification
     */
    public function setNumberTickets(?string $numberTickets = null): self
    {
        // validation for constraint: string
        if (!is_null($numberTickets) && !is_string($numberTickets)) {
            $message = sprintf('Invalid value %s, please provide a string, %s given', var_export($numberTickets, true), gettype($numberTickets));
            throw new \Exception($message);
            // throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($numberTickets, true), gettype($numberTickets)), __LINE__);
        }
        $this->NumberTickets = $numberTickets;
        
        return $this;
    }
    /**
     * Get Area value
     * @return string|null
     */
    public function getArea(): ?string
    {
        return $this->Area;
    }
    /**
     * Set Area value
     * @param string $area
     * @return \StructType\BiSeatingVerification
     */
    public function setArea(?string $area = null): self
    {
        // validation for constraint: string
        if (!is_null($area) && !is_string($area)) {
            $message = sprintf('Invalid value %s, please provide a string, %s given', var_export($area, true), gettype($area));
            throw new \Exception($message);
            // throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($area, true), gettype($area)), __LINE__);
        }
        $this->Area = $area;
        
        return $this;
    }
    /**
     * Get LowSeatNum value
     * @return string|null
     */
    public function getLowSeatNum(): ?string
    {
        return $this->LowSeatNum;
    }
    /**
     * Set LowSeatNum value
     * @param string $lowSeatNum
     * @return \StructType\BiSeatingVerification
     */
    public function setLowSeatNum(?string $lowSeatNum = null): self
    {
        // validation for constraint: string
        if (!is_null($lowSeatNum) && !is_string($lowSeatNum)) {
            $message = sprintf('Invalid value %s, please provide a string, %s given', var_export($lowSeatNum, true), gettype($lowSeatNum));
            throw new \Exception($message);
            // throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lowSeatNum, true), gettype($lowSeatNum)), __LINE__);
        }
        $this->LowSeatNum = $lowSeatNum;
        
        return $this;
    }
    /**
     * Get HighSeatNum value
     * @return string|null
     */
    public function getHighSeatNum(): ?string
    {
        return $this->HighSeatNum;
    }
    /**
     * Set HighSeatNum value
     * @param string $highSeatNum
     * @return \StructType\BiSeatingVerification
     */
    public function setHighSeatNum(?string $highSeatNum = null): self
    {
        // validation for constraint: string
        if (!is_null($highSeatNum) && !is_string($highSeatNum)) {
            $message = sprintf('Invalid value %s, please provide a string, %s given', var_export($highSeatNum, true), gettype($highSeatNum));
            throw new \Exception($message);
            // throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($highSeatNum, true), gettype($highSeatNum)), __LINE__);
        }
        $this->HighSeatNum = $highSeatNum;
        
        return $this;
    }
    /**
     * Get Row value
     * @return string|null
     */
    public function getRow(): ?string
    {
        return $this->Row;
    }
    /**
     * Set Row value
     * @param string $row
     * @return \StructType\BiSeatingVerification
     */
    public function setRow(?string $row = null): self
    {
        // validation for constraint: string
        if (!is_null($row) && !is_string($row)) {
            $message = sprintf('Invalid value %s, please provide a string, %s given', var_export($row, true), gettype($row));
            throw new \Exception($message);
            // throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($row, true), gettype($row)), __LINE__);
        }
        $this->Row = $row;
        
        return $this;
    }
    /**
     * Get Section value
     * @return string|null
     */
    public function getSection(): ?string
    {
        return $this->Section;
    }
    /**
     * Set Section value
     * @param string $section
     * @return \StructType\BiSeatingVerification
     */
    public function setSection(?string $section = null): self
    {
        // validation for constraint: string
        if (!is_null($section) && !is_string($section)) {
            $message = sprintf('Invalid value %s, please provide a string, %s given', var_export($section, true), gettype($section));
            throw new \Exception($message);
            // throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($section, true), gettype($section)), __LINE__);
        }
        $this->Section = $section;
        
        return $this;
    }
}
