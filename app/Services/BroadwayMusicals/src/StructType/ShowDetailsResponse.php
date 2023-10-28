<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ShowDetailsResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class ShowDetailsResponse extends AbstractStructBase
{
    /**
     * The ShowDetailsResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\ShowDetailsResult|null
     */
    protected ?\StructType\ShowDetailsResult $ShowDetailsResult = null;
    /**
     * Constructor method for ShowDetailsResponse
     * @uses ShowDetailsResponse::setShowDetailsResult()
     * @param \StructType\ShowDetailsResult $showDetailsResult
     */
    public function __construct(?\StructType\ShowDetailsResult $showDetailsResult = null)
    {
        $this
            ->setShowDetailsResult($showDetailsResult);
    }
    /**
     * Get ShowDetailsResult value
     * @return \StructType\ShowDetailsResult|null
     */
    public function getShowDetailsResult(): ?\StructType\ShowDetailsResult
    {
        return $this->ShowDetailsResult;
    }
    /**
     * Set ShowDetailsResult value
     * @param \StructType\ShowDetailsResult $showDetailsResult
     * @return \StructType\ShowDetailsResponse
     */
    public function setShowDetailsResult(?\StructType\ShowDetailsResult $showDetailsResult = null): self
    {
        $this->ShowDetailsResult = $showDetailsResult;
        
        return $this;
    }
}
