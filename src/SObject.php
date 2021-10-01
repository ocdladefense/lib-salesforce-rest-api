<?php

namespace Salesforce;



class SObject {

    private $name;

    private $meta;

    private $api;

    

    public function __construct($name){

        $this->name = $name;
    }


    public static function fromSobjectName($sObjectName, $metadata){

        $sobject = new self($sobjectName);
        $sobject->meta = $metadata;

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


    public function getPicklist($fieldName){

        $fieldMeta = $this->getField($fieldName);

        $pValues = array();

        $pickListValues = $fieldMeta["picklistValues"];

        foreach($pickListValues as $value){

            $pValues[$value["value"]] = $value["label"];
        }

        return $pValues;
    }
}