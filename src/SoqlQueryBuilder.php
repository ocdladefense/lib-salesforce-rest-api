<?php 

namespace Salesforce;

class SoqlQueryBuilder{

    public function __construct(){}

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


    public static function buildConditions($group){

        $builder = new self();

        $sql = "";

        $joinOperator = $group["op"];

        foreach($group["conditions"] as $condition) {

            if(!is_array($condition)) continue;

            if($condition["isGroup"] != True){

                $sql .= $builder->buildCondition($condition, $joinOperator);

            } else {

                foreach($condition["conditions"] as $subcondition){

                    if(!is_array($subcondition)) continue;

                    $joinOperator = $condition["op"];

                    $sql .= $builder->buildCondition($subcondition, $joinOperator);

                }
            }
        }

        var_dump($sql);exit;

    }


    public function buildCondition($item, $operator){

        return " " . $item["fieldname"] . " " . $item["op"] . " '" . $item["value"] . "' " .  $operator;

    }

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
