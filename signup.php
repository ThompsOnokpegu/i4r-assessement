<?php 
require "common/scripts.php";
require "config.php";
$passcode = "SRC".srcode();
$error = false;
if(isset($_POST['survey_signup'])){

  try{

    $conn = new PDO($dsn,$username,$password,$options);

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $industry = $_POST['industry'];
    $passcode = $_POST['passcode'];

    $query = "SELECT email_address FROM users WHERE email_address=:email_address";
    $st = $conn->prepare($query);
    $st->bindValue(':email_address',$email);
    $st->execute();
    
    if($st->rowCount()>0){
      $error = true;
    }else{
      $user = array(
        "fullname" => $fullname,
        "email_address" => $email,
        "industry" => $industry,
        "passcode" => $passcode,
        "last_qid" => 1
      );
  
      $sql = sprintf("INSERT INTO %s (%s) values (%s)","users",implode(", ", array_keys($user)),":" . implode(", :", array_keys($user)));
      $stm = $conn->prepare($sql);
      $stm->execute($user);
  
      if($stm->rowCount()==1){  
        $_SESSION['user_id'] = $passcode; 
        $_SESSION['qgroup'] = 1;      
          //SEND EMAIL
          //     $to = $email;
          //     $subject = "Your Survey Recovery Code";
          //     $message = "This is your auto generated survey recovery code!
          // You will need it to reload your survey form if you are not able to finish in one try.
          //         ----------------------------------
          //         Recovery Code: ".$passcode."
          //         ----------------------------------
          // Click the link below to continue your survey.
          //     https://spdcupc.com/auth/verify?token=".$passcode;
          //     $headers = "From:noreply@spdcupc.com"."\r\n";
          //     try{
          //         mail($to,$subject,$message,$headers);
          //         echo "verification email sent!";
          header("Location: i40-readiness-assessement.php");
          // }catch(PDOException $error){
          //     echo $error->getMessage();
          // }
      }
    } 
  }catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Survey | Product Readiness</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-danger">
    <div class="card-header text-center">
      <a aria-disabled="disabled" class="h1"><b style="color:#C92049">I4R</b><small> MODEL</small></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Enter a few details we can use to save and recover your survey, if your're not able to finish.</p>
      
      <form method="post">
        <?php
          
          if($error){?>
            <p class="text-center" style="color:red;"> User already exist with this email! <span><br><a href="survey_recovery.php" class="text-center">Load Form</a></span></p>
            
         <?php }?>
        <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
        <input type="hidden" name="passcode" value="<?php echo $passcode; ?>">
        <div class="input-group mb-3">
          <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="industry" class="form-control" placeholder="Industry" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-briefcase"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <input type="submit" name="survey_signup" value="Continue to Survey" class="btn btn-info btn-block">
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
