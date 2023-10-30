<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for PerformancesResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class PerformancesResponse extends AbstractStructBase
{
    /**
     * The PerformancesResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\PerformancesResult|null
     */
    protected ?\StructType\PerformancesResult $PerformancesResult = null;
    /**
     * Constructor method for PerformancesResponse
     * @uses PerformancesResponse::setPerformancesResult()
     * @param \StructType\PerformancesResult $performancesResult
     */
    public function __construct(?\StructType\PerformancesResult $performancesResult = null)
    {
        $this
            ->setPerformancesResult($performancesResult);
    }
    /**
     * Get PerformancesResult value
     * @return \StructType\PerformancesResult|null
     */
    public function getPerformancesResult(): ?\StructType\PerformancesResult
    {
        return $this->PerformancesResult;
    }
    /**
     * Set PerformancesResult value
     * @param \StructType\PerformancesResult $performancesResult
     * @return \StructType\PerformancesResponse
     */
    public function setPerformancesResult(?\StructType\PerformancesResult $performancesResult = null): self
    {
        $this->PerformancesResult = $performancesResult;
        
        return $this;
    }
}
