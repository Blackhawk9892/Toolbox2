<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>Time Functions</title>
</head>
<body>

<?php
 
 function time_conversion($field1){		
                                   switch ($field1) {
                                              case "1am":
                                              $fb_time = '01:00';
                                              break;
                                              case "2am":
                                              $fb_time = '02:00';
                                              break;
                                              case "3am":
                                              $fb_time = '03:00';
                                               break;
																							  case "4am":
                                              $fb_time = '04:00';
                                              break;
                                              case "5am":
                                              $fb_time = '05:00';
                                              break;
                                              case "6am":
                                              $fb_time = '06:00';
                                               break;
																							 case "7am":
                                              $fb_time = '07:00';
                                              break;
                                              case "8am":
                                              $fb_time = '08:00';
                                              break;
                                              case "9am":
                                              $fb_time = '09:00';
                                               break;
																							  case "10am":
                                              $fb_time = '10:00';
                                              break;
                                              case "11am":
                                              $fb_time = '11:00';
                                              break;
                                              case "12pm":
                                              $fb_time = '12:00';
                                               break;
																						 case "1pm":
                                              $fb_time = '13:00';
                                              break;
                                              case "2pm":
                                              $fb_time = '14:00';
                                              break;
                                              case "3pm":
                                              $fb_time = '15:00';
                                               break;
																							  case "4pm":
                                              $fb_time = '16:00';
                                              break;
                                              case "5pm":
                                              $fb_time = '17:00';
                                              break;
                                              case "6pm":
                                              $fb_time = '18:00';
                                               break;
																							 case "7pm":
                                              $fb_time = '19:00';
                                              break;
                                              case "8pm":
                                              $fb_time = '20:00';
                                              break;
                                              case "9pm":
                                              $fb_time = '21:00';
                                               break;
																							  case "10pm":
                                              $fb_time = '22:00';
                                              break;
                                              case "11pm":
                                              $fb_time = '23:00';
                                              break;
                                              case "12am":
                                              $fb_time = '24:00';
                                               break;
                                             default:
                                              
                                              }
																		return $fb_time;
														}
 
        function am_pm(){
				
				               $timeAmPm_arr = array();
											 $timeAmPm_arr[] = "\n<option value=\"Off\">Off </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"1:00AM\">1:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"1:30AM\">1:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"2:00AM\">2:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"2:30AM\">2:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"3:00AM\">3:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"3:30AM\">3:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"4:00AM\">4:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"4:30AM\">4:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"5:00AM\">5:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"5:30AM\">5:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"6:00AM\">6:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"6:30AM\">6:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"7:00AM\">7:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"7:30AM\">7:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"8:00AM\">8:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"8:30AM\">8:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"9:00AM\">9:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"9:30AM\">9:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"10:00AM\">10:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"10:30AM\">10:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"11:00AM\">11:00AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"11:30AM\">11:30AM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"12:00PM\">12:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"12:30PM\">12:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"1:00PM\">1:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"1:30PM\">1:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"2:00PM\">2:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"2:30PM\">2:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"3:00PM\">3:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"3:30PM\">3:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"4:00PM\">4:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"4:30PM\">4:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"5:00PM\">5:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"5:30PM\">5:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"6:00PM\">6:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"6:30PM\">6:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"7:00PM\">7:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"7:30PM\">7:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"8:00PM\">8:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"8:30PM\">8:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"9:00PM\">9:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"9:30PM\">9:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"10:00PM\">10:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"10:30PM\">10:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"11:00PM\">11:00PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"11:30PM\">11:30PM </option>\n";
											 $timeAmPm_arr[] = "\n<option value=\"12:00AM\">12:00AM </option>\n";
										
											 
											 
											 return $timeAmPm_arr;
				
				}
				
				
				 function employee_title(){
				 
		$title_arr[] = "\n<option value=\"\"> </option>\n";
			
		$title_arr[] = "\n<option value=\"BDC Salesperson\">BDC Salesperson </option>\n";
		
		$title_arr[] = "\n<option value=\"Car Washer\">Car Washer </option>\n";
		
		$title_arr[] = "\n<option value=\"Lot Attendant\">Lot Attendant </option>\n";
		
		$title_arr[] = "\n<option value=\"Valet\">Valet </option>\n";

		$title_arr[] = "\n<option value=\"Lube Technician\">Lube Technician </option>\n";
		
		$title_arr[] = "\n<option value=\"Mechanic\">Mechanic </option>\n";
		
		$title_arr[] = "\n<option value=\"Receptionist\">Receptionist </option>\n";	
		
		$title_arr[] = "\n<option value=\"Parts\">Parts </option>\n";	
				
		$title_arr[] = "\n<option value=\"Salesperson\">Salesperson </option>\n";
		
		$title_arr[] = "\n<option value=\"Service Adviser\">Service Adviser </option>\n";
		
		$title_arr[] = "\n<option value=\"Manager\">Manager </option>\n";	
		
		return $title_arr;
		
		}
		
		
				function change_to_24hour($fb_time){		
                                   switch ($fb_time) {
                                              case "1:00AM":
                                              $fb_time = '01:00';
                                              break;
																							 case "1:30AM":
                                              $fb_time = '01:30';
                                              break;
                                              case "2:00AM":
                                              $fb_time = '02:00';
                                              break;
																							case "2:30AM":
                                              $fb_time = '02:30';
                                              break;
                                              case "3:00AM":
                                              $fb_time = '03:00';
                                               break;
																							 case "3:30AM":
                                              $fb_time = '03:30';
                                               break;
																							  case "4:00AM":
                                              $fb_time = '04:00';
                                              break;
																							 case "4:30AM":
                                              $fb_time = '04:30';
                                              break;
                                              case "5:00AM":
                                              $fb_time = '05:00';
                                              break;
																							case "5:30AM":
                                              $fb_time = '05:30';
                                              break;
                                              case "6:00AM":
                                              $fb_time = '06:00';
                                               break;
																							 case "6:30AM":
                                              $fb_time = '06:30';
                                               break;
																							 case "7:00AM":
                                              $fb_time = '07:00';
                                              break;
																							 case "7:30AM":
                                              $fb_time = '07:30';
                                              break;
                                              case "8:00AM":
                                              $fb_time = '08:00';
                                              break;
																							case "8:30AM":
                                              $fb_time = '08:30';
                                              break;
                                              case "9:00AM":
                                              $fb_time = '09:00';
                                               break;
																							 case "9:30AM":
                                              $fb_time = '09:30';
                                               break;
																							 case "10:00AM":
                                              $fb_time = '10:00';
                                              break;
																							case "10:30AM":
                                              $fb_time = '10:30';
                                              break;
                                              case "11:00AM":
                                              $fb_time = '11:00';
                                              break;
																							case "11:30AM":
                                              $fb_time = '11:30';
                                              break;
                                              case "12:00PM":
                                              $fb_time = '12:00';
                                               break;
																							 case "12:30PM":
                                              $fb_time = '12:30';
                                               break;
																						 case "1:00PM":
                                              $fb_time = '13:00';
                                              break;
																							case "1:30PM":
                                              $fb_time = '13:30';
                                              break;
                                              case "2:00PM":
                                              $fb_time = '14:00';
                                              break;
																							case "2:30PM":
                                              $fb_time = '14:30';
                                              break;
                                              case "3:00PM":
                                              $fb_time = '15:00';
                                               break;
																							 case "3:30PM":
                                              $fb_time = '15:30';
                                               break;
																							 case "4:00PM":
                                              $fb_time = '16:00';
                                              break;
																							case "4:30PM":
                                              $fb_time = '16:30';
                                              break;
                                              case "5:00PM":
                                              $fb_time = '17:00';
                                              break;
																							case "5:30PM":
                                              $fb_time = '17:30';
                                              break;
                                              case "6:00PM":
                                              $fb_time = '18:00';
                                               break;
																							 case "6:30PM":
                                              $fb_time = '18:30';
                                               break;
																							case "7:00PM":
                                              $fb_time = '19:00';
                                              break;
																							case "7:30PM":
                                              $fb_time = '19:30';
                                              break;
                                              case "8:00PM":
                                              $fb_time = '20:00';
                                              break;
																							case "8:30PM":
                                              $fb_time = '20:30';
                                              break;
                                              case "9:00PM":
                                              $fb_time = '21:00';
                                               break;
																							case "9:30PM":
                                              $fb_time = '21:30';
                                               break;
																							case "10:00PM":
                                              $fb_time = '22:00';
                                              break;
																							case "10:30PM":
                                              $fb_time = '22:30';
                                              break;
                                              case "11PM":
                                              $fb_time = '23:00';
                                              break;
																							case "11:00PM":
                                              $fb_time = '23:30';
                                              break;
                                              case "12:00AM":
                                              $fb_time = '24:00';
                                               break;
																							 
                                             default:
                                               $fb_time = 'Off';
                                              }
										return $fb_time;
										
							}
 ?>

</body>
</html>
