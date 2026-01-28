	



<?php

//================================================START FUNCTION================================================================

function replace_apostrophes($field1) {  // Replace apostrophes with * for putting into SQL

        $string_array = str_split($field1);
            $i = 0;
        foreach ($string_array as $value ) {

                if($value == "'"){
                    $string_array[$i] = "*";

                }
                $i++;
        }  
        
          $string = "";
         foreach ($string_array as $value) {
               
                    $string .= $value;               
           }

    return $string;
}

// END FUNCTION replace_apostrophes
//***********************************************END FUNCTION*************************************************************

//==============================================START FUNCTION============================================================

function replace_star($field1) {  // Replace star with ' for taking out of SQL

        $string_array = str_split($field1);
            $i = 0;
        foreach ($string_array as $value ) {

                if($value == "*"){
                    $string_array[$i] = "'";

                }
                $i++;
        }  
        
          $string = "";
         foreach ($string_array as $value) {
               
                    $string .= $value;               
           }

    return $string;
}

// END FUNCTION replace_star
//***********************************************END FUNCTION*************************************************************

?>