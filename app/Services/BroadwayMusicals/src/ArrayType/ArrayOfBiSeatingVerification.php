<?php

declare(strict_types=1);

namespace ArrayType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructArrayBase;

/**
 * This class stands for ArrayOfBiSeatingVerification ArrayType
 * @subpackage Arrays
 */
class ArrayOfBiSeatingVerification extends AbstractStructArrayBase
{
    /**
     * The BiSeatingVerification
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - nillable: true
     * @var \StructType\BiSeatingVerification[]
     */
    protected ?array $BiSeatingVerification = null;
    /**
     * Constructor method for ArrayOfBiSeatingVerification
     * @uses ArrayOfBiSeatingVerification::setBiSeatingVerification()
     * @param \StructType\BiSeatingVerification[] $biSeatingVerification
     */
    public function __construct(?array $biSeatingVerification = null)
    {
        $this
            ->setBiSeatingVerification($biSeatingVerification);
    }
    /**
     * Get BiSeatingVerification value
     * An additional test has been added (isset) before returning the property value as
     * this property may have been unset before, due to the fact that this property is
     * removable from the request (nillable=true+minOccurs=0)
     * @return \StructType\BiSeatingVerification[]
     */
    public function getBiSeatingVerification(): ?array
    {
        return $this->BiSeatingVerification ?? null;
    }
    /**
     * This method is responsible for validating the value(s) passed to the setBiSeatingVerification method
     * This method is willingly generated in order to preserve the one-line inline validation within the setBiSeatingVerification method
     * This has to validate that each item contained by the array match the itemType constraint
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateBiSeatingVerificationForArrayConstraintFromSetBiSeatingVerification(?array $values = []): string
    {
        if (!is_array($values)) {
            return '';
        }
        $message = '';
        $invalidValues = [];
        foreach ($values as $arrayOfBiSeatingVerificationBiSeatingVerificationItem) {
            // validation for constraint: itemType
            if (!$arrayOfBiSeatingVerificationBiSeatingVerificationItem instanceof \StructType\BiSeatingVerification) {
                $invalidValues[] = is_object($arrayOfBiSeatingVerificationBiSeatingVerificationItem) ? get_class($arrayOfBiSeatingVerificationBiSeatingVerificationItem) : sprintf('%s(%s)', gettype($arrayOfBiSeatingVerificationBiSeatingVerificationItem), var_export($arrayOfBiSeatingVerificationBiSeatingVerificationItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The BiSeatingVerification property can only contain items of type \StructType\BiSeatingVerification, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        
        return $message;
    }
    /**
     * Set BiSeatingVerification value
     * This property is removable from request (nillable=true+minOccurs=0), therefore
     * if the value assigned to this property is null, it is removed from this object
     * @throws InvalidArgumentException
     * @param \StructType\BiSeatingVerification[] $biSeatingVerification
     * @return \ArrayType\ArrayOfBiSeatingVerification
     */
    public function setBiSeatingVerification(?array $biSeatingVerification = null): self
    {
        // validation for constraint: array
        if ('' !== ($biSeatingVerificationArrayErrorMessage = self::validateBiSeatingVerificationForArrayConstraintFromSetBiSeatingVerification($biSeatingVerification))) {
            throw new InvalidArgumentException($biSeatingVerificationArrayErrorMessage, __LINE__);
        }
        if (is_null($biSeatingVerification) || (is_array($biSeatingVerification) && empty($biSeatingVerification))) {
            unset($this->BiSeatingVerification);
        } else {
            $this->BiSeatingVerification = $biSeatingVerification;
        }
        
        return $this;
    }
    /**
     * Returns the current element
     * @see AbstractStructArrayBase::current()
     * @return \StructType\BiSeatingVerification|null
     */
    public function current(): ?\StructType\BiSeatingVerification
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see AbstractStructArrayBase::item()
     * @param int $index
     * @return \StructType\BiSeatingVerification|null
     */
    public function item($index): ?\StructType\BiSeatingVerification
    {
        return parent::item($index);
    }
    /**
     * Returns the first element
     * @see AbstractStructArrayBase::first()
     * @return \StructType\BiSeatingVerification|null
     */
    public function first(): ?\StructType\BiSeatingVerification
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see AbstractStructArrayBase::last()
     * @return \StructType\BiSeatingVerification|null
     */
    public function last(): ?\StructType\BiSeatingVerification
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see AbstractStructArrayBase::offsetGet()
     * @param int $offset
     * @return \StructType\BiSeatingVerification|null
     */
    public function offsetGet($offset): ?\StructType\BiSeatingVerification
    {
        return parent::offsetGet($offset);
    }
    /**
     * Add element to array
     * @see AbstractStructArrayBase::add()
     * @throws InvalidArgumentException
     * @param \StructType\BiSeatingVerification $item
     * @return \ArrayType\ArrayOfBiSeatingVerification
     */
    public function add($item): self
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\BiSeatingVerification) {
            throw new InvalidArgumentException(sprintf('The BiSeatingVerification property can only contain items of type \StructType\BiSeatingVerification, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        return parent::add($item);
    }
    /**
     * Returns the attribute name
     * @see AbstractStructArrayBase::getAttributeName()
     * @return string BiSeatingVerification
     */
    public function getAttributeName(): string
    {
        return 'BiSeatingVerification';
    }
}
