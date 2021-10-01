<?php 

namespace Salesforce;

class ApiHelper{

    public static function getSoqlConditions($values, $fields){

        $conditions = array();

        $fieldsWithValues = array_filter($values, function($value){

            return ($value !== "" && $value !== null);
        });

        if(empty($fieldsWithValues)) return null;


        foreach($fieldsWithValues as $field => $value){

            $syntax = $fields[$field];
            
            if($syntax == null) continue;

            $value = is_bool($value) ? ($value ? "True" : "False") : $value;

            $formatted = sprintf($syntax, $value);
            $conditions[] = $field . " " . $formatted;
        }

        return $conditions;
    }


    public static function getPicklistFieldValues($pickListField){

        $pValues = array();

        $pickListValues = $pickListField["picklistValues"];

        foreach($pickListValues as $value){

            $pValues[$value["value"]] = $value["label"];
        }

        return $pValues;
    }
}