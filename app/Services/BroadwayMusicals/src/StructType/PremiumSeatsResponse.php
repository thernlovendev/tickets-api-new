<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PremiumSeatsResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class PremiumSeatsResponse extends AbstractStructBase
{
    /**
     * The PremiumSeatsResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\PremiumSeatsResult|null
     */
    protected ?\StructType\PremiumSeatsResult $PremiumSeatsResult = null;
    /**
     * Constructor method for PremiumSeatsResponse
     * @uses PremiumSeatsResponse::setPremiumSeatsResult()
     * @param \StructType\PremiumSeatsResult $premiumSeatsResult
     */
    public function __construct(?\StructType\PremiumSeatsResult $premiumSeatsResult = null)
    {
        $this
            ->setPremiumSeatsResult($premiumSeatsResult);
    }
    /**
     * Get PremiumSeatsResult value
     * @return \StructType\PremiumSeatsResult|null
     */
    public function getPremiumSeatsResult(): ?\StructType\PremiumSeatsResult
    {
        return $this->PremiumSeatsResult;
    }
    /**
     * Set PremiumSeatsResult value
     * @param \StructType\PremiumSeatsResult $premiumSeatsResult
     * @return \StructType\PremiumSeatsResponse
     */
    public function setPremiumSeatsResult(?\StructType\PremiumSeatsResult $premiumSeatsResult = null): self
    {
        $this->PremiumSeatsResult = $premiumSeatsResult;
        
        return $this;
    }
}
