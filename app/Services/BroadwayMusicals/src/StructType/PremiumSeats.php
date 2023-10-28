<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PremiumSeats StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class PremiumSeats extends AbstractStructBase
{
    /**
     * The DateEnds
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $DateEnds;
    /**
     * The OneShowCode
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $OneShowCode;
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
     * Constructor method for PremiumSeats
     * @uses PremiumSeats::setDateEnds()
     * @uses PremiumSeats::setOneShowCode()
     * @uses PremiumSeats::setDateBegins()
     * @param string $dateEnds
     * @param string $oneShowCode
     * @param string $dateBegins
     */
    public function __construct(string $dateEnds, string $oneShowCode, ?string $dateBegins)
    {
        $this
            ->setDateEnds($dateEnds)
            ->setOneShowCode($oneShowCode)
            ->setDateBegins($dateBegins);
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
     * @return \StructType\PremiumSeats
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
     * Get OneShowCode value
     * @return string
     */
    public function getOneShowCode(): string
    {
        return $this->OneShowCode;
    }
    /**
     * Set OneShowCode value
     * @param string $oneShowCode
     * @return \StructType\PremiumSeats
     */
    public function setOneShowCode(string $oneShowCode): self
    {
        // validation for constraint: string
        if (!is_null($oneShowCode) && !is_string($oneShowCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($oneShowCode, true), gettype($oneShowCode)), __LINE__);
        }
        $this->OneShowCode = $oneShowCode;
        
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
     * @return \StructType\PremiumSeats
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
