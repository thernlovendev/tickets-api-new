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

        if (!isset($data->NewDataSet->Table))
        {
            return $data->NewDataSet->Table1;
        }

        $resultArray = array();
        
        foreach ($data->NewDataSet->Table as $table)
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