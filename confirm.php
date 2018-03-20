<?php
include_once 'common.php';

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
</head>
<body>

  <?php

    if(!isset($_GET['confirm'])) {
      return_error("confirmation link has expired");
    } else {
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

      $confirm = process_input($_GET["confirm"]);
      $email = process_input($_GET['email']);

      try {
        $sql = "SELECT * FROM user_db WHERE username= :email AND auth_hash= :confirm;";

        $get_confirmation = $conn->prepare($sql);
        $get_confirmation->bindValue(':email', $email);
        $get_confirmation->bindValue(':confirm', $confirm);
        $get_confirmation->execute();

        $result = $get_confirmation->fetch(PDO::FETCH_ASSOC);

        if ($result['username'] != "") {
          //switch this so it takes you back to the register page with all the boxes filled in
          $_SESSION['redirect'] = "change_password_form.php";
          $_SESSION['formname'] = "new_password";
          $_SESSION['uid'] = $email;
          $_SESSION['pwd'] = $confirm;
          return_success("account confirmed, please create a password");
        } else {
          return_error("confirmation link has expired");
        }
      }
      catch(PDOException $e) {
        return_error("Server error, please try again later" . $e->getMessage());

      }

      $conn = null;
    }

  ?>
</body>
</html>
