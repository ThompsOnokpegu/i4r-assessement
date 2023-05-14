<?php

/**
  * Configuration for database connection
  *
  */
  $password   = "i4.0r@S2023!";
  $dbname     = "u130540156_i4r00s";
  $host       = "localhost";
  $username   = "root";
  //$password   = "";
  //$dbname     = "product_readiness_upc_spdc";
  $dsn        = "mysql:host=$host;dbname=$dbname";
  $options    = array(
                  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                );
              