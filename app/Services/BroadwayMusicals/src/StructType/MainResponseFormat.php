<?php

namespace StructType;

class MainResponseFormat
{
    public function convertXmlToJson($xml, $type)
    {
        $result_str = $type."Result";
        $data = simplexml_load_string($xml->$result_str->any);
        $data = json_encode($data);
        $data = json_decode($data);

        if ($type="Select" && isset($data->NewDataSet->Outbound)) {
            return $data->NewDataSet->Outbound;
        } else {
            return $data->NewDataSet->Table1;
        }

        $resultArray = array();
        
        foreach ($tables as $table)
        {
            $temp = [];
            foreach ($table as $key => $value) {
                $temp[$key] = (string) $value;
            }
            $resultArray[] = $temp;
        }

        return $resultArray;
    }
}