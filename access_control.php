<?php
  include_once 'common.php';

  $uid = $_SESSION['uid'];
  $pwd = $_SESSION['pwd'];

  if(!isset($uid) || !isset($pwd)) {
    return_to_login("new_account_form.php");
  }

  check_login();

?>
