<?php
session_start();
//$_SESSION['page']='sendeail.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <title>Sendemail Script</title>
    </head>
    <body>

        <!-- Reminder: Add the link for the 'next page' (at the bottom) --> 
        <!-- Reminder: Change 'YourEmail' to Your real email --> 

        <?php
        $ip = $_POST['ip'];
        $httpref = $_POST['httpref'];
        $httpagent = $_POST['httpagent'];
        $visitor = $_POST['visitor'];
        $visitormail = $_POST['visitormail'];

        $notes = $_POST['notes'];
        $attn = $_POST['attn'];

        if (isset($_POST['company'])) {
            $company = $_POST['company'];
            $visitor = $company . "<br />" . $visitor . "<br />";
        }

        if (isset($_POST['usrtel'])) {
            $usrtel = $_POST['usrtel'];
            $notes = ' Phone Number: ' . $usrtel . "<br />" . $notes;
        }
        /* if (eregi('http:', $notes)) {
          die ("Do NOT try that! ! ");
          } */
        if (!$visitormail == "" && (!strstr($visitormail, "@") || !strstr($visitormail, "."))) {
            echo "<h2>Use Back - Enter valid e-mail</h2>\n";
            $badinput = "<h2>Feedback was NOT submitted</h2>\n";
            echo $badinput;
            die("Go back! ! ");
        }

        if (empty($visitor) || empty($visitormail) || empty($notes) || empty($company)) {
            echo "<h2>Use Back - fill in all fields</h2>\n";
            die("Use back! ! ");
        }

        $todayis = date("l, F j, Y, g:i a");

        $attn = $attn;
        $subject = $attn;

        $notes = stripcslashes($notes);

        $message = " $todayis [EST] \n
Attention: $attn \n
Message: $notes \n 
From: $visitor ($visitormail)\n
Additional Info : IP = $ip \n
Browser Info: $httpagent \n
Referral : $httpref \n
";

        $from = "From: $visitormail\r\n";
        $message .= $from;
     
        mail("tim.dumouchel3@gmail.com", $subject, $message, "");
        ?>
 <h2>
        <p align="center">
           
            Date: <?php echo $todayis ?> 
            <br />
            Thank You : <?php echo $visitor ?> 
            <br />

            Attention: <?php echo $attn ?>
            <br /> 
            Message:<br /> 
<?php $notesout = str_replace("\r", "<br/>", $notes);
echo $notesout;
?> 
            <br />
            <?php //echo $ip ?> 

            <br /><br />
            <?php
            if (isset($_COOKIE['user'])) {
                print "<a href=\"main.php\"> Return </a>";
            } else {
                print "<a href=\"index.php\"> Return </a>";
            }
            ?>
        </p> 
</h2>
    </body>
</html>


