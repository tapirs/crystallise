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

    foreach($_POST as $key => $value) {
      ${$key} = process_input($value);
    }

    try {
      //load list of fields to not display
      $sql = "SELECT COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :mydatabase AND TABLE_NAME = :mytable AND COLUMN_NAME = :mycolumn;";
      $get_skip_list = $conn->prepare($sql);
      $get_skip_list->bindValue(':mydatabase', $dbname);
      $get_skip_list->bindValue(':mytable', $_SESSION['currentForm']);
      $get_skip_list->bindValue(':mycolumn', "id");
      $get_skip_list->execute();

      $result = $get_skip_list->fetch();

      $skip_list_keypair = explode('=',$result['COLUMN_COMMENT']);
      $skip_list = explode(',', $skip_list_keypair[1]);

      $sql = "SELECT username, user_id, pupil_ids FROM account_form INNER JOIN user_db ON user_db.id=account_form.user_id WHERE user_db.username= :uid;";

      $get_user = $conn->prepare($sql);
      $get_user->bindValue(':uid', $_SESSION['uid']);
      $get_user->execute();

      $result = $get_user->fetch(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e) {
      return_error("Server error, please try again later" . $e->getMessage());

    }

    if ($_SESSION["currentForm"] == "pupil_form") {
      try {
        $pupil_exists = false;

        if ($result['username'] != "") {
          $pupil_ids = $result['pupil_ids'];

          $sql = "SELECT id, surname, forename FROM pupil_form WHERE user_id= :uid;";

          $get_pupil = $conn->prepare($sql);
          $get_pupil->bindValue(':uid', $result['user_id']);
          $get_pupil->execute();

          $get_pupil->setFetchMode(PDO::FETCH_ASSOC);

          foreach($get_pupil->fetchAll() as $pupil) {
            if($pupil['surname'] == ucwords(strtolower($surname)) and $pupil['forename'] == ucwords(strtolower($forename))) {
              $pupil_exists = true;
            }
          }
        }

        if($pupil_exists) {
          return_error("pupil: " . $forename ." " . $surname . " already exists");
        } else {

          $sql = "SELECT * FROM " . $_SESSION['currentForm'] . ";";

          $get_column_names = $conn->prepare($sql);
          $get_column_names->execute();

          $colcount = $get_column_names->columnCount();
          $colnamearray = array();

          for ($i=0; $i < $colcount; $i++) {
            $colarray = $get_column_names->getColumnMeta($i);

            $colname = $colarray['name'];

            //if this column name appears in the skip list then don't render it
            if(in_array($colname, $skip_list)) continue;

            array_push($colnamearray, $colname);
          }

          $colname_toinsert = implode(',', $colnamearray);
          $colname_values = implode(',:', $colnamearray);


          $sql = "INSERT INTO pupil_form (user_id,$colname_toinsert) VALUES (:uid,:$colname_values);";

          $create_pupil = $conn->prepare($sql);
          $create_pupil->bindValue(':uid', $result['user_id']);

          foreach($colnamearray as $col) {
            $create_pupil->bindValue(":$col", ucwords(strtolower(${$col})));
          }

          $create_pupil->execute();

          $sql = "SELECT id FROM pupil_form WHERE forename= :forename AND surname= :surname;";

          $get_new_pupil = $conn->prepare($sql);
          $get_new_pupil->bindValue(':forename', $forename);
          $get_new_pupil->bindValue(':surname', $surname);
          $get_new_pupil->execute();

          $new_pupil = $get_new_pupil->fetch();

          if ($pupil_ids == "") {
            $pupil_ids = $new_pupil['id'];
          } else {
            $pupil_ids = join(",",[$pupil_ids, $new_pupil['id']]);
          }

          $sql = "UPDATE account_form SET pupil_ids='" . $pupil_ids . "' WHERE user_id=" . $result['user_id'] . ";";

          $update_pupil_id = $conn->prepare($sql);
          $update_pupil_id->execute();

          return_success("pupil: $forename $surname successfully added");
        }
        //         $sql = "SELECT COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :mydatabase AND TABLE_NAME = :mytable AND COLUMN_NAME = :mycolumn;";
        //
        //         $get_column_comment = $conn->prepare($sql);
        //         $get_column_comment->bindValue(':mydatabase', $dbname);
        //         $get_column_comment->bindValue(':mytable', $currentForm);
        //         $get_column_comment->bindValue(':mycolumn', $colname);
        //         $get_column_comment->execute();
        //
        //         $result = $get_column_comment->fetch();
        //
        //         $type = "text";
        //         $placeholder = "";
        //
        //         foreach (explode(',',$result['COLUMN_COMMENT']) as $property) {
        //           $keypair =  explode('=', $property);
        //             print $key;
        //             print $value;
        //             switch ($keypair[0]) {
        //               case 'type':
        //                 $type = $keypair[1];
        //                 break;
        //                 case 'placeholder':
        //                   $placeholder = $keypair[1];
        //                   break;
        //               default:
        //                 break;
        //           }
        //         }
        //
        //         echo "<div class=\"form-group\">
        //           <label class=\"control-label col-sm-2\" for=\"$colname\">$colname</label>
        //           <div class=\"col-sm-10\">
        //             <input type=\"$type\" class=\"form-control\" id=\"$colname\" name=\"$colname\" value=\"${$colname}\" placeholder=\"$placeholder\" required>
        //           </div>
        //         </div>";
        //       }
        //     }
        //   }
        // }
        //
        // $sql = "INSERT INTO user_db (username) VALUES (:email);";
        //
        // $create_user = $conn->prepare($sql);
        // $create_user->bindValue(':email', $email);
        // //$create_user->bindValue(':auth_hash', password_hash($email . $user_pass, PASSWORD_BCRYPT));
        // $create_user->execute();
        //
        // $sql = "SELECT id FROM user_db WHERE username= :email;";
        // $get_id = $conn->prepare($sql);
        // $get_id->bindValue(':email', $email);
        // $get_id->execute();
        //
        // $result = $get_id->fetch(PDO::FETCH_ASSOC);
        //
        // $user_id = $result['id'];
        //
        // //need to check if the pupils with the pupil names exits in the pupils table before continuing
        //
        // $pupil_ids="1,2";
        //
        // $sql = "INSERT INTO `account_form`(`user_id`, `surname`, `forename`, `email`, `home_phone`, `work_phone`, `mobile`, `house_number`, `house_name`, `street_name`, `town`, `county`, `postcode`, `pupil_ids`) VALUES (:user_id, :surname, :forename, :email, :home_phone, :work_phone, :mobile, :house_number, :house_name, :street_name, :town, :county, :postcode, :pupil_ids)";
        // $create_account = $conn->prepare($sql);
        // $create_account->bindValue(':user_id', $user_id);
        // $create_account->bindValue(':surname', $surname);
        // $create_account->bindValue(':forename', $forename);
        // $create_account->bindValue(':email', $email);
        // $create_account->bindValue(':home_phone', $home_phone);
        // $create_account->bindValue(':work_phone', $work_phone);
        // $create_account->bindValue(':mobile', $mobile);
        // $create_account->bindValue(':house_number', $house_number);
        // $create_account->bindValue(':house_name', $house_name);
        // $create_account->bindValue(':street_name', $street_name);
        // $create_account->bindValue(':town', $town);
        // $create_account->bindValue(':county', $county);
        // $create_account->bindValue(':postcode', $postcode);
        // $create_account->bindValue(':pupil_ids', $pupil_ids);
        // $create_account->execute();
        //
        // $sql = "SELECT username FROM user_db WHERE username= :email;";
        //
        // $get_user = $conn->prepare($sql);
        // $get_user->bindValue(':email', $email);
        // $get_user->execute();
        //
        // $result_user = $get_user->fetch(PDO::FETCH_ASSOC);
        //
        // $sql = "SELECT email FROM account_form WHERE email= :email;";
        //
        // $get_user = $conn->prepare($sql);
        // $get_user->bindValue(':email', $email);
        // $get_user->execute();
        //
        // $result_account = $get_user->fetch(PDO::FETCH_ASSOC);
        //
        // if ($result_user['username'] == $email && $result_account['email'] == $email) {
        //   $_SESSION['redirect'] = "index.php";
        //   $confirm_parameter = base64_encode(password_hash($email . "this account requires a password", PASSWORD_BCRYPT));
        //
        //   $sql = "UPDATE user_db SET auth_hash= :confirm WHERE username= :email;";
        //   $set_pass = $conn->prepare($sql);
        //   $set_pass->bindValue(':confirm', $confirm_parameter);
        //   $set_pass->bindValue(':email', $email);
        //   $set_pass->execute();
        //
        //   $msg = wordwrap("you recently signed up for an account, please use the link below to confirm your account and create your password.\n\nhttps://www.tapirs-technologies.co.uk/forms/confirm.php?confirm=$confirm_parameter&email=$email\n\nthis link will expire after 24 hours at which point you'll need to create a new account\n\nif this wasn't you then report it here https://www.tapirs-technologies.co.uk/forms/report.php?confirm=$confirm_parameter&email=$email");
        //   mail($email, "new user account", $msg,  "From: noreply@tapirs-technologies.co.uk");
        //   return_success("User: $email created successfully, you will shortly recieve an email to confirm your account and create a password");
        // }
      }
      catch(PDOException $e) {
        return_error("Server error, please try again later. " . $e->getMessage());
        //print $e->getMessage();
      }
    } else {
      try {
        $form_data_exists = false;

        if ($result['username'] != "") {
          $pupil_id = $pupil_dropdown;

          $sql = "SELECT id FROM  " . $_SESSION["currentForm"] . " WHERE user_id= :uid AND pupil_id= :pupil_id;";

          $get_form = $conn->prepare($sql);
          $get_form->bindValue(':uid', $result['user_id']);
          $get_form->bindValue(':pupil_id', $pupil_id);
          $get_form->execute();

          $get_form->setFetchMode(PDO::FETCH_ASSOC);

          $this_form = $get_form->fetch();

          if(!empty($this_form)) {
            $form_data_exists = true;
          }

          $sql = "SELECT * FROM " . $_SESSION['currentForm'] . ";";

          $get_column_names = $conn->prepare($sql);
          $get_column_names->execute();

          $colcount = $get_column_names->columnCount();
          $colnamearray = array();

          for ($i=0; $i < $colcount; $i++) {
            $colarray = $get_column_names->getColumnMeta($i);

            $colname = $colarray['name'];

            //if this column name appears in the skip list then don't render it
            if(in_array($colname, $skip_list)) continue;

            array_push($colnamearray, $colname);
          }

          if ($form_data_exists) {

            $update_string = "";
            foreach($colnamearray as $col) {
              if($update_string == "") {
                $update_string = "$col= :$col";
              } else {
                $update_string = join(",", [$update_string, "$col= :$col"]);
              }
            }


            $sql = "UPDATE " . $_SESSION["currentForm"] . " SET $update_string WHERE user_id= :uid AND pupil_id= :pupil_id;";

            $manage_form = $conn->prepare($sql);
            $manage_form->bindValue(':uid', $result['user_id']);
            $manage_form->bindValue(':pupil_id', $pupil_id);

            foreach($colnamearray as $col) {
              $manage_form->bindValue(":$col", ${$col});
            }

            $manage_form->execute();


          } else {
            $colname_toinsert = implode(',', $colnamearray);
            $colname_values = implode(',:', $colnamearray);

            $sql = "INSERT INTO " . $_SESSION["currentForm"] . " (user_id,pupil_id,$colname_toinsert) VALUES (:uid,:pupil_id,:$colname_values);";

            $manage_form = $conn->prepare($sql);
            $manage_form->bindValue(':uid', $result['user_id']);
            $manage_form->bindValue(':pupil_id', $pupil_id);

            foreach($colnamearray as $col) {
              $manage_form->bindValue(":$col", ${$col});
            }

            $manage_form->execute();
          }

          return_success("Form sorted");
        }
      }
      catch(PDOException $e) {
        return_error("Server error, please try again later. " . $e->getMessage());
      }
    }

    $conn = null;
  ?>
</body>
</html>
