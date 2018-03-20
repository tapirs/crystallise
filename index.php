<?php
include_once 'common.php';

  session_start();

  $uid = $_SESSION['uid'];
  $pwd = $_SESSION['pwd'];

  //if(!isset($uid)) {
  //  return_to_login("update_account.php");
  //}
?>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>tapir-technologies.co.uk - home</title>
  <meta name="description" content="tapirs technologies - agile i.t. - open source, enterprise class and automation for all" />

  <?php readfile('../../../common/head.htm');?>

  <!-- these 2 are for dismissing alers -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
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
  <script type="text/javascript">
    function submitform(form)
    {
      document.forms[form].submit();
    }

    function logout()
    {
      document.forms['logout'].submit();
    }
  </script>
</head>
<body>

  <?php
    if ($_POST["error"] != "") {
      print "<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
          <span aria-hidden=\"true\">&times;</span>
        </button>
        <strong>Error!</strong> " . $_POST["error"] . ".
      </div>";
    }
    if ($_POST["success"] != "") {
      print "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
          <span aria-hidden=\"true\">&times;</span>
        </button>
        <strong>Success</strong> " . $_POST["success"] . ".
      </div>";
    }
    if ($_POST["redirect"] != "") {
      $_SESSION["redirect"] = $_POST["redirect"];
    }
   ?>

   <?php

   if(!isset($uid) || !isset($pwd)) {
     print '<div class="jumbotron text-center" style="margin-bottom:0px">
       <h1 class="display-3">welcome</h1>
       <p class="lead">westfield forms mvp</p>
       <hr class="my-4">
       <p>tapirs technologies and westfield school working together</p>
     </div>';

     echo '<div class="container">';


      print'<p class="card-text">please log into your account by entering your email address and password</p>';
      print'<p class="card-text">register for a new account by clicking the register button below</p>';

      print '<form class="form-horizontal" id="login_form" action="login.php" method="POST" enctype="application/x-www-form-urlencoded">
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">email</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email" placeholder="enter your email address" required>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="pass">password</label>
          <div class="col-sm-10">
            <input id="pass" class="form-control" type="password" maxlength="255" name="pass" placeholder="enter your password here to login"/>
          </div>
        </div>
      </form>
      <form class="form-horizontal" id="register_form" action="account_form.php" method="POST" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="formname" value="account_form">
      </form>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default" form="login_form">login</button>
          <button type="submit" class="btn btn-default" form="register_form">register</button>
        </div>
      </div>';
    } else {
      echo '<div class="container">';

      readfile('../../../common/navbar.htm');

      print '<H1>list of forms</H1>';

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


        echo '<div class="list-group">';

        foreach ($result as $form) {
          $form = $form['TABLE_NAME'];
          $formname = str_replace("_"," ",$form);
          $formname = str_replace(" form", "", $formname);
          $action = "form.php";
          if($formname != "account") {

            print "<form id=\"$form\" action=\"$action\" method=\"post\">
              <input type=\"hidden\" name=\"formname\" value=\"$form\">
              <a href=\"javascript: submitform('$form')\" class=\"list-group-item list-group-item-action\">$formname</a>
            </form>";
          }
        }

        echo '</div>';
      }
      catch(PDOException $e) {
        return_error("Server error, please try again later" . $e->getMessage());

      }
    }

    echo '</div>';

  ?>


</body>
