<?php

/**
  * Configuration for database connection
  *
  */
  // $username   = "u130540156_i4r00sdb";
  // $password   = "i4.0r@S2023!";
  // $dbname     = "u130540156_i4r00s";
  
  $username   = "root";
  $password   = "";
  $dbname     = "u130540156_i4r00s";

  $host       = "localhost";
  $dsn        = "mysql:host=$host;dbname=$dbname";
  $options    = array(
                  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                );
              