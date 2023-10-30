<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for AuthHeader StructType
 * Meta information extracted from the WSDL
 * - type: tns:AuthHeader
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class AuthHeader extends AbstractStructBase
{
    /**
     * The username
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     * @var int
     */
    protected int $username;
    /**
     * The password
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var string|null
     */
    protected ?string $password = null;
    /**
     * Constructor method for AuthHeader
     * @uses AuthHeader::setUsername()
     * @uses AuthHeader::setPassword()
     * @param int $username
     * @param string $password
     */
    public function __construct(int $username, ?string $password = null)
    {
        $this
            ->setUsername($username)
            ->setPassword($password);
    }
    /**
     * Get username value
     * @return int
     */
    public function getUsername(): int
    {
        return $this->username;
    }
    /**
     * Set username value
     * @param int $username
     * @return \StructType\AuthHeader
     */
    public function setUsername(int $username): self
    {
        // validation for constraint: int
        if (!is_null($username) && !(is_int($username) || ctype_digit($username))) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($username, true), gettype($username)), __LINE__);
        }
        $this->username = $username;
        
        return $this;
    }
    /**
     * Get password value
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    /**
     * Set password value
     * @param string $password
     * @return \StructType\AuthHeader
     */
    public function setPassword(?string $password = null): self
    {
        // validation for constraint: string
        if (!is_null($password) && !is_string($password)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($password, true), gettype($password)), __LINE__);
        }
        $this->password = $password;
        
        return $this;
    }
}
