<?php
include "config.php";
session_start();

//Save user response if NEXT button is clicked
if(isset($_POST['save_update'])){
    try{
      //establish database connection
      $conn = new PDO($dsn,$username,$password,$options);
  
      $last_qid = $_POST['last_qid'];
      $last_qst = $_POST['last_qst'];
      $count_qids = $_POST['count_qids'];
    
      //Check if update is required instead of inserting new record
      $update_is_required = FALSE;
      for ($i=0; $i <= $count_qids; $i++) {
        //check if any of the questions were previously answered 
        $update_is_required = isset($_SESSION['qid'.($last_qid-$i)]);
        //exit loop if you find any
        if($update_is_required==TRUE){
            break;
        }
      }

      $total = 0;
      $counter=0;
      for ($i=0; $i <= $count_qids; $i++) {
        //check whether any of the responses to this question is NA
        if($_POST[$last_qid-$i] == 5){
          $total = 5;//NA values are saved as 5
          $counter = 1;
          break;
        }
        //accumulate total score for this question 
        $total = $total + $_POST[$last_qid-$i];
        //REMEMBER user choices when the PREVIOUS button is clicked
        $_SESSION["qid".($last_qid-$i)] = $_POST[$last_qid-$i];
        $counter++;

      }
      
      //calculates average score for the current question
      $score = $total/$counter;

      //create object for sql statement
      $response = array(
        "userid" => $_SESSION['user_id'],
        "qgroup" => $_POST['q_group'],
        "response" => $score,
        "dimension" => $_POST['dimension'],
        "sub_dimension" => $_POST['subdimension']
      );
      
      if($update_is_required){
        //PREVENT update when question is a Dimension Header
        if($_POST['subdimension']=="header"){
          //increment question_id for the NEXT question
          if($last_qst < $_SESSION['max_qid']){
            $_SESSION['qgroup'] = $_POST['q_group'] + 1;
          }
          //redirect and build the next question
          header("Location: i40-readiness-assessement.php"); 
        }else{
          //UPDATE response
          $sql = "UPDATE survey_responses SET response = :response,dimension = :dimension,sub_dimension = :sub_dimension WHERE userid = :userid AND qgroup = :qgroup";
          $stmnt = $conn->prepare($sql);
          if($stmnt->execute($response)){
            if($last_qst >= $_SESSION['max_qid']){ 
              header("Location: report.php");
            }
            //increment question_id for the NEXT question
            if($last_qst < $_SESSION['max_qid']){             
              $_SESSION['qgroup'] = $_POST['q_group'] + 1;
              //redirect and build the next question
              header("Location: i40-readiness-assessement.php"); 
            }  
          }
        }
        
        
      }else{

        //PREVENT Zero insertion when question is a Dimension Header
        if($_POST['subdimension']=="header"){
          //increment question_id for the NEXT question
          if($last_qst < $_SESSION['max_qid']){
            $_SESSION['qgroup'] = $_POST['q_group'] + 1;
          }
          //redirect and build the next question
          header("Location: i40-readiness-assessement.php"); 
        }else{
          //INSERT new response
          $sql = sprintf("INSERT INTO %s (%s) values (%s)","survey_responses",implode(", ", array_keys($response)),":" . implode(", :", array_keys($response)));
          $stm = $conn->prepare($sql);
          if($stm->execute($response)){
           
            //UPDATE USER last_qid
            $user = array(
              'passcode' => $_SESSION['user_id'],
              'last_qid' => $last_qst
            );
            $usql = "UPDATE users SET last_qid = :last_qid WHERE passcode = :passcode";
            $ustmt = $conn->prepare($usql);
            $ustmt->execute($user);

            if($last_qst >= $_SESSION['max_qid']){ 
              header("Location: report.php");
            }
            //increment question_id for the NEXT question
            if($last_qst < $_SESSION['max_qid']){             
              $_SESSION['qgroup'] = $_POST['q_group'] + 1;
              //redirect and build the next question
              header("Location: i40-readiness-assessement.php"); 
            }  
          }
        }
      }

    }catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
    }
}

//When PREVIOUS button is clicked
if(isset($_POST['retrieve'])){
    try{
        //decreament question_id by 1
        if(($_POST['q_group'] - 1) > 0){
            $_SESSION['qgroup'] = $_POST['q_group'] - 1;
        }
        
        //reload page
        header("Location: i40-readiness-assessement.php");
    }catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

?>