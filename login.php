<?php
include_once 'common.php';

  session_start();

  $uid = $_SESSION['uid'];
  $pwd = $_SESSION['pwd'];

  //if(!isset($uid)) {
  //  return_to_login("");
  //}
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

  <div id="loader" name="loader"></div>


  <?php

    $servername = "db711602684.db.1and1.com";
    $username = "dbo711602684";
    $password = "ZAQ!2wsx####";
    $dbname = "db711602684";

    $email = $user_pass = "";



    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
      return_error("Server error, please try again later");

    }

    $email = process_input($_POST["email"]);
    $user_pass = process_input($_POST["pass"]);

    try {
      $sql = "SELECT username FROM user_db WHERE username= :email;";

      $get_user = $conn->prepare($sql);
      $get_user->bindValue(':email', $email);
      $get_user->execute();

      $result = $get_user->fetch(PDO::FETCH_ASSOC);

      if ($result['username'] == "") {
        return_error("Sorry, email address $email not found");
      }

      $sql = "SELECT username, auth_hash FROM user_db WHERE username= :email;";

      $get_user = $conn->prepare($sql);
      $get_user->bindValue(':email', $email);
      $get_user->execute();

      $result = $get_user->fetch(PDO::FETCH_ASSOC);

      if ($result['username'] == $email && $result['auth_hash'] != "null" && password_verify($email . $user_pass, $result['auth_hash'])) {
        $_SESSION['uid'] = $email;
        $_SESSION['pwd'] = $result['auth_hash'];

        if ($_SESSION["redirect"] != "") {
          $redirect = $_SESSION["redirect"];

        }
        return_success("user $email logged in successfully");
      } else {
        return_error("unable to log in, please check your email address and password are correct");
      }
    }
    catch(PDOException $e) {
      return_error("Server error, please try again later" . $e->getMessage());

    }

    $conn = null;
  ?>
</body>
</html>
