<?php

declare(strict_types=1);

namespace App\Services\BroadwayMusicals\Src;

/**
 * Class which returns the class map definition
 */
class ClassMap
{
    /**
     * Returns the mapping between the WSDL Structs and generated Structs' classes
     * This array is sent to the \SoapClient when calling the WS
     * @return string[]
     */
    final public static function get(): array
    {
        return [
            'Heartbeat' => '\\StructType\\Heartbeat',
            'AuthHeader' => '\\StructType\\AuthHeader',
            'HeartbeatResponse' => '\\StructType\\HeartbeatResponse',
            'HeartbeatResult' => '\\StructType\\HeartbeatResult',
            'ShowBasics' => '\\StructType\\ShowBasics',
            'ShowBasicsResponse' => '\\StructType\\ShowBasicsResponse',
            'ShowBasicsResult' => '\\StructType\\ShowBasicsResult',
            'ShowDetails' => '\\StructType\\ShowDetails',
            'ShowDetailsResponse' => '\\StructType\\ShowDetailsResponse',
            'ShowDetailsResult' => '\\StructType\\ShowDetailsResult',
            'StarGroupOrderDetail' => '\\StructType\\StarGroupOrderDetail',
            'StarGroupOrderDetailResponse' => '\\StructType\\StarGroupOrderDetailResponse',
            'StarGroupOrderDetailResult' => '\\StructType\\StarGroupOrderDetailResult',
            'CityList' => '\\StructType\\CityList',
            'CityListResponse' => '\\StructType\\CityListResponse',
            'CityListResult' => '\\StructType\\CityListResult',
            'Performances' => '\\StructType\\Performances',
            'PerformancesResponse' => '\\StructType\\PerformancesResponse',
            'PerformancesResult' => '\\StructType\\PerformancesResult',
            'PerformancesPOHPricesAvailability' => '\\StructType\\PerformancesPOHPricesAvailability',
            'PerformancesPOHPricesAvailabilityResponse' => '\\StructType\\PerformancesPOHPricesAvailabilityResponse',
            'PerformancesPOHPricesAvailabilityResult' => '\\StructType\\PerformancesPOHPricesAvailabilityResult',
            'PerformancesPOHPricesAvailabilityMC' => '\\StructType\\PerformancesPOHPricesAvailabilityMC',
            'PerformancesPOHPricesAvailabilityMCResponse' => '\\StructType\\PerformancesPOHPricesAvailabilityMCResponse',
            'PerformancesPOHPricesAvailabilityMCResult' => '\\StructType\\PerformancesPOHPricesAvailabilityMCResult',
            'PremiumSeats' => '\\StructType\\PremiumSeats',
            'PremiumSeatsResponse' => '\\StructType\\PremiumSeatsResponse',
            'PremiumSeatsResult' => '\\StructType\\PremiumSeatsResult',
            'NewOrder' => '\\StructType\\NewOrder',
            'PhoneInfo' => '\\StructType\\PhoneInfo',
            'NewOrderResponse' => '\\StructType\\NewOrderResponse',
            'NewOrderResult' => '\\StructType\\NewOrderResult',
            'Select' => '\\StructType\\Select',
            'SelectResponse' => '\\StructType\\SelectResponse',
            'SelectResult' => '\\StructType\\SelectResult',
            'Buy' => '\\StructType\\Buy',
            'ArrayOfBiSeatingVerification' => '\\ArrayType\\ArrayOfBiSeatingVerification',
            'BiSeatingVerification' => '\\StructType\\BiSeatingVerification',
            'BuyResponse' => '\\StructType\\BuyResponse',
            'BuyResult' => '\\StructType\\BuyResult',
            'ExtendTime' => '\\StructType\\ExtendTime',
            'ExtendTimeResponse' => '\\StructType\\ExtendTimeResponse',
            'ExtendTimeResult' => '\\StructType\\ExtendTimeResult',
            'MainResponseFormat' => '\\StructType\\MainResponseFormat',
        ];
    }
}
