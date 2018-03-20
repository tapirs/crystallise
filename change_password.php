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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <link href="https://cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css" rel="stylesheet" type="text/css">

  <!-- mailchimp stuff -->
  <style type="text/css">
    #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; width:100%;}
  </style>

  <!-- footer stuff -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">

  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
  <script>
  window.addEventListener("load", function(){
  window.cookieconsent.initialise({
    "palette": {
      "popup": {
        "background": "#eaf7f7",
        "text": "#5c7291"
      },
      "button": {
        "background": "#56cbdb",
        "text": "#ffffff"
      }
    },
    "theme": "classic"
  })});
  </script>
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
