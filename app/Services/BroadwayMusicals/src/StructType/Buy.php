<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Buy StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class Buy extends AbstractStructBase
{
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
     * The Price
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var float
     */
    protected float $Price;
    /**
     * The BookingLastName
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingLastName;
    /**
     * The BookingFirstName
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingFirstName;
    /**
     * The BookingReferenceNumber
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingReferenceNumber;
    /**
     * The BookingNotes
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingNotes;
    /**
     * The seatingVerificationList
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var \ArrayType\ArrayOfBiSeatingVerification
     */
    protected \ArrayType\ArrayOfBiSeatingVerification $seatingVerificationList;
    /**
     * The Session
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $Session;
    /**
     * The BookingEmailAddress
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingEmailAddress;
    /**
     * The BookingCellPhoneNumber
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var \StructType\PhoneInfo
     */
    protected \StructType\PhoneInfo $BookingCellPhoneNumber;
    /**
     * The BookingAddress
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingAddress;
    /**
     * The BookingCity
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingCity;
    /**
     * The BookingState
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingState;
    /**
     * The BookingZipOrPostalCode
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingZipOrPostalCode;
    /**
     * The BookingCountry
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $BookingCountry;
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
     * Constructor method for Buy
     * @uses Buy::setProductId()
     * @uses Buy::setOneShowCode()
     * @uses Buy::setQuantity()
     * @uses Buy::setPrice()
     * @uses Buy::setBookingLastName()
     * @uses Buy::setBookingFirstName()
     * @uses Buy::setBookingReferenceNumber()
     * @uses Buy::setBookingNotes()
     * @uses Buy::setSeatingVerificationList()
     * @uses Buy::setSession()
     * @uses Buy::setBookingEmailAddress()
     * @uses Buy::setBookingCellPhoneNumber()
     * @uses Buy::setBookingAddress()
     * @uses Buy::setBookingCity()
     * @uses Buy::setBookingState()
     * @uses Buy::setBookingZipOrPostalCode()
     * @uses Buy::setBookingCountry()
     * @uses Buy::setEventDateTime()
     * @param int $productId
     * @param string $oneShowCode
     * @param int $quantity
     * @param float $price
     * @param string $bookingLastName
     * @param string $bookingFirstName
     * @param string $bookingReferenceNumber
     * @param string $bookingNotes
     * @param \ArrayType\ArrayOfBiSeatingVerification $seatingVerificationList
     * @param string $session
     * @param string $bookingEmailAddress
     * @param \StructType\PhoneInfo $bookingCellPhoneNumber
     * @param string $bookingAddress
     * @param string $bookingCity
     * @param string $bookingState
     * @param string $bookingZipOrPostalCode
     * @param string $bookingCountry
     * @param string $eventDateTime
     */
    public function __construct(int $productId, string $oneShowCode, int $quantity, float $price, string $bookingLastName, string $bookingFirstName, string $bookingReferenceNumber, string $bookingNotes, \ArrayType\ArrayOfBiSeatingVerification $seatingVerificationList, string $session, string $bookingEmailAddress, \StructType\PhoneInfo $bookingCellPhoneNumber, string $bookingAddress, string $bookingCity, string $bookingState, string $bookingZipOrPostalCode, ?string $bookingCountry, ?string $eventDateTime)
    {
        $this
            ->setProductId($productId)
            ->setOneShowCode($oneShowCode)
            ->setQuantity($quantity)
            ->setPrice($price)
            ->setBookingLastName($bookingLastName)
            ->setBookingFirstName($bookingFirstName)
            ->setBookingReferenceNumber($bookingReferenceNumber)
            ->setBookingNotes($bookingNotes)
            ->setSeatingVerificationList($seatingVerificationList)
            ->setSession($session)
            ->setBookingEmailAddress($bookingEmailAddress)
            ->setBookingCellPhoneNumber($bookingCellPhoneNumber)
            ->setBookingAddress($bookingAddress)
            ->setBookingCity($bookingCity)
            ->setBookingState($bookingState)
            ->setBookingZipOrPostalCode($bookingZipOrPostalCode)
            ->setBookingCountry($bookingCountry)
            ->setEventDateTime($eventDateTime);
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
     * @return \StructType\Buy
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
     * @return \StructType\Buy
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
     * @return \StructType\Buy
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
     * Get Price value
     * @return float
     */
    public function getPrice(): float
    {
        return $this->Price;
    }
    /**
     * Set Price value
     * @param float $price
     * @return \StructType\Buy
     */
    public function setPrice(float $price): self
    {
        // validation for constraint: float
        if (!is_null($price) && !(is_float($price) || is_numeric($price))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a float value, %s given', var_export($price, true), gettype($price)), __LINE__);
        }
        $this->Price = $price;
        
        return $this;
    }
    /**
     * Get BookingLastName value
     * @return string
     */
    public function getBookingLastName(): string
    {
        return $this->BookingLastName;
    }
    /**
     * Set BookingLastName value
     * @param string $bookingLastName
     * @return \StructType\Buy
     */
    public function setBookingLastName(string $bookingLastName): self
    {
        // validation for constraint: string
        if (!is_null($bookingLastName) && !is_string($bookingLastName)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingLastName, true), gettype($bookingLastName)), __LINE__);
        }
        $this->BookingLastName = $bookingLastName;
        
        return $this;
    }
    /**
     * Get BookingFirstName value
     * @return string
     */
    public function getBookingFirstName(): string
    {
        return $this->BookingFirstName;
    }
    /**
     * Set BookingFirstName value
     * @param string $bookingFirstName
     * @return \StructType\Buy
     */
    public function setBookingFirstName(string $bookingFirstName): self
    {
        // validation for constraint: string
        if (!is_null($bookingFirstName) && !is_string($bookingFirstName)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingFirstName, true), gettype($bookingFirstName)), __LINE__);
        }
        $this->BookingFirstName = $bookingFirstName;
        
        return $this;
    }
    /**
     * Get BookingReferenceNumber value
     * @return string
     */
    public function getBookingReferenceNumber(): string
    {
        return $this->BookingReferenceNumber;
    }
    /**
     * Set BookingReferenceNumber value
     * @param string $bookingReferenceNumber
     * @return \StructType\Buy
     */
    public function setBookingReferenceNumber(string $bookingReferenceNumber): self
    {
        // validation for constraint: string
        if (!is_null($bookingReferenceNumber) && !is_string($bookingReferenceNumber)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingReferenceNumber, true), gettype($bookingReferenceNumber)), __LINE__);
        }
        $this->BookingReferenceNumber = $bookingReferenceNumber;
        
        return $this;
    }
    /**
     * Get BookingNotes value
     * @return string
     */
    public function getBookingNotes(): string
    {
        return $this->BookingNotes;
    }
    /**
     * Set BookingNotes value
     * @param string $bookingNotes
     * @return \StructType\Buy
     */
    public function setBookingNotes(string $bookingNotes): self
    {
        // validation for constraint: string
        if (!is_null($bookingNotes) && !is_string($bookingNotes)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingNotes, true), gettype($bookingNotes)), __LINE__);
        }
        $this->BookingNotes = $bookingNotes;
        
        return $this;
    }
    /**
     * Get seatingVerificationList value
     * @return \ArrayType\ArrayOfBiSeatingVerification
     */
    public function getSeatingVerificationList(): \ArrayType\ArrayOfBiSeatingVerification
    {
        return $this->seatingVerificationList;
    }
    /**
     * Set seatingVerificationList value
     * @param \ArrayType\ArrayOfBiSeatingVerification $seatingVerificationList
     * @return \StructType\Buy
     */
    public function setSeatingVerificationList(\ArrayType\ArrayOfBiSeatingVerification $seatingVerificationList): self
    {
        $this->seatingVerificationList = $seatingVerificationList;
        
        return $this;
    }
    /**
     * Get Session value
     * @return string
     */
    public function getSession(): string
    {
        return $this->Session;
    }
    /**
     * Set Session value
     * @param string $session
     * @return \StructType\Buy
     */
    public function setSession(string $session): self
    {
        // validation for constraint: string
        if (!is_null($session) && !is_string($session)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($session, true), gettype($session)), __LINE__);
        }
        $this->Session = $session;
        
        return $this;
    }
    /**
     * Get BookingEmailAddress value
     * @return string
     */
    public function getBookingEmailAddress(): string
    {
        return $this->BookingEmailAddress;
    }
    /**
     * Set BookingEmailAddress value
     * @param string $bookingEmailAddress
     * @return \StructType\Buy
     */
    public function setBookingEmailAddress(string $bookingEmailAddress): self
    {
        // validation for constraint: string
        if (!is_null($bookingEmailAddress) && !is_string($bookingEmailAddress)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingEmailAddress, true), gettype($bookingEmailAddress)), __LINE__);
        }
        $this->BookingEmailAddress = $bookingEmailAddress;
        
        return $this;
    }
    /**
     * Get BookingCellPhoneNumber value
     * @return \StructType\PhoneInfo
     */
    public function getBookingCellPhoneNumber(): \StructType\PhoneInfo
    {
        return $this->BookingCellPhoneNumber;
    }
    /**
     * Set BookingCellPhoneNumber value
     * @param \StructType\PhoneInfo $bookingCellPhoneNumber
     * @return \StructType\Buy
     */
    public function setBookingCellPhoneNumber(\StructType\PhoneInfo $bookingCellPhoneNumber): self
    {
        $this->BookingCellPhoneNumber = $bookingCellPhoneNumber;
        
        return $this;
    }
    /**
     * Get BookingAddress value
     * @return string
     */
    public function getBookingAddress(): string
    {
        return $this->BookingAddress;
    }
    /**
     * Set BookingAddress value
     * @param string $bookingAddress
     * @return \StructType\Buy
     */
    public function setBookingAddress(string $bookingAddress): self
    {
        // validation for constraint: string
        if (!is_null($bookingAddress) && !is_string($bookingAddress)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingAddress, true), gettype($bookingAddress)), __LINE__);
        }
        $this->BookingAddress = $bookingAddress;
        
        return $this;
    }
    /**
     * Get BookingCity value
     * @return string
     */
    public function getBookingCity(): string
    {
        return $this->BookingCity;
    }
    /**
     * Set BookingCity value
     * @param string $bookingCity
     * @return \StructType\Buy
     */
    public function setBookingCity(string $bookingCity): self
    {
        // validation for constraint: string
        if (!is_null($bookingCity) && !is_string($bookingCity)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingCity, true), gettype($bookingCity)), __LINE__);
        }
        $this->BookingCity = $bookingCity;
        
        return $this;
    }
    /**
     * Get BookingState value
     * @return string
     */
    public function getBookingState(): string
    {
        return $this->BookingState;
    }
    /**
     * Set BookingState value
     * @param string $bookingState
     * @return \StructType\Buy
     */
    public function setBookingState(string $bookingState): self
    {
        // validation for constraint: string
        if (!is_null($bookingState) && !is_string($bookingState)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingState, true), gettype($bookingState)), __LINE__);
        }
        $this->BookingState = $bookingState;
        
        return $this;
    }
    /**
     * Get BookingZipOrPostalCode value
     * @return string
     */
    public function getBookingZipOrPostalCode(): string
    {
        return $this->BookingZipOrPostalCode;
    }
    /**
     * Set BookingZipOrPostalCode value
     * @param string $bookingZipOrPostalCode
     * @return \StructType\Buy
     */
    public function setBookingZipOrPostalCode(string $bookingZipOrPostalCode): self
    {
        // validation for constraint: string
        if (!is_null($bookingZipOrPostalCode) && !is_string($bookingZipOrPostalCode)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingZipOrPostalCode, true), gettype($bookingZipOrPostalCode)), __LINE__);
        }
        $this->BookingZipOrPostalCode = $bookingZipOrPostalCode;
        
        return $this;
    }
    /**
     * Get BookingCountry value
     * @return string
     */
    public function getBookingCountry(): string
    {
        return $this->BookingCountry;
    }
    /**
     * Set BookingCountry value
     * @param string $bookingCountry
     * @return \StructType\Buy
     */
    public function setBookingCountry(string $bookingCountry): self
    {
        // validation for constraint: string
        if (!is_null($bookingCountry) && !is_string($bookingCountry)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bookingCountry, true), gettype($bookingCountry)), __LINE__);
        }
        $this->BookingCountry = $bookingCountry;
        
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
     * @return \StructType\Buy
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
