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
  <script>
    function checkPasswordMatch() {
      var password = document.getElementById('password').value;
      var confirmPassword = document.getElementById('password_confirm').value;
      var ok = true;

      if (password != confirmPassword) {
        document.getElementById('confirm_pass_div').className = "form-group has-danger";
        ok = false;
      } else {
        document.getElementById('confirm_pass_div').className = "form-group";
        ok = true;
      }

      return ok;
    }
  </script>
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
