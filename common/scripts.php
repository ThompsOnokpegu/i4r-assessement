<?php
session_start();

if (empty($_SESSION['csrf'])) {
  if (function_exists('random_bytes')) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
  } else if (function_exists('mcrypt_create_iv')) {
    $_SESSION['csrf'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
  } else {
    $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
}

define('CUSTOMIZATION',10);
define('DATA_USAGE',25);
define('SHARE_REVENUE',40);
define('DIGITAL_FEATURE',55);
define('DATA_DRIVEN_SERVICE',70);
define('OVERALL',85);

function steps($page){
  if($page == 101){
    return CUSTOMIZATION;
  }  
  if($page == 102){
    return DATA_USAGE;
  } 
  if($page == 103){
    return SHARE_REVENUE;
  }  
  if($page == 104){
    return DIGITAL_FEATURE;
  }  
  if($page == 105){
    return DATA_DRIVEN_SERVICE;
  }  
  if($page == 106){
    return OVERALL;
  }  
}

function escape($html) {
  return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function srcode(){
    $_mon = date("m");
    $_day = date('d');
    $_sec = date("s");
    return strtoupper($_sec.$_day.substr($_sec+34,0,2));
}


?>