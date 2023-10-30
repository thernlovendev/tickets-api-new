<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for StarGroupOrderDetailResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class StarGroupOrderDetailResponse extends AbstractStructBase
{
    /**
     * The StarGroupOrderDetailResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\StarGroupOrderDetailResult|null
     */
    protected ?\StructType\StarGroupOrderDetailResult $StarGroupOrderDetailResult = null;
    /**
     * Constructor method for StarGroupOrderDetailResponse
     * @uses StarGroupOrderDetailResponse::setStarGroupOrderDetailResult()
     * @param \StructType\StarGroupOrderDetailResult $starGroupOrderDetailResult
     */
    public function __construct(?\StructType\StarGroupOrderDetailResult $starGroupOrderDetailResult = null)
    {
        $this
            ->setStarGroupOrderDetailResult($starGroupOrderDetailResult);
    }
    /**
     * Get StarGroupOrderDetailResult value
     * @return \StructType\StarGroupOrderDetailResult|null
     */
    public function getStarGroupOrderDetailResult(): ?\StructType\StarGroupOrderDetailResult
    {
        return $this->StarGroupOrderDetailResult;
    }
    /**
     * Set StarGroupOrderDetailResult value
     * @param \StructType\StarGroupOrderDetailResult $starGroupOrderDetailResult
     * @return \StructType\StarGroupOrderDetailResponse
     */
    public function setStarGroupOrderDetailResult(?\StructType\StarGroupOrderDetailResult $starGroupOrderDetailResult = null): self
    {
        $this->StarGroupOrderDetailResult = $starGroupOrderDetailResult;
        
        return $this;
    }
}
