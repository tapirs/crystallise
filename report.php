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
      $_SESSION['redirect'] = "index.php";
      return_error("request has already been removed, thank you");
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
        $_SESSION['redirect'] = "index.php";
        return_error("Server error, please try again later");
      }

      $confirm = process_input($_GET["confirm"]);
      $email = process_input($_GET['email']);

      try {
        $sql = "DELETE FROM user_db WHERE username= :email AND auth_hash= :confirm;";

        $remove_request = $conn->prepare($sql);
        $remove_request->bindValue(':email', $email);
        $remove_request->bindValue(':confirm', $confirm);
        $remove_request->execute();

        print "<div class=\"alert alert-success\" role=\"alert\">
          <strong>Success</strong> request has been deleted, thank you.
        </div>";
      }
      catch(PDOException $e) {
        $_SESSION['redirect'] = "index.php";
        return_error("Server error, please try again later" . $e->getMessage());
      }

      $conn = null;
    }

  ?>
</body>
</html>
