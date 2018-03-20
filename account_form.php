<?php
include_once 'common.php';
//include_once 'access_control.php';

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
  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/images/apple-touch-icon-57x57.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/images/apple-touch-icon-114x114.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/images/apple-touch-icon-144x144.png" />
  <link rel="apple-touch-icon-precomposed" sizes="60x60" href="/images/apple-touch-icon-60x60.png" />
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/images/apple-touch-icon-120x120.png" />
  <link rel="apple-touch-icon-precomposed" sizes="76x76" href="/images/apple-touch-icon-76x76.png" />
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/images/apple-touch-icon-152x152.png" />
  <link rel="icon" type="image/png" href="/images/favicon-196x196.png" sizes="196x196" />
  <link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="/images/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="/images/favicon-16x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="/images/favicon-128.png" sizes="128x128" />
  <meta name="application-name" content="&nbsp;"/>
  <meta name="msapplication-TileColor" content="#FFFFFF" />
  <meta name="msapplication-TileImage" content="/images/mstile-144x144.png" />
  <meta name="msapplication-square70x70logo" content="/images/mstile-70x70.png" />
  <meta name="msapplication-square150x150logo" content="/images/mstile-150x150.png" />
  <meta name="msapplication-wide310x150logo" content="/images/mstile-310x150.png" />
  <meta name="msapplication-square310x310logo" content="/images/mstile-310x310.png" />


  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/bootstrap_override.css">

  <!-- these 2 are for dismissing alers -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>

</head>
<body>

    <?php
    if (isset($_POST['formname'])) {
      $currentForm = $_SESSION['currentForm'] = process_input($_POST['formname']);
    } else {
      return_error("form not found");
    }
   ?>

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

     $forename = $_SESSION['forename'];
     $surname = $_SESSION['surname'];
     $email = $_SESSION['email'];
     $home_phone = $_SESSION['home_phone'];
     $work_phone = $_SESSION['work_phone'];
     $mobile = $_SESSION['mobile'];
     $house_number = $_SESSION['house_number'];
     $house_name = $_SESSION['house_name'];
     $street_name = $_SESSION['street_name'];
     $town = $_SESSION['town'];
     $county = $_SESSION['county'];
     $postcode = $_SESSION['postcode'];
     $pupil_names = $_SESSION['pupil_names'];

     $updateCreate = "create";

     readfile('navbar.htm');
     echo '<div class="container">';

     try {
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

       $sql = "SELECT * FROM $currentForm;";

       $get_column_names = $conn->prepare($sql);
       $get_column_names->execute();

       $colcount = $get_column_names->columnCount();
     }
     catch(PDOException $e) {
       return_error("Server error, please try again later" . $e->getMessage());

     }

     echo '<form class="form-horizontal" action="update_account.php" method="POST" enctype="application/x-www-form-urlencoded">';

     try {

       //check if this account already exists to see if we pull back details
       $sql = "SELECT email FROM account_form WHERE email= :email;";

       $check_account = $conn->prepare($sql);
       $check_account->bindValue(':email',  $_SESSION['uid']);
       $check_account->execute();

       $result = $check_account->fetch();

       if($result['email'] ==  $_SESSION['uid']) {
         $account_exists = true;
       } else {
         $account_exists = false;
       }

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

         foreach (explode(',',$result['COLUMN_COMMENT']) as $property) {
           $updateCreate = "update";
           $keypair =  explode('=', $property);
             switch ($keypair[0]) {
               case 'type':
                 $type = $keypair[1];
                 break;
                 case 'placeholder':
                   $placeholder = $keypair[1];
                   break;
               default:
                 break;
           }
         }

         $colname_with_spaces = str_replace("_", " ", $colname);

         $value = ${$colname};

         if($account_exists && $value == "") {
           $sql = "SELECT $colname FROM account_form WHERE email= :email;";

           $get_value = $conn->prepare($sql);
           $get_value->bindValue(':email', $_SESSION['uid']);
           $get_value->execute();

           $result = $get_value->fetch();

           $value = $result["$colname"];
         }

         //to do-check column to see if it not_null, if not then set below to required

         if($type == "dropdown") {
           $values = explode(',', $value);
           echo "<div class=\"form-group\">
             <label class=\"control-label col-sm-2\" for=\"$colname\">$colname_with_spaces</label>
             <div class=\"col-sm-10\">
               <select class=\"form-control\" id=\"$colname\" name=\"$colname\" size=\"3\" >";
               foreach($values as $v) {
                 $sql = "SELECT forename, surname FROM pupil_form WHERE id= :id";

                 $get_pupil_name = $conn->prepare($sql);
                 $get_pupil_name->bindValue(':id', $v);
                 $get_pupil_name->execute();

                 $v_result = $get_pupil_name->fetch();

                 echo "<option value=\"$v\">" . $v_result['forename'] . " " . $v_result['surname'] . "</option>";
               }
              echo "</select>
             </div>
           </div>";
         } else {
           echo "<div class=\"form-group\">
             <label class=\"control-label col-sm-2\" for=\"$colname\">$colname_with_spaces</label>
             <div class=\"col-sm-10\">
               <input type=\"$type\" class=\"form-control\" id=\"$colname\" name=\"$colname\" value=\"$value\" placeholder=\"$placeholder\" >
             </div>
           </div>";
         }
       }
     }
     catch(PDOException $e) {
       return_error("Server error, please try again later" . $e->getMessage());

     }

     echo "<div class=\"form-group\">
         <div class=\"col-sm-offset-2 col-sm-10\">
           <button type=\"submit\" class=\"btn btn-default\">$updateCreate</button>
         </div>
       </div>
     </form>";

     echo '</div>';
    ?>
</body>
</html>
