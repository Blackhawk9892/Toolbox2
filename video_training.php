<?php

session_start();
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
    <head>
        <title>Vidio's </title>
    </head>
    <body>

        <?php
  
 
        $v = $_SESSION['video'];
        $playVideo = 'video/' . $v . '.mp4';
       //  unset($_SESSION['video']);


        echo"<center>";
        print "\n";

        print "<video width=\"920\" height=\"840\" autoplay>";

        print "  <source src=\"$playVideo\" type=\"video/mp4\">\n";

        print "  Your browser does not support HTML5 video.\n";
        print "</video>\n";

        echo"</center>";
        ?>

    </body>
</html>
