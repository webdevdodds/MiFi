<?php

$link = mysqli_connect("localhost", "root", "Zero1Nine0", "database1");

if(mysqli_connect_error()){
  die("Could not Connect to Database");
}

$today=getdate();

$tdate=$today['year'].",".$today['mon'].",".$today['mday'];


$month=$today['mon'];

$year=$today['year'];

$date="2016,10,2";


function dayNetTransactions($date) {

  include 'link.php';

  $query = "SELECT SUM(amount) AS NetTrans FROM ProTrans WHERE `date`='$date'";

  $results=mysqli_query($link, $query);

  $row=mysqli_fetch_assoc($results);

  $netTrans=$row['NetTrans'];

  return $netTrans;
}



function showCal($month,$year) {

  $output;

  $balance=600;

  $dayOfWeek=array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");

  $date_info=getdate(mktime(0,0,0, $month, 1, $year));

  $numDays=cal_days_in_month(CAL_GREGORIAN, $date_info['mon'],$date_info['year']);

  $output .= "<table class='calendar text-center'>";

  $output.= "<tr><td colspan=7>".$date_info['month']." ".$date_info['year']."</td></tr>";

  $output.="<tr>";

  foreach ($dayOfWeek as $key => $value) {
    $output.="<td>".$value."</td>";
  }

  $output.="</tr>";

  for ($day=1; $day<=$numDays; $day++) {

    $date_info=getdate(mktime(0,0,0, $month, $day, $year));

    $date=$date_info['year'].",".$date_info['mon'].",".$date_info['mday'];

    $balance+=dayNetTransactions($date);

    if ($date_info['mday']==1) {

      if ($date_info['wday']==0) {
        $output.="<tr>";
      } else {
        $output.="<tr><td colspan=".$date_info['wday']."></td>";
      }


    }

    if ($date_info['wday']==0) {
      $output.= "<tr>";
    }



    if ($balance>=0) {
      $output.= "<td class=success>".$date_info['mday']."</td>";
    } else {
      $output.= "<td class=danger>".$date_info['mday']."</td>";
    }


    if ($date_info['wday']==6) {
      $output.= "</tr>";
    }

    $endrow=$date_info['wday'];
    $endrow=6-$endrow;

    if ($date_info['mday']==$numDays) {

      if ($date_info['wday']==6) {
        $output.="</tr>";
      } else {
        $output.="<td colspan=".$endrow."></td></tr>";
      }



    }


  }

  $output.="</table>";

  echo $output;

}

function calStrip($month, $year) {
  for ($i=$month; $i < $month+12; $i++) {
    $mon=$i;
    $yr=$year;

    if ($month>12) {
      $mon=$mon-12;
      $yr=$yr+1;
    }

    showCal($mon,$yr);
  }

}


?>


<html>
  <head>
    <title> N-Cal </title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <style>

      .calendar tr td{
        border-style: solid;
        border-color: black;
        border-width:1px;
        padding:2px;
        font-size:10px;
      }

      .calendar {
        display:inline-block;
      }

      .success {
        background-color: green;
      }

      .danger {
        background-color: red;
      }

    </style>
    <?php

    calStrip($month,$year);


    ?>
  </body>
</html>