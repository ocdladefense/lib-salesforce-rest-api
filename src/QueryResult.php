<?php

namespace Salesforce;



class QueryResult  {

    const DEFAULT_DECODING_SCHEME = "associative_array";
    const OBJECT_DECODING_SCHEME = "object";
    const JSON_DECODING_SCHEME = "json";



    private $rows = array();


    public function __construct($rows = array()) {
        $this->rows = $rows;
    }



    public function getRecords(){

        return $this->rows;
    }


    public function count() {

        return null == $this->rows ? 0 : count($this->rows);
    }


    public function key($key) {
        $keys = $this->getField($key);

        return array_combine($keys,$this->rows);
    }
    

    public function getField($fieldName) {

        $records = $this->getRecords();
		
		return array_map(function($record) use($fieldName){
            return $record[$fieldName];
        }, $records);
    }

    public function group($fn) {

        $tmp = [];

        foreach($this->getRecords() as $record) {
            $key = $fn($record);
            if(!isset($tmp[$key])) {
                $tmp[$key] = array();
            }

            $tmp[$key] []= $record;
        }

        return $tmp;
    }

    public function getRecord($index = null){

        return $index == null ? $this->body["records"][0] : $this->body["records"][$index];
    }

    public function getFirst() {
        $index = 0;

        return $this->body["records"][$index];
    }


    public function getRecordCount(){

        return count($this->getRecords());
    }




    public function getSObject(){
        return $SObject;
    }

    



    
}
