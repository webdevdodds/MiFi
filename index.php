<?php

include 'link.php';


$label=$_POST['label'];
$amount=$_POST['amount'];
$date=$_POST['date'];
$budget=$_POST['budget'];




if ($_POST['submit']) {
  $query = "INSERT INTO ProTrans (`label`,`amount`,`date`,`budget`) VALUES ('$label','$amount','$date','$budget')";


  if (mysqli_query($link, $query)) {
  } else {
    echo "Error: ". $query . "<br/>". mysqli_error($link);
  }


}

//function recuring($date) {
  $transDates=array();
  echo $date;
  for ($i=0; $i<26 ; $i++) {
  date_add($date,date_interval_create_from_date_string("2 weeks"));
  echo date_format($date,"Y-m-d")."<br/>";
  array_push($transDates, date_format($date,"Y-m-d"));
  }

print_r($transDates);

//foreach date INSERT a row into proTrans for the next 12 months

$query = "Select * From ProTrans";
$revenues;
$expenses;
$total;
$budTot;
$actTot;

if ($result=mysqli_query($link, $query)) {

  while ($row=mysqli_fetch_array($result)) {

    if ($row['amount']>0) {

      $revenues.="<tr><td></td><td>".$row['budget']."</td><td>$".$row['amount']."</td><td>$".$row['date']."</td></tr>";

    } else if ($row['amount']<0) {
      $expenses.="<tr><td></td><td>".$row['budget']."</td><td>$".$row['amount']."</td><td>".$row['date']."</td></tr>";

    }



    $budTot = $budTot + $row['amount'];
  }

  $total .="<tr><th>Total:</th><td></td><td>$".$budTot."</td><td></td></tr>";

} else {
  echo "it failed";
}

?>


<!DOCTYPE html>
<html>
  <head>
    <title>PHP Practice</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

  </head>

  <body>

    <form class="form-inline text-center" method="post">
      <input class="form-control" type="text" name="label" placeholder="Label" />
      <label>$</label><input class="form-control" type="text" name="amount" placeholder="Amount"/>
      <input class="form-control" type="date" name="date" placeholder="Date"/>
      <input class="form-control" type="text" name="budget" placeholder="Budget"/>
      <input class="btn btn-success" type="submit" name="submit" value="submit"/>
    </form>
    <table class="table">
      <tr><th>Revenues</th><td></td><td></td><td></td></tr>
      <?php echo $revenues; ?>
      <tr><th>Expenses</th><td></td><td></td><td></td></tr>
      <?php echo $expenses; ?>
      <tr></tr>
      <?php echo $total; ?>
    </table>

    <div class=container-fluid>
      <div class="row">
        <?php

          include 'calendarN.php';

        ?>
      </div>
    </div>




  </body>
</html>


