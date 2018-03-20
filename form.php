<?php
include_once 'common.php';
include_once 'access_control.php';

?>

<!DOCTYPE html>
<html>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>tapir-technologies.co.uk - home</title>
  <meta name="description" content="tapirs technologies - agile i.t. - open source, enterprise class and automation for all" />

  <?php readfile('../../../common/head.htm') ?>
  <script type="text/javascript">
    function submitform(form)
    {
      document.forms[form].submit();
    }

    function logout()
    {
      document.forms['logout'].submit();
    }

    function updateForm(pupil_id, formname) {
      var xhttp;
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("form-data").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "get_form_data.php?pupil_id=" + pupil_id + "&formname=" + formname, true);
      xhttp.send();
    }

    function checkboxClick(checkboxID) {
      if(document.getElementById(checkboxID).checked) {
        document.getElementById(checkboxID).value = "yes";
      } else {
        document.getElementById(checkboxID).value = "no";
      }
    }
  </script>

</head>
<body>

  <?php
  if (isset($_POST['formname'])) {
    $currentForm = $_SESSION["currentForm"] = process_input($_POST['formname']);
  } else {
    return_error("form not found");
  }
 ?>

 <?php readfile('navbar.htm'); ?>

   <?php

     $servername = "db711602684.db.1and1.com";
     $username = "dbo711602684";
     $password = "ZAQ!2wsx####";
     $dbname = "db711602684";

     try {
         $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     }
     catch(PDOException $e) {
       return_error("Server error, please try again later");

     }

     echo '<form class="form-horizontal" id="form" action="submit_form.php" method="POST" enctype="application/x-www-form-urlencoded">';

     echo '<div class="container">';
     echo '<div id="form-data">';

     include('get_form_data.php');

     echo '</div>';

     echo '<div class="form-group">
         <div class="col-sm-offset-2 col-sm-10">
           <button type="submit" class="btn btn-default">submit</button>
         </div>
       </div>
     </form>';

     echo '</div>';
    ?>
</body>
</html>
