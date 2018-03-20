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
<body>

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
   ?>

   <?php

      $pwd = $_POST['password'];
      $email = $_POST['email'];

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
        $auth_hash = password_hash($email . $pwd, PASSWORD_BCRYPT);

        $sql = "UPDATE user_db SET auth_hash= :confirm WHERE username= :email;";
        $set_pass = $conn->prepare($sql);
        $set_pass->bindValue(':confirm', $auth_hash);
        $set_pass->bindValue(':email', $email);
        $set_pass->execute();

        $sql = "SELECT username, auth_hash FROM user_db WHERE username= :email;";

        $get_user = $conn->prepare($sql);
        $get_user->bindValue(':email', $email);
        $get_user->execute();

        $result = $get_user->fetch(PDO::FETCH_ASSOC);

        if ($result['username'] == $email && $result['auth_hash'] != "null" && password_verify($email . $pwd, $result['auth_hash'])) {
          $_SESSION['uid'] = $email;
          $_SESSION['pwd'] = $result['auth_hash'];

          $_SESSION['redirect'] = "index.php";
          return_success("user $email logged in successfully");
        } else {
          $_SESSION = array();
          $_SESSION['redirect'] = "index.php";
          return_error("unable to log in, please check your email address and password are correct");
        }
      }
      catch(PDOException $e) {
        return_error("Server error, please try again later" . $e->getMessage());

      }

  ?>


</body>
