<?php 

namespace Salesforce;

class ApiHelper{

    public static function getOccupationFieldsDistinct(){

        $query = "SELECT Ocdla_Occupation_Field_Type__c FROM Contact ORDER BY Ocdla_Occupation_Field_Type__c DESC";

        $api = $this->loadForceApi();

        $result = $api->query($query);

        if(!$result->isSuccess()) throw new Exception($result->getErrorMessage());

        $records = $result->getRecords();

        $areas = array();

        foreach($records as $record){

            $area = $record["Ocdla_Occupation_Field_Type__c"];

            $areas[$area] = $area;
        }

        //var_dump($areas);exit;

        return $areas;
    }

    public static function getAreasOfInterest(){

        $pickListId = "0Nt5b000000CbzK";

        $req = $this->loadForceApi();

        $url = "/services/data/v39.0/tooling/sobjects/GlobalValueSet/$pickListId";

        $resp = $req->send($url);

        $picklistValues = $resp->getBody()["Metadata"]["customValue"];

        $areasOfInterest = array();
        foreach($picklistValues as $value){

            $valueName = $value["valueName"];

            $areasOfInterest[$valueName] = $valueName;
        }

        return $areasOfInterest;
    }

    public static function getContactField($fieldName){

        $endpoint = "/services/data/v23.0/sobjects/Contact/describe";
        $api = $this->loadForceApi();
        $resp = $api->send($endpoint);
        $fields = $resp->getBody()["fields"];

        foreach($fields as $field){

            if($field["name"] == $fieldName){

                return $field;
            }
        }

        return null;
    }

    public static function getPicklistValues($field){

        $pValues = array();

        $pickListValues = $field["picklistValues"];

        foreach($pickListValues as $value){

            $pValues[$value["value"]] = $value["label"];
        }

        return $pValues;
    }

    public static function getSoqlConditions($values, $fields){

        $conditions = array();

        $fieldsWithValues = array_filter($values);

        if(empty($fieldsWithValues)) return null;


        foreach($fieldsWithValues as $field => $value){

            $value = is_bool($value) ? ($value ? "True" : "False") : $value;

            $syntax = $fields[$field];
            $formatted = sprintf($syntax, $value);
            $conditions[] = $field . " " . $formatted;
        }

        return $conditions;
    }
}