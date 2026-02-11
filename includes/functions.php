	



<?php

//================================================START FUNCTION================================================================

function replace_apostrophes($field1) {  // Replace apostrophes with &#39 for putting into SQL

        $string_array = str_split($field1);
            $i = 0;
        foreach ($string_array as $value ) {

                if($value == "'"){
                    $string_array[$i] = "&#39";

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

//==============================================POINTS FUNCTION============================================================

function points($total, $find) {  // Add points to date base

          require_once("includes/constants.php");
           require("includes/connection.php");
           $function_array[] = $total;
           $function_array[] = $find;

            mysqli_query($con, "UPDATE customer_data SET cust_points = '$total'
           WHERE cust_find  = '$find' ");

       
     
   return $function_array;
}

// END FUNCTION replace_star
//***********************************************END FUNCTION*************************************************************

?>