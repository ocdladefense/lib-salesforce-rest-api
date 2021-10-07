<?php 

namespace Salesforce;

class SoqlQueryBuilder{

    public $baseQuery;

    public $selectFields;

    public $conditions;

    public $addedConditions;

    public $orderBy;



    public function __construct($query = null){

        $this->baseQuery = $query;
    }



    public function setConditions($conditionGroup){


        $this->conditions = $conditionGroup;
    }

    public function _buildConditions(){

        return self::buildConditions($this->conditions);
    }


        // Build using recursion
    public static function buildConditions($item) {

        $op = $item["op"];
        $name = $item["fieldname"];

    
        if($op == "AND" || $op == "OR"){
    
            return "(" . implode(" $op ", array_map("self::buildConditions", $item["conditions"])) . ")";
        }

        if($item["value"] === False || !empty($item["value"])) {

            $value = $item["value"];

            $value = is_bool($value) ? ($value ? "True" : "False") : $value;
    
            $formattedValue = sprintf($item["syntax"], $value);
        }
    
        return "$name $op $formattedValue";
    }


    public function setQuery($query) {

        $this->baseQuery = $query;
    }

    public function setOrderBy($orderBy) {

        $this->orderBy = $orderBy;
    }


    // Just takes the entire condition string right now...including the join operator.
    public function addCondition($conditionString) {

        $this->addedConditions .= $conditionString;
    }

    public function mergeValues($fields, $values = null, $removeEmpty = True) {

        if(is_null($values)) return $fields;

        $conditions = $fields["conditions"];

        if($removeEmpty){

            $filtered = array_filter($conditions, function($con) use ($values){

                $key = $con["fieldname"];

                $value = $values[$key];

                return ($value !== "" && $value !== null);
            });
            
        } else {

            $filtered = $conditions;
        }

        $merged = array_map(function($con) use ($values){

            $key = $con["fieldname"];
            $value = $values[$key];

            $con["value"] = $value;

            return $con;
        },$filtered);

        return array("op" => $fields["op"], "conditions" => $merged);
    }


    public function compile() {

        $sql = $this->baseQuery;
        
        if(!empty($this->conditions)) $sql .= " WHERE {$this->_buildConditions()} $this->addedConditions";
        
        if(!empty($this->orderBy)) $sql .= " ORDER BY $this->orderBy";

        return $sql;
    }

}



/* #region deprecate */

function getSoqlConditions($values, $fields){

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


    // // Build using recursion
    // public function buildCondition2($item) {

    //     $sql = "";

    //     $logicalOperator = " AND";

    //     foreach($item as $value) {

    //         if($item["isGroup"] != True){

    //             $formattedValue = $this->formatOperandValue($item);
    //             $sql .= "$logicalOperator {$item['fieldname']} {$item['op']} $formattedValue ";
                
    //         } else {

    //             $logicalOperator = $item["op"];

    //             $sql .= "$logicalOperator (";

    //             $subConditions = array_filter($condition["conditions"], function($con){

    //                 return ($con["value"] !== null && $con["value"] !== "");
                    
    //             });

    //             foreach($subConditions as $condition) $sql .= $this->buildCondition2($condition, $logicalOperator);

    //             $sql .= ") ";
    //         }
    //     }

    //     return $sql;
    // }


/* #endregion */