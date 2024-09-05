<!DOCTYPE html>
<?php
// Start the session
session_start();
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Microphone Recording</title>
</head>
<body>


    

<br>

 <?php
 
require_once("includes/constants.php");
require("includes/connection.php");
require("includes/database_rows.php");

require("toolbar_sales.php");
/////////////////////////////////////////////////////////////////////////////////////



if(isset($_POST['submit'])){
  
  $name = $_SESSION['name'];
  $emp_id = $_SESSION['emp_id'];
   $script = $_SESSION['script'];
  
  if(isset($_SESSION['audioName'])){
    $audioName = $_SESSION['audioName'];
  }else{
    $errors[] ='You submit without a recording. You now have new customers. Please get there names before moving on ';
  
  }
  
  
  if (!empty($errors)) {
  
    foreach ($errors as $value) {
        echo "<div class=\"errors\">$value</div>";
    }
  } else {

    $custStamp = $_SESSION['custStamp'];
    if(isset($_SESSION['options'])){
      $options = $_SESSION['options'];
    }else{
      $options = '';
    }
     

    $sql = "INSERT INTO recording(record_empl_num,record_empl_name,	record_script,record_vioce,record_cust_data,record_options) 
    VALUES('$emp_id','$name','$script','$audioName','$custStamp','$options')";
    
    
          if (!mysqli_query($con, $sql)) {
              die('Error training 57: ' . mysqli_error($con));
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



///////////////////////////////////////////////////////////////////////////////////

if($_GET['find']){
  $_SESSION['find'] = $_GET['find'];

}
$find = $_SESSION['find'];


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
   $cust_id = $row['cust_id'];
   $_SESSION['cust_id'] = $cust_id;
   $cust_find = $row['cust_find'];
   $cust_primary_user = $row['cust_primary_user'];
   $cust_primary = $row['cust_primary'];
   $cust_secondary = $row['cust_secondary'];
   $cust_vehicle = $row['cust_vehicle'];
////////////////////////////////////////////////////////////////////////////////

$query = "SELECT * ";
$query .= "FROM script ";
$query .= "WHERE script_group   = '{$comp_group}' ";
$query .= "ORDER BY script_order ";


$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){
  $script_arry[] = $row['script_template'];
  $script_audio_arry[] = $row['script_audio'];
  $tone_arry[] = $row['script_tone'];
}  

     $count = count($script_arry) - 1;

     if($cust_points  > $count ){
      $find = $_SESSION['find'];
      header("Location: test1.php?find=$find");
      exit;
     }
 
     $tone = $tone_arry[$cust_points];
$useTone = 'Record using a voice tone of: ' . $tone;
  echo "<h2 style='background-color:Orange;'>$useTone</h2>";


  $script = $script_arry[$cust_points];
  $script_audio = $script_audio_arry[$cust_points];
  
  echo "<h3>$script</h3>";
     $_SESSION['script'] = $script;
  
 

  ?>
 <button id="start-record-btn">Start Recording</button>
  <button id="stop-record-btn" disabled>Stop Recording</button>
  <audio id="audio-playback" controls></audio>
<br>
<br>
<?php


  echo " <img src=\"$cust_male_photo\" width=\"300\" height=\"300\">Customer 1\n";
   echo "     \n";
   echo "\n";
   echo "\n";
   echo "    <img src=\"$cust_female_photo\" width=\"300\" height=\"300\">Customer 2";

   //////////////////////////////////////////////////////////////////////////////////////////




  $cust_points = $cust_points + 1;
  
  mysqli_query($con, "UPDATE customer_data SET cust_points = '$cust_points'
   WHERE cust_find = '$cust_find' ");

////////////////////////////////////////////////////////////////////////////////////////////
   
  if($script_audio == 'Vehicle Driven'){
    $drive = 'Vehicle Driven';

    $query = "SELECT * ";
    $query .= "FROM audio ";
    $query .= "WHERE audio_group   = '{$comp_group}' ";
    $query .= "AND audio_vehicle_type   = '{$cust_vehicle}' ";
    $query .= "AND audio_drive_type   = '{$drive}' ";
   
    $result_set = mysqli_query($con, $query)
    or die('Query failed scrip: ' . mysqli_error($con));

    while($row = mysqli_fetch_array($result_set)){
      $audio_drive_type = $row['audio_drive_type'];
      $audio_id = $row['audio_id'];
      $audio_gender  = $row['audio_gender'];
      $driven_arry[] = $audio_id;
    
  }
   
  
$countDriven = count($driven_arry) - 1;
$randDriven = rand(0, $countDriven);

if($randDriven < 0){
  $randDriven = 0;
}



$idDriven = $driven_arry[$randDriven];

mysqli_query($con, "UPDATE customer_data SET cust_driven = '$idDriven'
              WHERE cust_id  = '$cust_id' ");
    
 }

////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////

if($script_audio == 'PrimaryName'){

  $primary_arry = array();

    $secondary_arry = array();

    $driven_arry = array();

  if($cust_primary_user == 1){
      $primary = "Male";
      $secondary = "Female";
  }else{
      $primary = "Female";
      $secondary = "Male";
  }

   
 
  $query = "SELECT * ";
  $query .= "FROM audio ";
  $query .= "WHERE audio_group   = '{$comp_group}' ";
  $query .= "AND audio_vehicle_type   = '{$cust_vehicle}' ";

  
  $result_set = mysqli_query($con, $query)
          or die('Query failed scrip: ' . mysqli_error($con));
  while($row = mysqli_fetch_array($result_set)){
    $audio_drive_type = $row['audio_drive_type'];
    $audio_id = $row['audio_id'];
    $audio_gender  = $row['audio_gender'];

    if($audio_drive_type == 'Primary Driver'){     
    if($audio_gender == $primary){
  
         $primary_arry[] = $audio_id;
    }
  }

  if($audio_drive_type == 'Secondary Driver'){
    if($audio_gender == $secondary){
      $secondary_arry[] = $audio_id;
    }
  }

}
print_r($primary_arry) . '<br>';
    $countPrimary = count($primary_arry) - 1;
 
    $randPrimary = rand(0, $countPrimary);
   

    if($randPrimary < 0){
      $randPrimary = 0;
    }
   
   
    $idPrimary = $primary_arry[$randPrimary];

    mysqli_query($con, "UPDATE customer_data SET cust_primary = '$idPrimary'
                  WHERE cust_id  = '$cust_id' ");
print_r($secondary_arry) . '<br>';
$countSecondary = count($secondary_arry) - 1;
$randSecondary = rand(0, $countSecondary);

if($randSecondary < 0){
  $randSecondary = 0;
}



$idSecondary = $secondary_arry[$randSecondary];

mysqli_query($con, "UPDATE customer_data SET cust_secondary = '$idSecondary'
              WHERE cust_id  = '$cust_id' ");
  
  ///////////////////////////////////////////////////////////////////////////////////////////



if($cust_primary_user == 1){

  echo "<br>";
  echo " <audio controls>\n";
  echo "  <source src=\" $cust_male_voice \" type=\"audio/mpeg\">\n";
  echo "      Your browser does not support the audio element.\n";
  
  echo "      </audio>\n";

  }else{
 
  echo "    <audio controls>\n";
  echo "  <source src=\"$cust_female_voice\" type=\"audio/mpeg\">\n";
  echo "      Your browser does not support the audio element.\n";
  echo "      </audio>";
  
  }

}
  //////////////////////////////////////////////////////////////////////////////////

  if($script_audio == 'PrimaryRequest'){

    
$query = "SELECT * ";
$query .= "FROM audio ";
$query .= "WHERE audio_group    = '{$comp_group}' ";
$query .= "AND audio_id    = '{$cust_primary}' ";



$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
$row = mysqli_fetch_array($result_set);
  $audio_location = $row['audio_location'];
  $_SESSION['options'] = $row['audio_options'];

  
  
  echo "    <audio controls>\n";
  echo "  <source src=\"$audio_location\" type=\"audio/mpeg\">\n";
  echo "      Your browser does not support the audio element.\n";
  echo "      </audio>";
  }

   //////////////////////////////////////////////////////////////////////////////////

   
    if($script_audio == 'SecondaryRequest'){

$query = "SELECT * ";
$query .= "FROM audio ";
$query .= "WHERE audio_group    = '{$comp_group}' ";
$query .= "AND audio_id    = '{$cust_secondary}' ";

$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
$row = mysqli_fetch_array($result_set);
  $audio_location = $row['audio_location'];
  $_SESSION['options'] = $row['audio_options'];

  
  
  echo "    <audio controls>\n";
  echo "  <source src=\"$audio_location\" type=\"audio/mpeg\">\n";
  echo "      Your browser does not support the audio element.\n";
  echo "      </audio>";
   }

  ////////////////////////////////////////////////////////////////////////////////

  if($script_audio == 'Vehicle Driven'){
         
    $query = "SELECT * ";
    $query .= "FROM audio ";
    $query .= "WHERE audio_group    = '{$comp_group}' ";
    $query .= "AND audio_id  = '{$idDriven}' ";
    
    
    
    $result_set = mysqli_query($con, $query)
            or die('Query failed scrip: ' . mysqli_error($con));
    $row = mysqli_fetch_array($result_set);
      $audio_location = $row['audio_location'];
      
      echo "    <audio controls>\n";
      echo "  <source src=\"$audio_location\" type=\"audio/mpeg\">\n";
      echo "      Your browser does not support the audio element.\n";
      echo "      </audio>";
       }
    
 

  
 
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
echo "<form action=\"training.php?find=$find\" method=\"post\">";

?>

<br />

<input   type="submit" name="submit" value="Submit"/>


<br />
 
<h1 style="background-color: Red;">After listening to your recording press the submit button.
If you do not listen to your recording you will not receive points. You may do as many recordings as you like. The only one that will be count is the one you submit.</h1>";
   
</body>

</html>
