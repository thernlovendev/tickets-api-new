<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for BuyResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class BuyResponse extends AbstractStructBase
{
    /**
     * The BuyResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\BuyResult|null
     */
    public ?\StructType\BuyResult $BuyResult = null;
    /**
     * Constructor method for BuyResponse
     * @uses BuyResponse::setBuyResult()
     * @param \StructType\BuyResult $buyResult
     */
    public function __construct(?\StructType\BuyResult $buyResult = null)
    {
        $this
            ->setBuyResult($buyResult);
    }
    /**
     * Get BuyResult value
     * @return \StructType\BuyResult|null
     */
    public function getBuyResult(): ?\StructType\BuyResult
    {
        return $this->BuyResult;
    }
    /**
     * Set BuyResult value
     * @param \StructType\BuyResult $buyResult
     * @return \StructType\BuyResponse
     */
    public function setBuyResult(?\StructType\BuyResult $buyResult = null): self
    {
        $this->BuyResult = $buyResult;
        
        return $this;
    }
}
