<?php
//count questions in the db and set progress bar
$connection = new PDO($dsn, $username, $password, $options);
$sql = "SELECT MAX(qst_group) as max FROM survey_questions";
$statement = $connection->prepare($sql);
$statement->execute();
$questions_count = $statement->fetch(PDO::FETCH_ASSOC);
$_SESSION['max_qid'] = $questions_count['max'];//prevent questions overflow
$error = false;
if(isset($_POST['recovery'])){
  if(isset($_POST['email'])){
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        
        $email = escape($_POST['email']);
    
        $sql = "SELECT * FROM users WHERE email_address = :email";
    
        $stmt = $connection->prepare($sql);
        
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        //if user exist
        if($stmt->rowCount()==1){
          $_SESSION['user_id'] = $user['passcode'];
          //redirect to report if user has already completed survey
          if($user['last_qid'] >= $_SESSION['max_qid']){
            header("Location:report.php");
          }else{
            //redirect user to survey questions
            $_SESSION['qgroup'] = $user['last_qid'];//will be used to fetch question from db on redirect
            header("Location:i40-readiness-assessement.php");
          }
        }else{
            $error = True;
        }
    } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
    }
  }
  if(isset($_POST['src'])){
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $src = escape($_POST['src']);
        
    
        $sql = "SELECT * FROM users WHERE passcode = :src";
    
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(':src', $src);
        
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        //if user exist
        if($stmt->rowCount()==1){
          $_SESSION['user_id'] = $user['passcode'];
          //redirect to report if user has already completed survey
          if($user['last_qid'] >= $_SESSION['max_qid']){
            header("Location:report.php");
          }else{
            //redirect user to survey questions
            $_SESSION['qgroup'] = $user['last_qid'];//will be used to fetch question from db on redirect
            header("Location: i40-readiness-assessement.php");
          }
          
        }else{
            $error = True;
        }
    } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
    }
  }
  
  
}

?>