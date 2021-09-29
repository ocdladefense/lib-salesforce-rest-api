<?php 

namespace Salesforce;

class ApiHelper{

    public static function getSoqlConditions($values, $syntaxInstructions){

        $conditions = array();

        $fieldsWithValues = array_filter($values);

        if(empty($fieldsWithValues)) return null;


        foreach($fieldsWithValues as $field => $value){

            $value = is_bool($value) ? ($value ? "True" : "False") : $value;

            $syntax = $syntaxInstructions[$field];
            $formatted = sprintf($syntax, $value);
            $conditions[] = $field . " " . $formatted;
        }

        return $conditions;
    }


    public static function getPicklistValues($field){

        $pValues = array();

        $pickListValues = $field["picklistValues"];

        foreach($pickListValues as $value){

            $pValues[$value["value"]] = $value["label"];
        }

        return $pValues;
    }
}