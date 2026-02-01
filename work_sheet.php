<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<!--
Add Dealer to stock tag program
-->
<html>
    <head>
        <title>Add Employee</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <div>
            <?php
            require("includes/connection.php");
            require("includes/functions.php");
            require("toolbar_sales.php");
            require("includes/database_rows.php");

          echo "<center><h1>Manager's Work Sheet</h1></center>";

          
           if (isset($_POST['back'])) {
            header("Location: points.php");
            exit;
        }

        
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



       if(isset($_GET['find'])){
           $_SESSION['find'] = $_GET['find'];

        }

        $find = $_SESSION['find'];

       
        
        $query = "SELECT * ";
        $query .= "FROM recording ";
        $query .= "WHERE record_cust_data = '{$find}' ";
        $query .= "ORDER BY record_index";

       
        $result_set = mysqli_query($con, $query)
                or die('Query failed: ' . mysql_error());

        while ($row = mysqli_fetch_array($result_set)) { // start while

            $record_empl_name = $row['record_empl_name'];
            $record_empl_num = $row['record_empl_num'];    
            $record_script = $row['record_script'];
            $record_vioce = $row['record_vioce'];
            $record_tone = $row['record_tone'];

           $bid_satus = 'photos';


             $rows[] = "\n<div id=\"$bid_satus\"><table><tr><td>$record_empl_name</td></tr> <td>$record_script</td></tr> <td>Tone to be used: $record_tone</td></tr></table> </div>";
                   
             $rows[] =" <audio controls>\n";
             $rows[] = "  <source src=\" $record_vioce \" type=\"audio/mpeg\">\n";
             $rows[] = "      Your browser does not support the audio element.\n";
  
             $rows[] = "      </audio>\n";

        }

            


?>

 
            <form action="points.php" method="post">


 <center>
            <h3>Enter anything the salesperson is doing to improve their career. </h3>
          
        
                        <textarea rows="6" cols="150" name="scrip" wrap="wrap " >
                          <?php if (isset($_POST['scrip'])) echo $_POST['scrip'] ?>
                        </textarea>
                <br />
                <br />
                         <label for="quantity">Inprovement Points (between 0 and 20):</label>
  <input type="number" id="quantity" name="quantity" min="0" max="20">
          <br />
                <br />
                    <input type="submit" name="submit" value="Submit"/>
                    <br />
                     <br />
                
                    <input type="submit" name="back" value="Back"/>
                    <br />
</center>
                   <?php
           
          

           echo "<h1>Recording</h1></center>";

                $result = count($rows);
               
                $count = 0;

                while ($count < $result) {
                    $load = $rows[$count];
                    echo $load;

                    $count++;
                }
                ?>

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