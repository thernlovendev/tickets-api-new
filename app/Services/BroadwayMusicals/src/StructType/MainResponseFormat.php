<?php

namespace StructType;

class MainResponseFormat
{
    public function convertXmlToJson($xml, $type)
    {
        $response = [];
        if ($type === "CityList")
        {
            $response = $this->cityList($xml);
        }
        if ($type === "Select")
        {
            $response = $this->Select($xml);
        }
        if ($type === "Buy")
        {
            $response = $this->Buy($xml);
        }
        return $response;
    }

    private function cityList($xml)
    {
        $data = simplexml_load_string($xml->CityListResult->any);
        $data = json_encode($data);
        $data = json_decode($data);
        $data = $data->NewDataSet->Table;
        return $data;
    }

    private function Select($xml)
    {
        $data = simplexml_load_string($xml->SelectResult->any);
        $data = json_encode($data);
        $data = json_decode($data);
        $data = $data->NewDataSet;
        return $data;
    }

    private function Buy($xml)
    {
        $data = simplexml_load_string($xml->BuyResult->any);
        $data = json_encode($data);
        $data = json_decode($data);
        $data = $data->NewDataSet;
        return $data;
    }
}