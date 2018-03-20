<?php
include_once 'common.php';

  session_start();

  $_SESSION = array();
  session_destroy();

  logged_out("You have logged out.");

?>
