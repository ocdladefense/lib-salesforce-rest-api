<?php 

namespace Salesforce;

class SoqlQueryBuilder{

    public $baseQuery;

    public $selectFields;

    public $conditions;

    public $orderBy;



    public function __construct($query = null){

        $this->baseQuery = $query;
    }



    public function buildConditions($conditionGroup){

        // Filter out the conditions where no value is present at the key of "value".  With the exception of grouped conditions.
        $conditions = array_filter($conditionGroup["conditions"], function($con){

            return $con["isGroup"] == True || ($con["value"] !== null && $con["value"] !== ""); 

        });


        $sql = "";
        $joinOperator = $conditionGroup["op"];

        foreach($conditions as $condition) {

            if(!is_array($condition)) continue;

            if($condition["isGroup"] != True){      //The condition is not a group of conditions.  It is a single condition.

                $sql .= $this->buildCondition($condition, $joinOperator);

            } else {        //The condition is actually a group of conditions.  Set the join operator to that of the condition subgroup and iterate over the sub conditions.
                
                $joinOperator = $condition["op"];

                // Filter out the conditions with no values, and reset the keys...for the loop.
                $subConditions = array_values(array_filter($condition["conditions"], function($con){

                    return ($con["value"] !== null && $con["value"] !== "");
                    
                }));

                for($i = 0; $i < count($subConditions); $i++) {

                    if($i == 0) $sql .= "(";

                    if(!is_array($subconditions[$i])) continue;

                    $sql .= $this->buildCondition($subconditions[$i], $joinOperator);
                }

                $sql = trim($sql, " $joinOperator");
                $sql .= ")";
            }
        }

        $this->conditions = trim($sql, " $joinOperator");
    }


    public function buildCondition($item, $operator){

        $value = $item["value"];

        $value = is_bool($value) ? ($value ? "True" : "False") : $value;

        $formattedValue = sprintf($item["syntax"], $value);

        return $item["fieldname"] . " " . $item["op"] . " $formattedValue $operator ";
    }


    public function setQuery($query) {

        $this->baseQuery = $query;
    }

    public function setOrderBy($orderBy) {

        $this->orderBy = $orderBy;
    }


    // Just takes the entire condition string right now...including the join operator.
    public function addCondition($entireConditionString) {

        $this->conditions .= $entireConditionString;
    }


    public function compile() {

        $sql = $this->baseQuery;
        
        if(!empty($this->conditions)) $sql .= " WHERE $this->conditions";
        
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

function buildCondition($op, $opnd1, $opnd2 = null){

    $sql = "";

    $args = func_get_args();


    if($op == "AND" || $op == "OR"){

        array_shift($args);

        return implode(" $op ", array_map(function($item){

            return buildCondition($item["op"], $item["name"], $item["value"]);

        }, $args));

    } else {

        return "$opnd1 $op $opnd2";
    }
}

/* #endregion */