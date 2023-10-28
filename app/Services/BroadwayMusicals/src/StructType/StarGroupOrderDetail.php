<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for StarGroupOrderDetail StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class StarGroupOrderDetail extends AbstractStructBase
{
    /**
     * The StarGroupOrderNumber
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * - nillable: true
     * @var int
     */
    protected ?int $StarGroupOrderNumber;
    /**
     * Constructor method for StarGroupOrderDetail
     * @uses StarGroupOrderDetail::setStarGroupOrderNumber()
     * @param int $starGroupOrderNumber
     */
    public function __construct(?int $starGroupOrderNumber)
    {
        $this
            ->setStarGroupOrderNumber($starGroupOrderNumber);
    }
    /**
     * Get StarGroupOrderNumber value
     * @return int
     */
    public function getStarGroupOrderNumber(): int
    {
        return $this->StarGroupOrderNumber;
    }
    /**
     * Set StarGroupOrderNumber value
     * @param int $starGroupOrderNumber
     * @return \StructType\StarGroupOrderDetail
     */
    public function setStarGroupOrderNumber(?int $starGroupOrderNumber): self
    {
        // validation for constraint: int
        if (!is_null($starGroupOrderNumber) && !(is_int($starGroupOrderNumber) || ctype_digit($starGroupOrderNumber))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($starGroupOrderNumber, true), gettype($starGroupOrderNumber)), __LINE__);
        }
        $this->StarGroupOrderNumber = $starGroupOrderNumber;
        
        return $this;
    }
}
