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

  <div id="loader" name="loader"></div>

  <?php
    $servername = "db711602684.db.1and1.com";
    $username = "dbo711602684";
    $password = "ZAQ!2wsx####";
    $dbname = "db711602684";

    $forename =  $surname =  $email = $home_phone = $work_phone = $mobile = $house_number = $house_name = $street_name = $town = $county = $postcode = "";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
      return_error("Server error, please try again later");

    }

    $forename = $_SESSION['forename'] = process_input($_POST["forename"]);
    $surname = $_SESSION['surname'] = process_input($_POST["surname"]);
    $email = $_SESSION['email'] = process_input($_POST["email"]);
    $home_phone = $_SESSION['home_phone'] = process_input($_POST["home_phone"]);
    $work_phone = $_SESSION['work_phone'] = process_input($_POST["work_phone"]);
    $mobile = $_SESSION['mobile'] = process_input($_POST["mobile"]);
    $house_number = $_SESSION['house_number'] = process_input($_POST["house_number"]);
    $house_name = $_SESSION['house_name'] = process_input($_POST["house_name"]);
    $street_name = $_SESSION['street_name'] = process_input($_POST["street_name"]);
    $town = $_SESSION['town'] = process_input($_POST["town"]);
    $county = $_SESSION['county'] = process_input($_POST["county"]);
    $postcode = $_SESSION['postcode'] = process_input($_POST["postcode"]);
    $pupil_names = $_SESSION['pupil_names'] = process_input($_POST["pupil_names"]);

    try {
      $sql = "SELECT username FROM user_db WHERE username= :email;";

      $get_user = $conn->prepare($sql);
      $get_user->bindValue(':email', $email);
      $get_user->execute();

      $result = $get_user->fetch(PDO::FETCH_ASSOC);

      if ($result['username'] != "") {
        //switch this so it takes you back to the register page with all the boxes filled in
        return_error("Sorry, user: $email already exists");
      }

      $sql = "INSERT INTO user_db (username) VALUES (:email);";

      $create_user = $conn->prepare($sql);
      $create_user->bindValue(':email', $email);
      //$create_user->bindValue(':auth_hash', password_hash($email . $user_pass, PASSWORD_BCRYPT));
      $create_user->execute();

      $sql = "SELECT id FROM user_db WHERE username= :email;";
      $get_id = $conn->prepare($sql);
      $get_id->bindValue(':email', $email);
      $get_id->execute();

      $result = $get_id->fetch(PDO::FETCH_ASSOC);

      $user_id = $result['id'];

      //need to check if the pupils with the pupil names exits in the pupils table before continuing

/////////-********99999999999999999999999999      $pupil_ids="1,2";

      $sql = "INSERT INTO `account_form`(`user_id`, `surname`, `forename`, `email`, `home_phone`, `work_phone`, `mobile`, `house_number`, `house_name`, `street_name`, `town`, `county`, `postcode`, `pupil_ids`) VALUES (:user_id, :surname, :forename, :email, :home_phone, :work_phone, :mobile, :house_number, :house_name, :street_name, :town, :county, :postcode, :pupil_ids)";
      $create_account = $conn->prepare($sql);
      $create_account->bindValue(':user_id', $user_id);
      $create_account->bindValue(':surname', $surname);
      $create_account->bindValue(':forename', $forename);
      $create_account->bindValue(':email', $email);
      $create_account->bindValue(':home_phone', $home_phone);
      $create_account->bindValue(':work_phone', $work_phone);
      $create_account->bindValue(':mobile', $mobile);
      $create_account->bindValue(':house_number', $house_number);
      $create_account->bindValue(':house_name', $house_name);
      $create_account->bindValue(':street_name', $street_name);
      $create_account->bindValue(':town', $town);
      $create_account->bindValue(':county', $county);
      $create_account->bindValue(':postcode', $postcode);
      $create_account->bindValue(':pupil_ids', $pupil_ids);
      $create_account->execute();

      $sql = "SELECT username FROM user_db WHERE username= :email;";

      $get_user = $conn->prepare($sql);
      $get_user->bindValue(':email', $email);
      $get_user->execute();

      $result_user = $get_user->fetch(PDO::FETCH_ASSOC);

      $sql = "SELECT email FROM account_form WHERE email= :email;";

      $get_user = $conn->prepare($sql);
      $get_user->bindValue(':email', $email);
      $get_user->execute();

      $result_account = $get_user->fetch(PDO::FETCH_ASSOC);

      if ($result_user['username'] == $email && $result_account['email'] == $email) {
        $_SESSION['redirect'] = "index.php";
        $confirm_parameter = base64_encode(password_hash($email . "this account requires a password", PASSWORD_BCRYPT));

        $sql = "UPDATE user_db SET auth_hash= :confirm WHERE username= :email;";
        $set_pass = $conn->prepare($sql);
        $set_pass->bindValue(':confirm', $confirm_parameter);
        $set_pass->bindValue(':email', $email);
        $set_pass->execute();

        $msg = wordwrap("you recently signed up for an account, please use the link below to confirm your account and create your password.\n\nhttps://www.tapirs-technologies.co.uk/forms/confirm.php?confirm=$confirm_parameter&email=$email\n\nthis link will expire after 24 hours at which point you'll need to create a new account\n\nif this wasn't you then report it here https://www.tapirs-technologies.co.uk/forms/report.php?confirm=$confirm_parameter&email=$email");
        mail($email, "new user account", $msg,  "From: noreply@tapirs-technologies.co.uk");
        return_success("User: $email created successfully, you will shortly recieve an email to confirm your account and create a password");
      }
    }
    catch(PDOException $e) {
      return_error("Server error, please try again later" . $e->getMessage());

    }

    $conn = null;
  ?>
</body>
</html>
