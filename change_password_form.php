<?php
include_once 'common.php';
include_once 'access_control.php'

?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>tapir-technologies.co.uk - home</title>
  <meta name="description" content="tapirs technologies - agile i.t. - open source, enterprise class and automation for all" />

  <?php readfile('../../../common/head.htm') ?>
</head>
<body onload="document.getElementById('confirm_pass_div').addEventListener('keyup', checkPasswordMatch);">

  <?php
    if ($_POST["error"] != "") {
      print "<div class=\"alert alert-danger\" role=\"alert\">
        <strong>Error!</strong> " . $_POST["error"] . ".
      </div>";
    }
    if ($_POST["success"] != "") {
      print "<div class=\"alert alert-success\" role=\"alert\">
        <strong>Success</strong> " . $_POST["success"] . ".
      </div>";
    }
    if ($_POST["redirect"] != "") {
      $_SESSION["redirect"] = $_POST["redirect"];
    }
    if (isset($_POST['formname'])) {
      $currentForm = process_input($_POST['formname']);
    } else {
      $_SESSION = array();
      session_destroy();
      return_error("form not found");
    }
   ?>

   <?php

    $email = $_SESSION['uid'];

    print '<form class="form-horizontal" id="change_password_form" action="change_password.php" onsubmit="return checkPasswordMatch()" method="POST" enctype="application/x-www-form-urlencoded">';

    if($formname = "new_password") {


    } else if($formname = "update_password") {
      print '<div class="form-group">
        <label class="control-label col-sm-2" for="password">current password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="password" name="password" placeholder="enter your current password" required>
        </div>
      </div>';
    }
    print '<div class="form-group">
        <label class="control-label col-sm-2" for="password">new password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="password" name="password" placeholder="enter your new password" required>
        </div>
      </div>
      <div id="confirm_pass_div" class="form-group">
        <label class="control-label col-sm-2" for="password_confirm">confirm password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="confirm your new password" required>';
          print "<input type=\"hidden\" name=\"email\" value=\"$email\">";
        print '</div>
      </div>
    </form>
    <form class="form-horizontal" id="cancel" action="logout.php" method="POST" enctype="application/x-www-form-urlencoded">

    </form>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default" form="change_password_form">update</button>
        <button type="submit" class="btn btn-default" form="cancel">cancel</button>
      </div>
    </div>';

      /**print 'list of forms';

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

      try {
        $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA=:database AND TABLE_NAME LIKE '%form%'";

        $get_forms = $conn->prepare($sql);
        $get_forms->bindValue(':database', $dbname);
        $get_forms->execute();

        $result = $get_forms->fetchall(PDO::FETCH_ASSOC);

        foreach ($result as $form) {
          $form = $form['TABLE_NAME'];
          $formname = str_replace("_"," ",$form);
          $formname = str_replace(" form", "", $formname);
          print "<form id=\"$form\" action=\"form.php\" method=\"post\">
            <input type=\"hidden\" name=\"formname\" value=\"$form\">
            <a href=\"javascript: submitform('$form')\">$formname</a>
          </form>";
        }
      }
      catch(PDOException $e) {
        return_error("Server error, please try again later" . $e->getMessage());

      }**/

  ?>


</body>
