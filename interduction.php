<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Customer Interduction</title>
</head>
<body>

<br>

<?php

require_once("includes/constants.php");
require("includes/connection.php");
require("includes/database_rows.php");

require("toolbar_sales.php");

 

if(isset($_COOKIE["userId"])){
  $userId = $_COOKIE["userId"];


  
$emp_arry = Employee($userId);
$first = $emp_arry[0];
$last = $emp_arry[1];      
$position = $emp_arry[2];
$emp_id = $emp_arry[3];
$dealer_id = $emp_arry[4];
$name = $first . ' ' . $last;
}
$query = "SELECT * ";
$query .= "FROM script ";
$query .= "WHERE script_comp_num   = '{$dealer_id}' ";
$query .= "ORDER BY script_order ";


$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){
  $script_arry[] = $row['script_template'];
  $tone_arry[] = $row['script_tone'];
}  
$tone = $tone_arry[0];
$useTone = 'Record using a voice tone of: ' . $tone;
  echo "<h2 style='background-color:Orange;'>$useTone</h2>";

  $script = $script_arry[0];
  echo "<h3>$script</h3>";

  ?>

<button id="start-record-btn">Start Recording</button>
  <button id="stop-record-btn" disabled>Stop Recording</button>
  <audio id="audio-playback" controls></audio>
  <br>
  <br>
<?php
///////////////////////////////////////////////////////////////////////////////////

$query = "SELECT * ";
$query .= "FROM company ";
$query .= "WHERE comp_id   = '{$dealer_id}' ";

$result_set = mysqli_query($con, $query)
        or die('Query failed emp: ' . mysqli_error($con));
$row = mysqli_fetch_array($result_set);

$comp_group = $row['comp_group'];

///////////////////////////////////////////////////////////////////////////////////
$veh_array = array();
$query = "SELECT * ";
$query .= "FROM audio ";
$query .= "WHERE audio_group   = '{$comp_group}' ";

$result_set = mysqli_query($con, $query)
        or die('Query failed emp: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){
  $audio_vehicle_type = $row['audio_vehicle_type'];
       if(empty($audio_vehicle_type)){
        continue;
       }else{
        $veh_array[] = $audio_vehicle_type;
       }

}

$random_keys=array_rand($veh_array);
 $vehicle = $veh_array[$random_keys];
 $_SESSION['vehicle'] = $vehicle;

/////////////////////////////////////////////////////////////////////////////////////



if(isset($_POST['submit'])){

  
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
  
    $sql = "INSERT INTO recording(record_empl_num,record_empl_name,	record_script,record_vioce,record_cust_data) 
    VALUES('$emp_id','$name','$script','$audioName','$custStamp')";
    
    
          if (!mysqli_query($con, $sql)) {
              die('Error recording 216: ' . mysqli_error($con));
          }
  
          $custStamp = $_SESSION['custStamp'];
  
          header("Location: training.php?find=$custStamp");
          exit;
  
          unset($_SESSION['audioName']);
      }
    }

/////////////////////////////////////////////////////////////////////////////////////


$photoMale = array();

$type = 'Male';

$query = "SELECT * ";
$query .= "FROM photos ";
$query .= "WHERE photo_gender   = '{$type}' ";

$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){
  $photoMale[] = $row['photo_location'];
}  
$count = count($photoMale);
$n = rand(0, $count) - 1;
if($n < 0){
  $n = 0;
}

$malePhoto = $photoMale[$n];


echo " <img src=\"$malePhoto\" width=\"300\" height=\"300\">\n";

////////////////////////////////////////////////////////////////////////////////////////////

echo "\n";
echo "\n";
echo "\n";

$photoFemale = array();
$type = 'Female';

$query = "SELECT * ";
$query .= "FROM photos ";
$query .= "WHERE photo_gender   = '{$type}' ";

$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){
  $photoFemale[] = $row['photo_location'];
}  

$count = count($photoFemale);
$n = rand(0, $count) - 1 ;
if($n < 0){
  $n = 0;
}

$femalePhoto = $photoFemale[$n];

echo "    <img src=\"$femalePhoto\" width=\"300\" height=\"300\">";

//////////////////////////////////////////////////////////////////////////////////////////

$voiceMale = array();

$type = 'Male';

$query = "SELECT * ";
$query .= "FROM voice ";
$query .= "WHERE voice_gender   = '{$type}' ";

$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){
  $voiceMale[] = $row['voice_location'];
  $maleVoiceName[] = $row['voice_name'];
}     

$count = count($voiceMale);
$n = rand(0, $count) - 1 ;
if($n < 0){
  $n = 0;
}

$maleVoice = $voiceMale[$n];
$maleVoiceName = $maleVoiceName[$n];


echo "<br>";
echo " <audio controls>\n";
echo "        <source src=\"$maleVoice\" type=\"audio/mpeg\">\n";
echo "      Your browser does not support the audio element.\n";

echo "      </audio>\n";

/////////////////////////////////////////////////////////////////////////////////////////



$voiceFemale = array();
$type = 'Female';

$query = "SELECT * ";
$query .= "FROM voice ";
$query .= "WHERE voice_gender   = '{$type}' ";

$result_set = mysqli_query($con, $query)
        or die('Query failed scrip: ' . mysqli_error($con));
while($row = mysqli_fetch_array($result_set)){
  $voiceFemale[] = $row['voice_location'];
  $femaleVoiceName[] = $row['voice_name'];
}     

$count = count($voiceFemale);
$n = rand(0, $count) - 1;
if($n < 0){
  $n = 0;
}
$femaleVoice = $voiceFemale[$n];
$femaleVoiceName = $femaleVoiceName[$n];

echo "    <audio controls>\n";
echo "        <source src=\"$femaleVoice\" type=\"audio/mpeg\">\n";
echo "      Your browser does not support the audio element.\n";
echo "      </audio>";
//////////////////////////////////////////////////////////////////////////////////


 $dateStamp =  date("Ymdhis");
 $custStamp = $emp_id . $dateStamp . $dealer_id;
 $_SESSION['custStamp'] = $custStamp;
 $points = 1;
 $primaryUser = rand(1,2);

 $vehicle = $_SESSION['vehicle'];
 
 $sql = "INSERT INTO customer_data(cust_group,cust_male_name,cust_male_voice,cust_male_photo,cust_female_name,cust_female_voice,cust_female_photo,cust_find,cust_points,cust_salesperson_name,cust_salesperson_num,cust_company,cust_primary_user,cust_vehicle) 
 VALUES('$comp_group','$maleVoiceName','$maleVoice','$malePhoto','$femaleVoiceName','$femaleVoice','$femalePhoto','$custStamp','$points','$name','$emp_id','$dealer_id','$primaryUser','$vehicle')";
 
 
       if (!mysqli_query($con, $sql)) {
           die('Error customer _data 206: ' . mysqli_error($con));
       }




  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

      <br>
      <br>

  
 
   
</body>



<?php

echo "<form action=\"interduction.php\" method=\"post\">";

?>
<br />

<input   type="submit" name="submit" value="Submit"/>


<br />
<h1 style="background-color: Red;">After listening to your recording press the submit button.
   If you do not listen to your recording you will not receive points. You may do as many recordings as you like. The only one that will be count is the one you submit.</h1>";
</html>
