<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ExtendTimeResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class ExtendTimeResponse extends AbstractStructBase
{
    /**
     * The ExtendTimeResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\ExtendTimeResult|null
     */
    protected ?\StructType\ExtendTimeResult $ExtendTimeResult = null;
    /**
     * Constructor method for ExtendTimeResponse
     * @uses ExtendTimeResponse::setExtendTimeResult()
     * @param \StructType\ExtendTimeResult $extendTimeResult
     */
    public function __construct(?\StructType\ExtendTimeResult $extendTimeResult = null)
    {
        $this
            ->setExtendTimeResult($extendTimeResult);
    }
    /**
     * Get ExtendTimeResult value
     * @return \StructType\ExtendTimeResult|null
     */
    public function getExtendTimeResult(): ?\StructType\ExtendTimeResult
    {
        return $this->ExtendTimeResult;
    }
    /**
     * Set ExtendTimeResult value
     * @param \StructType\ExtendTimeResult $extendTimeResult
     * @return \StructType\ExtendTimeResponse
     */
    public function setExtendTimeResult(?\StructType\ExtendTimeResult $extendTimeResult = null): self
    {
        $this->ExtendTimeResult = $extendTimeResult;
        
        return $this;
    }
}
