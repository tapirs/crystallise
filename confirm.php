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

  <!-- favicon pointers -->
  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="../images/apple-touch-icon-57x57.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../images/apple-touch-icon-114x114.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../images/apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../images/apple-touch-icon-144x144.png" />
  <link rel="apple-touch-icon-precomposed" sizes="60x60" href="../images/apple-touch-icon-60x60.png" />
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="../images/apple-touch-icon-120x120.png" />
  <link rel="apple-touch-icon-precomposed" sizes="76x76" href="../images/apple-touch-icon-76x76.png" />
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="../images/apple-touch-icon-152x152.png" />
  <link rel="icon" type="image/png" href="../images/favicon-196x196.png" sizes="196x196" />
  <link rel="icon" type="image/png" href="../images/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="../images/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="../images/favicon-16x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="../images/favicon-128.png" sizes="128x128" />
  <meta name="application-name" content="&nbsp;"/>
  <meta name="msapplication-TileColor" content="#FFFFFF" />
  <meta name="msapplication-TileImage" content="../images/mstile-144x144.png" />
  <meta name="msapplication-square70x70logo" content="../images/mstile-70x70.png" />
  <meta name="msapplication-square150x150logo" content="../images/mstile-150x150.png" />
  <meta name="msapplication-wide310x150logo" content="../images/mstile-310x150.png" />
  <meta name="msapplication-square310x310logo" content="../images/mstile-310x310.png" />


  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/bootstrap_override.css">

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
