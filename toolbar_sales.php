
  

<!-- Home Toolbar -->


<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>








<body>
    <?php
   
$department_array = array();
    $menu_array = array();
    $mantenance_array = array();



/////////////////////////////////////////////////////////////////////////////// 

$tb_program = 'addDealer.php';
$tb_descrip = 'Add A Dealer';

$mantenance_array[] = "<li><a href=$tb_program>$tb_descrip</a></li>\n";


$tb_program = 'select_employee.php';
$tb_descrip = 'Employee Maintenance';

$mantenance_array[] = "<li><a href=$tb_program>$tb_descrip</a></li>\n";

$tb_program = 'addscrip.php';
$tb_descrip = 'Add Company Scrips';

$mantenance_array[] = "<li><a href=$tb_program>$tb_descrip</a></li>\n";


$tb_program = 'upload_reply.php';
$tb_descrip = 'Upload Reply To Scrip';

$mantenance_array[] = "<li><a href=$tb_program>$tb_descrip</a></li>\n";

$tb_program = 'upload_photo.php';
$tb_descrip = ' Upload Photo';

$mantenance_array[] = "<li><a href=$tb_program>$tb_descrip</a></li>\n";

$tb_program = 'upload_name.php';
$tb_descrip = ' Upload Names';

$mantenance_array[] = "<li><a href=$tb_program>$tb_descrip</a></li>\n";

$tb_program = 'equipment_setup.php';
$tb_descrip = 'Equipment Setup';

$mantenance_array[] = "<li><a href=$tb_program>$tb_descrip</a></li>\n";

$tb_program = 'interduction.php';
$tb_descrip = 'Start Memory Program';

$mantenance_array[] = "<li><a href=$tb_program>$tb_descrip</a></li>\n";





      

    print "\n";
    print "<nav class=\"navbar navbar-inverse\">\n";
    print "  <div class=\"container-fluid\">\n";
    print "    <div class=\"navbar-header\">\n";

    print "    </div>\n";


    print "    <ul class=\"nav navbar-nav\">\n";



    print "        </ul>\n";

    print "<li class=\"dropdown\"><a style=\"color:yellow;\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Menu Maintenance <span class=\"caret\"></span></a>";
    print "<ul class=\"dropdown-menu\">";
    // print_r($mantenance_array);
    $result = count($mantenance_array);
    $count = 0;

    while ($count <= $result) {

        $load = @$mantenance_array[$count];
        echo $load;

        $count++;
    }
   // print "</ul>\n";
  //  print "</ul>\n";


  $dealer_name = "Sales Tool Box ";

   print "</ul>";
   // print "\n";
   // print "\n";
    print "<ul class=\"nav navbar-nav navbar-right\">";
    print "<li class=\"active\"><a style=\"color:yellow; font-size: 30px;\" href=\"#\"> $dealer_name </a></li>\n";
    print "<li class=\"dropdown\"><a style=\"color:yellow;\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Help<span class=\"caret\"></span></a>\n";
    print "<ul class=\"dropdown-menu\">\n";
    print "<li><a href=\"../video_training.php?video=page\"target=_blank>Help for current page</a></li> 	\n";
   // print "<li><a href=\"../help_page.php?page=sales\">Help Page</a></li>\n";
    print "</ul>\n";
    print "<li><a style=\"color:yellow;\" href=\"emailform.php\">Feed Back</a></li>\n";
    print "<li><a style=\"color:yellow;\" href=\"logg_off.php\">Log out</a></li>";
    print "</ul>\n";
 
    print "</div>\n";
    print "<!--/.nav-collapse -->\n";
    print "</div>\n";
    print "		</div>\n";
    print "</nav>";
    ?>
					













