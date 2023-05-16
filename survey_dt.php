<?php

require "config.php";

try {
  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT fullname,email_address,industry,passcode,last_qid FROM users";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
  $users = array();
  foreach ($result as $user) {
      array_push($user,'<a type="button" class="btn btn-info btn-xs" href="report.php?src='.$user[3].'">Report</a>');
      $users[] = $user;
  }
  echo json_encode($users);
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}

?>