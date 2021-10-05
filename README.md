# "lib-salesforce-rest-api" repository




## SoqlQueryBuilder Class

#### Example: Array structure to be passed to "buildConditions".

```

        $conditionGroup = array(
            "op" => "AND",
            "conditions" => array(
                array(
                    "fieldname"  => "Ocdla_Current_Member_Flag__c",
                    "value"      => True,
                    "op"         => "=",
                    "syntax"     => "%s"
                ),
                array(
                    "fieldname"  => "FirstName",
                    "value"      => $_POST["FirstName"],
                    "op"         => "LIKE",
                    "syntax"     => "'%%%s%%'"
                ),
                array(
                    "fieldname"  => "LastName",
                    "value"      => $_POST["LastName"],
                    "op"         => "LIKE",
                    "syntax"     => "'%%%s%%'"
                ),
                array(
                    "fieldname"  => "Ocdla_Organization__c",
                    "value"      => $_POST["Ocdla_Organization__c"],
                    "op"         => "LIKE",
                    "syntax"     => "'%%%s%%'"
                ),
                array(
                    "fieldname"  => "MailingCity",
                    "value"      => $_POST["MailingCity"],
                    "op"         => "LIKE",
                    "syntax"     => "'%%%s%%'"
                ),
                array(
                    "fieldname"  => "Ocdla_Occupation_Field_Type__c",
                    "value"      => $_POST["Ocdla_Occupation_Field_Type__c"],
                    "op"         => "LIKE",
                    "syntax"     => "'%%%s%%'"
                ),
                array(
                    "fieldname"  => "Ocdla_Is_Expert_Witness__c",
                    "value"      => $_POST["Ocdla_Is_Expert_Witness__c"],
                    "op"         => "=",
                    "syntax"     => "%s"
                )
                array(
                    "op" => "OR",
                    "isGroup" => True,
                    "conditions" => array(
                        array(
                            "fieldname"  => "LastName",
                            "value"      => "Uehlin",
                            "op"         => "LIKE",
                            "syntax"     => "'%%%s%%'",
                            "isFirst"    => True
                        ),
                        array(
                            "fieldname"  => "LastName",
                            "value"      => "Johnson",
                            "op"         => "LIKE",
                            "syntax"     => "'%%%s%%'"
                        )
                    )
                )
            )
        );
 ```

 #### Example of the array structure for first parameter to be passed to the "getSoqlConditions" function
 ```
         $fields = array(
          "FirstName"                     => "LIKE '%%%s%%'",
          "LastName"                      => "LIKE '%%%s%%'",
          "Ocdla_Organization__c"         => "LIKE '%%%s%%'",
          "MailingCity"                   => "LIKE '%%%s%%'",
          "Ocdla_Occupation_Field_Type__c"=> "LIKE '%%%s%%'",
          "Ocdla_Current_Member_Flag__c"  => "= %s",
          "Ocdla_Is_Expert_Witness__c"    => "= %s"
        );

```


