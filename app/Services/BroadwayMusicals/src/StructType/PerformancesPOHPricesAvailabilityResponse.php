<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PerformancesPOHPricesAvailabilityResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class PerformancesPOHPricesAvailabilityResponse extends AbstractStructBase
{
    /**
     * The PerformancesPOHPricesAvailabilityResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\PerformancesPOHPricesAvailabilityResult|null
     */
    public ?\StructType\PerformancesPOHPricesAvailabilityResult $PerformancesPOHPricesAvailabilityResult = null;
    /**
     * Constructor method for PerformancesPOHPricesAvailabilityResponse
     * @uses PerformancesPOHPricesAvailabilityResponse::setPerformancesPOHPricesAvailabilityResult()
     * @param \StructType\PerformancesPOHPricesAvailabilityResult $performancesPOHPricesAvailabilityResult
     */
    public function __construct(?\StructType\PerformancesPOHPricesAvailabilityResult $performancesPOHPricesAvailabilityResult = null)
    {
        $this
            ->setPerformancesPOHPricesAvailabilityResult($performancesPOHPricesAvailabilityResult);
    }
    /**
     * Get PerformancesPOHPricesAvailabilityResult value
     * @return \StructType\PerformancesPOHPricesAvailabilityResult|null
     */
    public function getPerformancesPOHPricesAvailabilityResult(): ?\StructType\PerformancesPOHPricesAvailabilityResult
    {
        return $this->PerformancesPOHPricesAvailabilityResult;
    }
    /**
     * Set PerformancesPOHPricesAvailabilityResult value
     * @param \StructType\PerformancesPOHPricesAvailabilityResult $performancesPOHPricesAvailabilityResult
     * @return \StructType\PerformancesPOHPricesAvailabilityResponse
     */
    public function setPerformancesPOHPricesAvailabilityResult(?\StructType\PerformancesPOHPricesAvailabilityResult $performancesPOHPricesAvailabilityResult = null): self
    {
        $this->PerformancesPOHPricesAvailabilityResult = $performancesPOHPricesAvailabilityResult;
        
        return $this;
    }
}
