<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for HeartbeatResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class HeartbeatResponse extends AbstractStructBase
{
    /**
     * The HeartbeatResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\HeartbeatResult|null
     */
    protected ?\StructType\HeartbeatResult $HeartbeatResult = null;
    /**
     * Constructor method for HeartbeatResponse
     * @uses HeartbeatResponse::setHeartbeatResult()
     * @param \StructType\HeartbeatResult $heartbeatResult
     */
    public function __construct(?\StructType\HeartbeatResult $heartbeatResult = null)
    {
        $this
            ->setHeartbeatResult($heartbeatResult);
    }
    /**
     * Get HeartbeatResult value
     * @return \StructType\HeartbeatResult|null
     */
    public function getHeartbeatResult(): ?\StructType\HeartbeatResult
    {
        return $this->HeartbeatResult;
    }
    /**
     * Set HeartbeatResult value
     * @param \StructType\HeartbeatResult $heartbeatResult
     * @return \StructType\HeartbeatResponse
     */
    public function setHeartbeatResult(?\StructType\HeartbeatResult $heartbeatResult = null): self
    {
        $this->HeartbeatResult = $heartbeatResult;
        
        return $this;
    }
}
