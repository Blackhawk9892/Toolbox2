<!DOCTYPE html>
<?php
// Start the session
session_start();
?>
<html lang="en">
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Customer Inerview</title>
 
</head>
<body>


    



 <?php
 
require_once("includes/constants.php");
require("includes/connection.php");
require("includes/database_rows.php");
require("includes/functions.php");




require("toolbar_sales.php");

if(isset($_SESSION['scriptType'])){
   $scriptType = $_SESSION['scriptType'];
}else{
  $save_type_script = "";
$query = "SELECT * ";
$query .= "FROM objections ";


$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){

 $obj_type_script = $row['obj_type_script'];
   if($save_type_script != $obj_type_script){
      $type_script_arry[] = $obj_type_script;
   }
}  
      $count = count($type_script_arry) - 1;
      $i = rand(0, $count);
     $_SESSION['scriptType'] = $type_script_arry[$i];
     $scriptType = $_SESSION['scriptType'];
}
    

if($_GET['find']){
  $_SESSION['find'] = $_GET['find'];

}
$find = $_SESSION['find'];

/////////////////////////////////////////////////////////////////////////////////////


if(isset($_POST['submit'])){
  
  $name = $_SESSION['name'];
  $emp_id = $_SESSION['emp_id'];
  $script = $_SESSION['script'];
  $tone = $_SESSION['tone'];

  if(isset($_SESSION['script'])){
    $script = $_SESSION['script'];
    $script = replace_apostrophes($script);  // Replace apostrophes with * for putting into SQL
  }else{
    $errors[] ='Script is empty';
  }
   
  
  if(isset($_SESSION['audioName'])){
    $audioName = $_SESSION['audioName'];
  }else{
    $errors[] ='You submit without a recording.  ';
  
  }
  
  
  if (!empty($errors)) {

   $cust_id = $_SESSION['cust_id'];
    $cust_points = $_SESSION['cust_points'];
  
    mysqli_query($con, "UPDATE customer_data SET cust_points = '$cust_points'
     WHERE cust_id = '$cust_id' ");
  
    foreach ($errors as $value) {

      
      $cust_points = $_SESSION['cust_points'];

        $errorMassage = "<div class=\"errors\">$value</div>";
    }
  } else {

    $custStamp = $_SESSION['custStamp'];
    if(isset($_SESSION['options'])){
      $options = $_SESSION['options'];
    }else{
      $options = '';
    }
     

    $sql = "INSERT INTO recording(record_empl_num,record_empl_name,	record_script,record_vioce,record_cust_data,record_options,record_tone) 
    VALUES('$emp_id','$name','$script','$audioName','$custStamp','$options','$tone')";
    
    
          if (!mysqli_query($con, $sql)) {
              die('Error training 107: ' . mysqli_error($con));
          }
  
  
          $custStamp = $_SESSION['custStamp'];
  
        //  header("Location: training.php?find=$custStamp");
         // exit;
  
          unset($_SESSION['audioName']);
          unset($_SESSION['options']);
      }
    }

/////////////////////////////////////////////////////////////////////////////////////

if(isset($_COOKIE["userId"])){
  $userId = $_COOKIE["userId"];



$emp_arry = Employee($userId);
$first = $emp_arry[0];
$last = $emp_arry[1];      
$position = $emp_arry[2];
$emp_id = $emp_arry[3];
$dealer_id = $emp_arry[4];
$name = $first . ' ' . $last;
$_SESSION['name'] = $name;
$_SESSION['emp_id'] = $emp_id;
}

/////////////////////////////////////////////////////////////////////////////////////
$query = "SELECT * ";
$query .= "FROM company ";
$query .= "WHERE comp_id   = '{$dealer_id}' ";

$result_set = mysqli_query($con, $query)
        or die('Query failed emp: ' . mysqli_error($con));
$row = mysqli_fetch_array($result_set);

$comp_group = $row['comp_group'];
$comp_name = $row['comp_name'];
$comp_product = $row['comp_product'];



///////////////////////////////////////////////////////////////////////////////////




      $query = "SELECT * ";
      $query .= "FROM customer_data ";
      $query .= "WHERE cust_find  = '{$find}' ";

      $result_set = mysqli_query($con, $query)
              or die('Query failed emp: ' . mysqli_error($con));
      $row = mysqli_fetch_array($result_set);

  $cust_male_name = $row['cust_male_name'];
  $cust_male_voice = $row['cust_male_voice'];
  $cust_male_photo = $row['cust_male_photo'];

   $cust_female_name = $row['cust_female_name'];
  $cust_female_voice = $row['cust_female_voice'];
  $cust_female_photo = $row['cust_female_photo'];

  $cust_points = $row['cust_points'];
  $_SESSION['cust_points'] = $row['cust_points'];
 
   $cust_id = $row['cust_id'];
   $_SESSION['cust_id'] = $row['cust_id'];
   $cust_find = $row['cust_find'];
   $cust_primary_user = $row['cust_primary_user'];
   $cust_primary = $row['cust_primary'];
   $cust_secondary = $row['cust_secondary'];
   $cust_vehicle = $row['cust_vehicle'];
////////////////////////////////////////////////////////////////////////////////


$_SESSION['countLeave'] = 0;
$query = "SELECT * ";
$query .= "FROM objections ";
$query .= "WHERE 	obj_corporate_number   = '{$comp_group}' ";
$query .= "AND obj_type_script   = '{$scriptType}' ";
$query .= "ORDER BY obj_order ";


$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){
  $script_arry[] = $row['obj_script'];
  $script_audio_arry[] = $row['obj_script'];
  $tone_arry[] = $row['obj_tone'];
  $index_arry[] = $row['obj_index'];
  $audio_arry[] = $row['obj_audio'];
  $audio2_arry[] = $row['obj_audio2'];
  $voice_type_arry[] = $row['obj_voice_type'];
  $_SESSION['countLeave']++;
}  


 if($_SESSION['counter'] >= $_SESSION['countLeave'] and $_SESSION['counter'] > 0){
        
      $_SESSION['message'] = "Your points for today have been recorded. You may train as many times as you want, but only your first time counts for points.";
    

      $program = "home.php";
      $testPage =  test_production($program);

      header("Location: $testPage");
      exit;
   }


     $count = count($script_arry) - 1;
   
    $cName =  $comp_product; 

   


     if($cust_points  > $count ){
      $find = $_SESSION['find'];
    //  header("Location: $testPage?find=$find");
     // exit;
     }  
     
     $counter = $_SESSION['counter'];
    
   
     $index = $index_arry[$counter];

   
    
     $tone = $tone_arry[$counter];
     $_SESSION['tone'] = $tone;
$useTone = 'Record using a voice inflection of: ' . $tone;
  echo "<h2 style='background-color:Orange;'>$useTone</h2>";


  $script = $script_arry[$counter];
  $_SESSION['script'] = $script;
  $script_audio = $script_audio_arry[$counter];
  
  echo "<h3>$script</h3>";
     $_SESSION['script'] = $script;
  if(isset($errorMassage)){
    echo $errorMassage;
  }
     
//echo "<h1 style='background-color:White;'>Record the script</h1>";
  ?>
  <h1>
 <button id="start-record-btn">Start Recording</button>
  <button id="stop-record-btn" disabled>Stop Recording</button>
  <audio id="audio-playback" controls></audio>
</h1>
<br>
<br>
<?php


  echo " <img src=\"$cust_male_photo\" alt=$cust_id width=\"300\" height=\"300\">\n";
   echo "     \n";
   echo "\n";
   echo "\n";
   echo "    <img src=\"$cust_female_photo\" alt=$cust_id width=\"300\" height=\"300\">";

   //////////////////////////////////////////////////////////////////////////////////////////
   
    $audio = $audio_arry[$counter];
 //////////////////////////////////////Audio 1/////////////////////////////////////////////

    $voice_type = $voice_type_arry[$counter];

    if($voice_type == 'Starting'){
       echo "<h1 style='background-color:White; color:Red'>Listen to prospect objection before recording </h1>";
    }else{
       echo "<h1 style='background-color:White;'>Answer To Question</h1>";
    }
  echo " <audio controls>\n";
  echo "  <source src=\" $audio \" type=\"audio/mpeg\">\n";
  echo "      Your browser does not support the audio element.\n"; 
  echo "      </audio>\n";


  ////////////////////////////////////Audio 2//////////////////////////////////



  if($voice_type == 'Starting'){
     $audio2 = $audio2_arry[$counter];
  echo "<h1 style='background-color:White;'>Answer To Question</h1>";
  echo " <audio controls>\n";
  echo "  <source src=\" $audio2 \" type=\"audio/mpeg\">\n";
  echo "      Your browser does not support the audio element.\n"; 
  echo "      </audio>\n";

  }
////////////////////////////////////////////////////////////////////////////
  $_SESSION['counter'] = $_SESSION['counter'] + 1;

  $cust_points = $cust_points + 1;
  
  mysqli_query($con, "UPDATE customer_data SET cust_points = '$cust_points'
   WHERE cust_find = '$cust_find' ");

   
 

////////////////////////////////////////////////////////////////////////////////////////////

   
 
?>


      <br>
      <br>

     
 

  <script>
    let mediaRecorder;
    let audioChunks = [];

    const startRecordBtn = document.getElementById('start-record-btn');
    const stopRecordBtn = document.getElementById('stop-record-btn');
    const audioPlayback = document.getElementById('audio-playback');

    startRecordBtn.addEventListener('click', async () => {
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      mediaRecorder = new MediaRecorder(stream);
      
      mediaRecorder.start();
      startRecordBtn.disabled = true;
      stopRecordBtn.disabled = false;

      mediaRecorder.ondataavailable = event => {
        audioChunks.push(event.data);
      };

      mediaRecorder.onstop = async () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
        const audioUrl = URL.createObjectURL(audioBlob);
        audioPlayback.src = audioUrl;

        // Send audio data to server
        const formData = new FormData();
        formData.append('audio', audioBlob, 'recording.wav');

        await fetch('upload_audio.php', {
          method: 'POST',
          body: formData
        });

        audioChunks = [];
        startRecordBtn.disabled = false;
      };
    });

    stopRecordBtn.addEventListener('click', () => {
      mediaRecorder.stop();
      stopRecordBtn.disabled = true;
    });

    
  </script>

   <?php
$find = $_SESSION['find'];
echo "<form action=\"objection_training.php?find=$find\" method=\"post\">";

?>



<h1><input type="submit" name="submit" value="Submit"/></h1>



 
<h1 style="background-color: #ff7c8c;">After listening to your recording press the submit button.
If you do not listen to your recording you will not receive points. You may do as many recordings as you like. The only one that will be count is the one you submit.</h1>";
   
</body>

</html>
