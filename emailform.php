
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Email Form </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../stylesheets/main.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    </head>
    <body>


        <form method="post" action="sendeail.php">
            <center>

                <!-- DO NOT change ANY of the php sections -->
                <?php
               
                require_once("includes/constants.php");
                require_once("includes/connection.php");
                require("includes/database_rows.php");
                require_once("toolbar_sales.php");

                
        if (isset($_COOKIE["userId"])) {
            $userId = $_COOKIE["userId"];
        } else {
            header("Location: ../index.php");
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
        $emp_user_name = $emp_arry[5];
        $emp_group = $emp_arry[6];
        $emp_email = $emp_arry[7];
        
        }

        $query = "SELECT * ";
        $query .= "FROM company ";
        $query .= "WHERE comp_id = '{$dealer_id}' ";


        $result_set = mysqli_query($con, $query)
                or die('Query failed dealer 85: ' . mysqli_error($con));
        $row = mysqli_fetch_array($result_set);
        $dealer_group = $row['comp_group'];
        $dealer_name = $row['comp_name'];
      


             $name = $first . ' ' . $last;

                $_POST['visitor'] = $name;
                $_POST['company'] = $dealer_name . ' ******Number: ' . $dealer_id;
                $_POST['visitormail'] = $emp_email;

               



                echo"<br />";
                echo"<br />";
                echo "<center><h1>Contact Photos For Dealers</h1></center>";
                echo"<br />";
                echo"<br />";

                $ipi = getenv("REMOTE_ADDR");
                $httprefi = getenv("HTTP_REFERER");
                $httpagenti = getenv("HTTP_USER_AGENT");
                ?>

                <input type="hidden" name="ip" value="<?php echo $ipi ?>" />
                <input type="hidden" name="httpref" value="<?php echo $httprefi ?>" />
                <input type="hidden" name="httpagent" value="<?php echo $httpagenti ?>" />

                Company: <br />
                <input type="text" name="company" size="60" value="<?php if (isset($_POST['company'])) echo $_POST['company'] ?>"	/>
                <br />
                Your Name: <br />
                <input type="text" name="visitor" size="50" value="<?php if (isset($_POST['visitor'])) echo $_POST['visitor'] ?>"	/>
                <br />
                Your Email:<br />
                <input type="text" name="visitormail" size="135" value="<?php if (isset($_POST['visitormail'])) echo $_POST['visitormail'] ?>" />
                <br /> <br />
                <br />
                Attention:<br />
                <select name="attn" size="1">
                    <option value=" Sales and Billing ">Sales and Billing </option> 
                    <option value=" General Support ">General Support </option> 
                    <option value=" Technical Support ">Technical Support </option> 
                    <option value=" Webmaster ">Webmaster </option> 
                </select>
                <br /><br />
                Mail Message:
                <br />
                <textarea name="notes" rows="4" cols="140"></textarea>
                <br />
                <input type="submit" value="Send Mail" />
                <br />

        </form>
        <br />
        <br />
       
       
        </center>
    </body>
</html>
