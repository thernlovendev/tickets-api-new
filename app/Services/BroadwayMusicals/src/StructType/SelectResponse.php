<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for SelectResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class SelectResponse extends AbstractStructBase
{
    /**
     * The SelectResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\SelectResult|null
     */
    public ?\StructType\SelectResult $SelectResult = null;
    /**
     * Constructor method for SelectResponse
     * @uses SelectResponse::setSelectResult()
     * @param \StructType\SelectResult $selectResult
     */
    public function __construct(?\StructType\SelectResult $selectResult = null)
    {
        $this
            ->setSelectResult($selectResult);
    }
    /**
     * Get SelectResult value
     * @return \StructType\SelectResult|null
     */
    public function getSelectResult(): ?\StructType\SelectResult
    {
        return $this->SelectResult;
    }
    /**
     * Set SelectResult value
     * @param \StructType\SelectResult $selectResult
     * @return \StructType\SelectResponse
     */
    public function setSelectResult(?\StructType\SelectResult $selectResult = null): self
    {
        $this->SelectResult = $selectResult;
        
        return $this;
    }
}
