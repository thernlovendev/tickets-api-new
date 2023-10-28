<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Select StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class Select extends AbstractStructBase
{
    /**
     * The SaleTypesCode
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $SaleTypesCode;
    /**
     * The ProductId
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var int
     */
    protected int $ProductId;
    /**
     * The OneShowCode
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $OneShowCode;
    /**
     * The Quantity
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var int
     */
    protected int $Quantity;
    /**
     * The EventDateTime
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * - nillable: true
     * @var string
     */
    protected ?string $EventDateTime;
    /**
     * Constructor method for Select
     * @uses Select::setSaleTypesCode()
     * @uses Select::setProductId()
     * @uses Select::setOneShowCode()
     * @uses Select::setQuantity()
     * @uses Select::setEventDateTime()
     * @param string $saleTypesCode
     * @param int $productId
     * @param string $oneShowCode
     * @param int $quantity
     * @param string $eventDateTime
     */
    public function __construct(string $saleTypesCode, int $productId, string $oneShowCode, int $quantity, ?string $eventDateTime)
    {
        $this
            ->setSaleTypesCode($saleTypesCode)
            ->setProductId($productId)
            ->setOneShowCode($oneShowCode)
            ->setQuantity($quantity)
            ->setEventDateTime($eventDateTime);
    }
    /**
     * Get SaleTypesCode value
     * @return string
     */
    public function getSaleTypesCode(): string
    {
        return $this->SaleTypesCode;
    }
    /**
     * Set SaleTypesCode value
     * @param string $saleTypesCode
     * @return \StructType\Select
     */
    public function setSaleTypesCode(string $saleTypesCode): self
    {
        // validation for constraint: string
        if (!is_null($saleTypesCode) && !is_string($saleTypesCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($saleTypesCode, true), gettype($saleTypesCode)), __LINE__);
        }
        $this->SaleTypesCode = $saleTypesCode;
        
        return $this;
    }
    /**
     * Get ProductId value
     * @return int
     */
    public function getProductId(): int
    {
        return $this->ProductId;
    }
    /**
     * Set ProductId value
     * @param int $productId
     * @return \StructType\Select
     */
    public function setProductId(int $productId): self
    {
        // validation for constraint: int
        if (!is_null($productId) && !(is_int($productId) || ctype_digit($productId))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($productId, true), gettype($productId)), __LINE__);
        }
        $this->ProductId = $productId;
        
        return $this;
    }
    /**
     * Get OneShowCode value
     * @return string
     */
    public function getOneShowCode(): string
    {
        return $this->OneShowCode;
    }
    /**
     * Set OneShowCode value
     * @param string $oneShowCode
     * @return \StructType\Select
     */
    public function setOneShowCode(string $oneShowCode): self
    {
        // validation for constraint: string
        if (!is_null($oneShowCode) && !is_string($oneShowCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($oneShowCode, true), gettype($oneShowCode)), __LINE__);
        }
        $this->OneShowCode = $oneShowCode;
        
        return $this;
    }
    /**
     * Get Quantity value
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->Quantity;
    }
    /**
     * Set Quantity value
     * @param int $quantity
     * @return \StructType\Select
     */
    public function setQuantity(int $quantity): self
    {
        // validation for constraint: int
        if (!is_null($quantity) && !(is_int($quantity) || ctype_digit($quantity))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($quantity, true), gettype($quantity)), __LINE__);
        }
        $this->Quantity = $quantity;
        
        return $this;
    }
    /**
     * Get EventDateTime value
     * @return string
     */
    public function getEventDateTime(): string
    {
        return $this->EventDateTime;
    }
    /**
     * Set EventDateTime value
     * @param string $eventDateTime
     * @return \StructType\Select
     */
    public function setEventDateTime(?string $eventDateTime): self
    {
        // validation for constraint: string
        if (!is_null($eventDateTime) && !is_string($eventDateTime)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($eventDateTime, true), gettype($eventDateTime)), __LINE__);
        }
        $this->EventDateTime = $eventDateTime;
        
        return $this;
    }
}
