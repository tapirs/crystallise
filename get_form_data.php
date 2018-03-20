<?php

include_once 'common.php';
include_once 'access_control.php';

  if (isset($_POST['formname'])) {
    $currentForm = $_SESSION["currentForm"] = process_input($_POST['formname']);
  } elseif (isset($_GET['formname'])) {
    $currentForm = $_SESSION["currentForm"] = process_input($_GET['formname']);
  } else {
    return_error("form not found");
  }

  try {

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

    //load list of fields to not display
    $sql = "SELECT COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :mydatabase AND TABLE_NAME = :mytable AND COLUMN_NAME = :mycolumn;";
    $get_skip_list = $conn->prepare($sql);
    $get_skip_list->bindValue(':mydatabase', $dbname);
    $get_skip_list->bindValue(':mytable', $currentForm);
    $get_skip_list->bindValue(':mycolumn', "id");
    $get_skip_list->execute();

    $result = $get_skip_list->fetch();

    $skip_list_keypair = explode('=',$result['COLUMN_COMMENT']);
    $skip_list = explode(',', $skip_list_keypair[1]);

    $sql = "SELECT username, user_id, pupil_ids FROM account_form INNER JOIN user_db ON account_form.user_id=user_db.id WHERE user_db.username= :uid;";

    $get_user = $conn->prepare($sql);
    $get_user->bindValue(':uid', $_SESSION['uid']);
    $get_user->execute();

    $result = $get_user->fetch(PDO::FETCH_ASSOC);

    if ($result['username'] != "") {
      $pupil_ids = $result['pupil_ids'];

      if($currentForm != "pupil_form") {

        if ($pupil_ids == "") {
          echo '</form>';
          return_error("You don't have any pupils assigned to your account, please add some before filling out this form.");
        }

        $pupil_ids = explode(',', $pupil_ids);

        if ($_GET['pupil_id']) {
          $pupil_id = $_GET['pupil_id'];
        } else {
          $pupil_id = $pupil_ids[0];
        }

        echo "<div class=\"form-group\">
          <label class=\"control-label col-sm-2\" for=\"pupil_dropdown\">pupils</label>
          <div class=\"col-sm-10\">
          <select class=\"form-control\" id=\"pupil_dropdown\" name=\"pupil_dropdown\" onchange='updateForm(this.value,\"$currentForm\")' >";
        foreach($pupil_ids as $v) {
          $sql = "SELECT forename, surname FROM pupil_form WHERE id= :id";

          $get_pupil_name = $conn->prepare($sql);
          $get_pupil_name->bindValue(':id', $v);
          $get_pupil_name->execute();

          $v_result = $get_pupil_name->fetch();

          if($v == $pupil_id) {
            echo "<option value=\"$v\" selected>" . $v_result['forename'] . " " . $v_result['surname'] . "</option>";
          } else {
            echo "<option value=\"$v\">" . $v_result['forename'] . " " . $v_result['surname'] . "</option>";
          }
        }
        echo "</select>
         </div>
         </div>";
    }

    $sql = "SELECT * FROM pupil_form LEFT JOIN $currentForm ON pupil_form.id=$currentForm.pupil_id WHERE pupil_form.id= :pupil_id;";

    if ($currentForm == "pupil_form") {
      $sql = "SELECT * FROM pupil_form WHERE id= :pupil_id;";
    }

    $get_column_names = $conn->prepare($sql);
    $get_column_names->bindValue(':pupil_id', $pupil_id);
    $get_column_names->execute();

    $pupil_result = $get_column_names->fetch();

    $colcount = $get_column_names->columnCount();

    for ($i=0; $i < $colcount; $i++) {
      $colarray = $get_column_names->getColumnMeta($i);

      $colname = $colarray['name'];

      //if this column name appears in the skip list then don't render it
      if(in_array($colname, $skip_list)) continue;

      $sql = "SELECT COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :mydatabase AND TABLE_NAME = :mytable AND COLUMN_NAME = :mycolumn;";

      $get_column_comment = $conn->prepare($sql);
      $get_column_comment->bindValue(':mydatabase', $dbname);
      $get_column_comment->bindValue(':mytable', $currentForm);
      $get_column_comment->bindValue(':mycolumn', $colname);
      $get_column_comment->execute();

      $result = $get_column_comment->fetch();

      $type = "text";
      $placeholder = "";
      $required = "true";

      foreach (explode(',',$result['COLUMN_COMMENT']) as $property) {
        $keypair =  explode('=', $property);
          switch ($keypair[0]) {
            case 'type':
              $type = $keypair[1];
              break;
            case 'placeholder':
              $placeholder = $keypair[1];
              break;
            case 'required':
              $required = $keypair[1];
              break;
            default:
              break;
          }
        }

        echo "<div class=\"form-group\">
          <label class=\"control-label col-sm-2\" for=\"$colname\">$colname</label>
          <div class=\"col-sm-10\">";

        $flags = "";
        if($required == "true") {
          $flags = $flags . "required ";
        }
        if($type == "checkbox" && $pupil_result[$colname] == "yes") {
          $flags = $flags . "checked ";
        }

        if($type =="checkbox") {
          //echo "<input type=\"hidden\" class=\"form-control\" id=\"$colname\" name=\"$colname\" value=\"no\" >";
          echo "<input type=\"checkbox\" class=\"form-control\" id=\"$colname\" name=\"$colname\" value=\"$pupil_result[$colname]\" onclick='checkboxClick(this.id)' $flags >";
        } else {
          echo "<input type=\"$type\" class=\"form-control\" id=\"$colname\" name=\"$colname\" value=\"$pupil_result[$colname]\" placeholder=\"$placeholder\" $flags>";
        }

        echo "</div>
        </div>";
      }
    }
  }
  catch(PDOException $e) {
    return_error("Server error, please try again later" . $e->getMessage());

  }


 ?>
