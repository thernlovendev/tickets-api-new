<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ExtendTime StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class ExtendTime extends AbstractStructBase
{
    /**
     * The Session
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var string
     */
    protected string $Session;
    /**
     * Constructor method for ExtendTime
     * @uses ExtendTime::setSession()
     * @param string $session
     */
    public function __construct(string $session)
    {
        $this
            ->setSession($session);
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
     * @return \StructType\ExtendTime
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
}
