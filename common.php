<?php

  session_start();

  function process_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
  }

  function return_error($error) {
    if ($_SESSION['redirect'] != "") {
      $redirect = $_SESSION['redirect'];
    } else {
      $redirect = "index.php";
    }
    if ($_SESSION['formname'] != "") {
      $formname = $_SESSION['formname'];
    } else {
      $formname = "";
    }
    echo "<form id=\"error_form\" action=\"$redirect\" method=\"post\">
    <input type=\"hidden\" name=\"error\" value=\"$error\">
    <input type=\"hidden\" name=\"formname\" value=\"$formname\">
    </form>

    <script type=\"text/javascript\">
      document.getElementById('error_form').submit();
    </script>";
  }

  function return_success($success) {
    if ($_SESSION['redirect'] != "") {
      $redirect = $_SESSION['redirect'];
    } else {
      $redirect = "index.php";
    }
    if ($_SESSION['formname'] != "") {
      $formname = $_SESSION['formname'];
    } else {
      $formname = "";
    }
    echo "<form id=\"myForm\" action=\"$redirect\" method=\"post\">
      <input type=\"hidden\" name=\"success\" value=\"$success\">
      <input type=\"hidden\" name=\"formname\" value=\"$formname\">

    </form>

    <script type=\"text/javascript\">
      document.getElementById('myForm').submit();
    </script>";
  }

  function return_to_login($redirect) {
    if ($_SESSION['redirect'] != "") {
      $redirect = $_SESSION['redirect'];
    } elseif ($redirect == "") {
      $redirect = "index.php";
    }
    if ($_SESSION['formname'] != "") {
      $formname = $_SESSION['formname'];
    } else {
      $formname = "";
    }
    echo "<form id=\"loginForm\" action=\"index.php\" method=\"post\">
    <input type=\"hidden\" name=\"error\" value=\"Please login before continuing.\">
    <input type=\"hidden\" name=\"redirect\" value=\"$redirect\">
    <input type=\"hidden\" name=\"formname\" value=\"$formname\">
    </form>

    <script type=\"text/javascript\">
      document.getElementById('loginForm').submit();
    </script>";
  }

  function logged_out() {
    echo "<form id=\"logoutForm\" action=\"index.php\" method=\"post\">
    <input type=\"hidden\" name=\"success\" value=\"You have logged out\">

    </form>

    <script type=\"text/javascript\">
      document.getElementById('logoutForm').submit();
    </script>";
  }

  function check_login() {
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
      $sql = "SELECT username, auth_hash FROM user_db WHERE username= :email AND auth_hash= :auth_hash";

      $get_user = $conn->prepare($sql);
      $get_user->bindValue(':email', $_SESSION['uid']);
      $get_user->bindValue(':auth_hash', $_SESSION['pwd']);
      $get_user->execute();

      $result = $get_user->fetch(PDO::FETCH_ASSOC);

      if ($result['username'] == $_SESSION['uid']) {
        return true;
      } else {
        return false;
      }
    }
    catch(PDOException $e) {
      return_error("Server error, please try again later" . $e->getMessage());
    }
  }
?>
