<?php

namespace App\Services\BroadwayMusicals;

use Illuminate\Support\Facades\Log;
use RicorocksDigitalAgency\Soap\Facades\Soap;

class ServiceGeneral
{
    public function selectSeat($data)
    {
        try {
            $xml = $data['xml'];
            $response = Soap::to(env('BROADWAY_URL'))->call('Select', [
                'xml' => $xml,
            ]);
            return $response;
        } catch (\Exception $e)
        {
            Log::error('Broadwaymuscials API Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function buySeat($data)
    {
        try {
            $xml = $data['xml'];
            $response = Soap::to(env('BROADWAY_URL'))->call('Buy', [
                'xml' => $xml,
            ]);
            return $response;
        } catch (\Exception $e)
        {
            Log::error('Broadwaymuscials API Error: ' . $e->getMessage());
            throw $e;
        }
    }
}