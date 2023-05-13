
<?php
require "config.php";
include "templates/header.php";
include "common/scripts.php";
$passcode = $_SESSION['user_id'];
$qid = $_GET['qid'];
if(isset($_POST['save_survey'])){
  try{
    $conn = new PDO($dsn,$username,$password,$options);
    //TODO: update users table
    $user = array(
      "last_qid" => $qid,
      "passcode" => $passcode
    );
    $sql = "UPDATE users SET last_qid = :last_qid WHERE passcode = :passcode";
    $ustmnt = $conn->prepare($sql);
    if($ustmnt->execute($user)){
        header("Location: index.php");
    }
  }catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  } 
}
 
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"> Survey: <small>Product & Services Readiness</small></h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="card card-info card-outline">
              <div class="card-body">
                    <h4 class="text-dark"><strong>Resume Later</strong></h4>
                    <p class="card-text">
                    Use the same invitation link that was sent to you to resume your survey when you are ready. Copy and keep your <strong>Recovery Code</strong> below then click on Save.
                    </p>
                    <p>Recovery Code: <span class="text-muted"><?php echo $passcode;?></span></p>
                    <form method="POST">
                    <ul class="todo-list" data-widget="todo-list">
                        <div class="form-group col-md-4 col-sm-12">
                        <div class="form-group clearfix">
                            
                        </div>
                    </ul>
                    <div class="input-group mb-3">
                    <input name="qid" type="hidden" value="<?php echo $qid;?>"> 
                    <!-- <input type="text" name="src" class="form-control" value="jack@gmail.com" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                    </div> -->
                    </div>
                    <div class="row">
                    <!-- /.col -->
                    <div class="col-12">
                        <input type="submit" name="save_survey" value="Save Survey" class="btn btn-info btn-block">
                    </div>
                    <!-- /.col -->
                    </div>
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
include "templates/footer.php";
?>
              