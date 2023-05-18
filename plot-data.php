<?php
require "config.php";

try {
    $userid = "";
    if(isset($_GET['src'])){
      $userid = $_GET['src'];
    }else{
      $userid = $_SESSION['user_id'];
    }
    $connection = new PDO($dsn, $username, $password, $options);
  
    //$sql = "SELECT customization,digital_feature,data_driven_service,share_revenue,data_usage,overall FROM responses WHERE user_id = '$user_id'";
    $sql = "SELECT dimension, AVG(response) AS score FROM survey_responses WHERE userid = '$userid' GROUP BY dimension";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);

    $values = array();
    $labels = array();
    foreach($data as $value){
      array_push($values,round($value['score']));
      array_push($labels,$value['dimension']);
    }

    $data_values = json_encode(array_values($values),JSON_NUMERIC_CHECK);
    $data_labels = json_encode(array_values($labels));

  //ORGANISATION
  $sql = "SELECT sub_dimension, AVG(response) AS score FROM survey_responses WHERE userid = '$userid' AND dimension = 'Organisation' GROUP BY sub_dimension";
  $statement_org = $connection->prepare($sql);
  $statement_org->execute();
  $org_results = $statement_org->fetchAll(PDO::FETCH_ASSOC);
  $org_values = array();
  $org_labels = array();
  foreach($org_results as $value){
    array_push($org_values,round($value['score']));
    array_push($org_labels,$value['sub_dimension']);
  }
  $org_data = json_encode(array_values($org_values),JSON_NUMERIC_CHECK);
  $org_sub = json_encode(array_values($org_labels));

  //PEOPLE
  $sql_people = "SELECT sub_dimension, AVG(response) AS score FROM survey_responses WHERE userid = '$userid' AND dimension = 'People' GROUP BY sub_dimension";
  $statement_people = $connection->prepare($sql_people);
  $statement_people->execute();
  $people_results = $statement_people->fetchAll(PDO::FETCH_ASSOC);
  $people_values = array();
  $people_labels = array();
  foreach($people_results as $value){
    array_push($people_values,round($value['score']));
    array_push($people_labels,$value['sub_dimension']);
  }
  $people_data = json_encode(array_values($people_values),JSON_NUMERIC_CHECK);
  $people_sub = json_encode(array_values($people_labels));

  //TECHNOLOGY/IT SOLUTIONS
  $sql_tech = "SELECT sub_dimension, AVG(response) AS score FROM survey_responses WHERE userid = '$userid' AND dimension = 'Industry 4.0 Solutions' GROUP BY sub_dimension";
  $statement_tech = $connection->prepare($sql_tech);
  $statement_tech->execute();
  $tech_results = $statement_tech->fetchAll(PDO::FETCH_ASSOC);
  $tech_values = array();
  $tech_labels = array();
  foreach($tech_results as $value){
    array_push($tech_values,round($value['score']));
    array_push($tech_labels,$value['sub_dimension']);
  }
  $tech_data = json_encode(array_values($tech_values),JSON_NUMERIC_CHECK);
  $tech_sub = json_encode(array_values($tech_labels));

  //PROCESS OPERATIONS MAINTENANCE
  $sql_pom = "SELECT sub_dimension, AVG(response) AS score FROM survey_responses WHERE userid = '$userid' AND dimension = 'Processes, Operations and Maintenance' GROUP BY sub_dimension";
  $statement_pom = $connection->prepare($sql_pom);
  $statement_pom->execute();
  $pom_results = $statement_pom->fetchAll(PDO::FETCH_ASSOC);
  $pom_values = array();
  $pom_labels = array();
  foreach($pom_results as $value){
    array_push($pom_values,round($value['score']));
    array_push($pom_labels,$value['sub_dimension']);
  }
  $pom_data = json_encode(array_values($pom_values),JSON_NUMERIC_CHECK);
  $pom_sub = json_encode(array_values($pom_labels));

   //SUSTAINABILITY
   $sql_sus = "SELECT sub_dimension, AVG(response) AS score FROM survey_responses WHERE userid = '$userid' AND dimension = 'Sustainability (Environment)' GROUP BY sub_dimension";
   $statement_sus = $connection->prepare($sql_sus);
   $statement_sus->execute();
   $sus_results = $statement_sus->fetchAll(PDO::FETCH_ASSOC);
   $sus_values = array();
   $sus_labels = array();
   foreach($sus_results as $value){
     array_push($sus_values,round($value['score']));
     array_push($sus_labels,$value['sub_dimension']);
   }
   array_push($sus_values,0);
   array_push($sus_labels,"Others (Negligible)");
   $sus_data = json_encode(array_values($sus_values),JSON_NUMERIC_CHECK);
   $sus_sub = json_encode(array_values($sus_labels));

   
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}

function progress_bar_color($dimension){
  if($dimension=="Organisation"){
    return 'progress-bar bg-primary';
  }
  if($dimension=="Industry 4.0 Solutions"){
    return 'progress-bar bg-danger';
  }
  if($dimension=="People"){
    return 'progress-bar bg-warning';
  }
  if($dimension=="Processes, Operations and Maintenance"){
    return 'progress-bar bg-secondary';
  }
  if($dimension=="Sustainability (Environment)"){
    return 'progress-bar bg-info';
  }
}


?>