<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PerformancesPOHPricesAvailabilityMCResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class PerformancesPOHPricesAvailabilityMCResponse extends AbstractStructBase
{
    /**
     * The PerformancesPOHPricesAvailabilityMCResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\PerformancesPOHPricesAvailabilityMCResult|null
     */
    protected ?\StructType\PerformancesPOHPricesAvailabilityMCResult $PerformancesPOHPricesAvailabilityMCResult = null;
    /**
     * Constructor method for PerformancesPOHPricesAvailabilityMCResponse
     * @uses PerformancesPOHPricesAvailabilityMCResponse::setPerformancesPOHPricesAvailabilityMCResult()
     * @param \StructType\PerformancesPOHPricesAvailabilityMCResult $performancesPOHPricesAvailabilityMCResult
     */
    public function __construct(?\StructType\PerformancesPOHPricesAvailabilityMCResult $performancesPOHPricesAvailabilityMCResult = null)
    {
        $this
            ->setPerformancesPOHPricesAvailabilityMCResult($performancesPOHPricesAvailabilityMCResult);
    }
    /**
     * Get PerformancesPOHPricesAvailabilityMCResult value
     * @return \StructType\PerformancesPOHPricesAvailabilityMCResult|null
     */
    public function getPerformancesPOHPricesAvailabilityMCResult(): ?\StructType\PerformancesPOHPricesAvailabilityMCResult
    {
        return $this->PerformancesPOHPricesAvailabilityMCResult;
    }
    /**
     * Set PerformancesPOHPricesAvailabilityMCResult value
     * @param \StructType\PerformancesPOHPricesAvailabilityMCResult $performancesPOHPricesAvailabilityMCResult
     * @return \StructType\PerformancesPOHPricesAvailabilityMCResponse
     */
    public function setPerformancesPOHPricesAvailabilityMCResult(?\StructType\PerformancesPOHPricesAvailabilityMCResult $performancesPOHPricesAvailabilityMCResult = null): self
    {
        $this->PerformancesPOHPricesAvailabilityMCResult = $performancesPOHPricesAvailabilityMCResult;
        
        return $this;
    }
}
