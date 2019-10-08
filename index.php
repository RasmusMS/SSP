<?php
  require_once "includes/header.php";

  if(file_exists("pages/$page")){
     include_once "pages/$page";
 }
 else {
     include_once 'pages/404.php';
 }
 ?>
