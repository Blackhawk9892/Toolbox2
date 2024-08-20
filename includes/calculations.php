<?php

/*
 * Calculations used in programs
 */

function calculateTime($start, $end) {


    $startTimeArray = explode(" ", $start);
    
    $start_Ymd = $startTimeArray[0];

    $startDateArray = explode("-", $start_Ymd);

  

    $start_d = $startDateArray[2];

    $start_m = $startDateArray[1];

    $start_Y = $startDateArray[0];



    $start_His = $startTimeArray[1];

    $timeArray = explode(":", $start_His);


    $start_H = $timeArray[0];

    $start_i = $timeArray[1];

    $start_s = $timeArray[2];



    $endTimeArray = explode(" ", $end);
    $end_Ymd = $endTimeArray[0];

    $endDateArray = explode("-", $end_Ymd);



    $end_d = $endDateArray[2];

    $end_m = $endDateArray[1];

    $end_Y = $endDateArray[0];

    $end_His = $endTimeArray[1];

    $endTimeArray = explode(":", $end_His);


    $end_H = $endTimeArray[0];

    $end_i = $endTimeArray[1];

    $end_s = $endTimeArray[2];



    if ($end_s < $start_s) {
        $end_s = $end_s + 60;
        $end_i = $end_i - 1;
    }

    $Seconds = $end_s - $start_s;

    if ($end_i < $start_i) {
        $end_i = $end_i + 60;
        $end_H = $end_H - 1;
    }
    $Minutes = $end_i - $start_i;
$d = 0;
    if ($end_H < $start_H) {
        $end_H = $end_H + 24;
        $d = 1;
    }
    $Hours = $end_H - $start_H;

     $date1=date_create($start_Ymd);
$date2=date_create($end_Ymd);
$diff=date_diff($date1,$date2);

// %a outputs the total number of days
$Days = $diff->format("%a");

   $Days = $Days - $d; 



    $calculatedTime_array = array( "Days" => $Days, "Hours" => $Hours, "Minutes" => $Minutes);
    

    return $calculatedTime_array;
}

function updateTime($status,$id,$dealerId="none",$newStatus='none') {
    require("../includes/connection.php");
   
                $query = "SELECT * ";
                $query .= "FROM time ";
                $query .= "WHERE t_vehicle_id   = '{$id}' ";
                $query .= "AND t_status  = '{$status}' ";


                $result_set = mysqli_query($con, $query)
                        or die('Query failed time: ' . mysqli_error($con));
                $row = mysqli_fetch_array($result_set);


                $t_id = $row['t_id'];
                $t_start_time = $row['t_start_time'];

                $end = date("Y-m-d H:i:s");

                $calculatedTime_array = calculateTime($t_start_time, $end);


                $days = $calculatedTime_array['Days'];
                $hours = $calculatedTime_array['Hours'];
                $minutes = $calculatedTime_array['Minutes'];


                mysqli_query($con, "UPDATE time SET t_end_time = '$end'
                WHERE t_id = '$t_id' ");

                mysqli_query($con, "UPDATE time SET t_days = '$days'
                  WHERE t_id = '$t_id' ");

                mysqli_query($con, "UPDATE time SET t_hours = '$hours'
                  WHERE t_id = '$t_id' ");

                mysqli_query($con, "UPDATE time SET t_minutes = '$minutes'
                  WHERE t_id = '$t_id' ");

                

                if($newStatus != 'none'){
                $sql = "INSERT INTO time(t_dealer_id,t_vehicle_id,t_status) 
                VALUES('$dealerId','$id','$newStatus')";

                if (!mysqli_query($con, $sql)) {
                 die('Error input vehicles: ' . mysqli_error($con));
                  }
                }
}
////////////////////////////////////////////////////////////////

         function Dollar($price) {
             $newPrice = '';
             $vp = str_split("$price");


              $c = count($vp);

              $i=0;
              foreach ($vp as $value) {
                  $i++;
                   if($c == 4 and $i == 2){
                        $newPrice .= ',';
                    }
                  if($c == 5 and $i == 3){
                         $newPrice .= ',';
                    }
                  if($c == 6 and $i == 4){
                         $newPrice .= ',';
                    }
                  $newPrice .= $value;
                }
           return $newPrice;
           }
