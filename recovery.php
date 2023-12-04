<?php 
require "common/scripts.php";
require "config.php";
include "fetch-survey.php";
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CBM4.0 - Condition Based Maintenance for Oil & Gas - by Chinedu</title>

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
      <p class="login-box-msg">Enter your survey recovery code or your signup email address to continue.</p>
      
      <form method="post">
        <?php
          
          if($error){?>
            <p class="text-center" style="color:red;"> No user found for this code or email <span><br><a href="signup.php" class="text-center">Start New Survey</a></span></p>
            
         <?php }?>
        <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
    
        <div class="input-group mb-3">
          <input type="text" name="src" class="form-control" placeholder="SRC765436">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <input type="submit" name="recovery" value="Load Survey" class="btn btn-info btn-block">
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
