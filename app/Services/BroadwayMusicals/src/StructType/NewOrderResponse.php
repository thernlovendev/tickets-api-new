<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for NewOrderResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class NewOrderResponse extends AbstractStructBase
{
    /**
     * The NewOrderResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\NewOrderResult|null
     */
    protected ?\StructType\NewOrderResult $NewOrderResult = null;
    /**
     * Constructor method for NewOrderResponse
     * @uses NewOrderResponse::setNewOrderResult()
     * @param \StructType\NewOrderResult $newOrderResult
     */
    public function __construct(?\StructType\NewOrderResult $newOrderResult = null)
    {
        $this
            ->setNewOrderResult($newOrderResult);
    }
    /**
     * Get NewOrderResult value
     * @return \StructType\NewOrderResult|null
     */
    public function getNewOrderResult(): ?\StructType\NewOrderResult
    {
        return $this->NewOrderResult;
    }
    /**
     * Set NewOrderResult value
     * @param \StructType\NewOrderResult $newOrderResult
     * @return \StructType\NewOrderResponse
     */
    public function setNewOrderResult(?\StructType\NewOrderResult $newOrderResult = null): self
    {
        $this->NewOrderResult = $newOrderResult;
        
        return $this;
    }
}
