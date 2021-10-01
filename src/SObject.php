<?php

namespace Salesforce;



class SObject {

    private $name;

    private $meta;

    private $api;

    

    public function __construct($name){

        $this->name = $name;
    }


    public static function fromSobjectName($sobjectName, $api){

        $endpoint = "/services/data/v23.0/sobjects/$sObjectName/describe";

        $resp = $api->send($endpoint);

        $sobject = new self($sobjectName);
        $sobject->meta = $resp->getBody();

        return $sobject;
    }


    public function getField($fieldName){

        $fields = $this->meta["fields"];

        foreach($fields as $field){

            if($field["name"] == $fieldName){

                return $field;
            }
        }

        return null;
    }

    // Get a "DISTINCT", ordered list of field values.
    public function getDistinctFieldValues($fieldName, $descending = False){

        $query = "SELECT $fieldName FROM $this->name GROUP BY $fieldName";

        if($descending) $query .= " DESC";

        $result = $this->api->query($query);

        if(!$result->isSuccess()) throw new Exception($result->getErrorMessage());

        return $result->getRecords();
    }
}