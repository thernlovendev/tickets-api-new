<?php

declare(strict_types=1);

namespace StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CityListResponse StructType
 * @subpackage Structs
 */
#[\AllowDynamicProperties]
class CityListResponse extends AbstractStructBase
{
    /**
     * The CityListResult
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 0
     * @var \StructType\CityListResult|null
     */
    public ?\StructType\CityListResult $CityListResult = null;
    /**
     * Constructor method for CityListResponse
     * @uses CityListResponse::setCityListResult()
     * @param \StructType\CityListResult $cityListResult
     */
    public function __construct(?\StructType\CityListResult $cityListResult = null)
    {
        $this
            ->setCityListResult($cityListResult);
    }
    /**
     * Get CityListResult value
     * @return \StructType\CityListResult|null
     */
    public function getCityListResult(): ?\StructType\CityListResult
    {
        return $this->CityListResult;
    }
    /**
     * Set CityListResult value
     * @param \StructType\CityListResult $cityListResult
     * @return \StructType\CityListResponse
     */
    public function setCityListResult(?\StructType\CityListResult $cityListResult = null): self
    {
        $this->CityListResult = $cityListResult;
        
        return $this;
    }
}
