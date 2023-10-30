<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ShowBasicsResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class ShowBasicsResponse extends AbstractStructBase
{
    /**
     * The ShowBasicsResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\ShowBasicsResult|null
     */
    protected ?\StructType\ShowBasicsResult $ShowBasicsResult = null;
    /**
     * Constructor method for ShowBasicsResponse
     * @uses ShowBasicsResponse::setShowBasicsResult()
     * @param \StructType\ShowBasicsResult $showBasicsResult
     */
    public function __construct(?\StructType\ShowBasicsResult $showBasicsResult = null)
    {
        $this
            ->setShowBasicsResult($showBasicsResult);
    }
    /**
     * Get ShowBasicsResult value
     * @return \StructType\ShowBasicsResult|null
     */
    public function getShowBasicsResult(): ?\StructType\ShowBasicsResult
    {
        return $this->ShowBasicsResult;
    }
    /**
     * Set ShowBasicsResult value
     * @param \StructType\ShowBasicsResult $showBasicsResult
     * @return \StructType\ShowBasicsResponse
     */
    public function setShowBasicsResult(?\StructType\ShowBasicsResult $showBasicsResult = null): self
    {
        $this->ShowBasicsResult = $showBasicsResult;
        
        return $this;
    }
}
