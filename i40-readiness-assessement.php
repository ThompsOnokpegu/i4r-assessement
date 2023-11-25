<?php
include "config.php";
include "templates/header.php";
include "common/scripts.php";

$connection = new PDO($dsn,$username,$password,$options);
//session_unset();
$qst_group = 1;// a set of options with same question
//use the question id from db unless it's the first one
if(isset($_SESSION['qgroup'])){
  $qst_group = $_SESSION['qgroup'];
}

//fetch questions from DB
$sql = "SELECT * FROM survey_questions WHERE qst_group =:qst_group";
$stmt = $connection->prepare($sql);
$stmt->bindValue(':qst_group', $qst_group);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count_qids = 0;//holds the total number of questions in the db using the qid column
$question = "";
$category = "";
$subcategory = "";
$last_qid = 0;
$last_qst = 0;
foreach ($results as $result){
  $question = $result['question'];
  $category = $result['category'];
  $subcategory = $result['sub_category'];
  $last_qid = $result['qid'];//remember my choice
  $last_qst = $result['qst_group'];//prevent question overflow
  $count_qids++;
}

//count questions in the db and set progress bar
$sql = "SELECT MAX(qst_group) as max FROM survey_questions";
$statement = $connection->prepare($sql);
$statement->execute();
$questions_count = $statement->fetch(PDO::FETCH_ASSOC);
$_SESSION['max_qid'] = $questions_count['max'];//prevent questions overflow
$progress_bar = intval((($last_qst-1)/$questions_count['max']) * 100);
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-info card-outline">
              <div class="card-body">              
                <form id="survey-form" method="post" action="save-survey.php">
                  <input type="hidden" name="last_qid" value="<?php echo $last_qid; ?>">
                  <input type="hidden" name="last_qst" value="<?php echo $last_qst; ?>">
                  <input type="hidden" name="count_qids" value="<?php echo $count_qids-1; ?>">
                  <input type="hidden" name="q_group" value="<?php echo $qst_group; ?>">
                  <input type="hidden" name="maxid" value="<?php echo $_SESSION['max_qid']; ?>">
                  <input type="hidden" name="dimension" value="<?php echo $category; ?>">
                  <input type="hidden" name="subdimension" value="<?php echo $subcategory; ?>">
                  <!-- IF QUESTION IS A HEADER - DISPLAY HEADER TEMPLATE -->
                  <?php if($subcategory == "header"){ ?>
                  <div class="row mb-2">
                    <div class="col-sm-8">
                      <h1 class="m-0 " style="color:#C92049"> <?php echo $category;?></h1>
                      <?php 
                    
                      if($category=="Organisation"){
                        include "templates/org.php"; 
                      }
                      if($category=="People"){
                        include "templates/people.php"; 
                      }
                      if($category=="Industry 4.0 Solutions"){
                        include "templates/tech.php"; 
                      }
                      if($category=="Processes, Operations and Maintenance"){
                        include "templates/pom.php"; 
                      }

                      if($category=="Sustainability (Environment)")
                        include "templates/sus.php";
                      ?>
                      
                    </div><!-- /.col -->
                  </div><br><!-- /.row -->
                  <div class="btn-group">
                    <a href="save.php?qid=<?php echo $_SESSION['qgroup']; ?>" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-container="body" title="Resume Later">
                      Resume Later</a>
                    <button id="retrieve" name="retrieve" type="submit" class="btn btn-default btn-sm" disable><i class="fas fa-reply"></i> Previous</button>
                    <button id="save_update" name="save_update" type="submit" class="btn btn-info btn-sm">Continue <i class="fas fa-share"></i></button>
                  </div> 
                  <?php }else{ ?>
                  <div class="progress active">
                      <div class="progress-bar bg-secondary progress-bar-striped" role="progressbar" id="progress"
                          aria-valuenow="<?php echo $progress_bar; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $progress_bar; ?>%">
                        <span class="sr-onl"><?php echo $progress_bar."% Complete";?></span>
                      </div>
                  </div><br> 
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0 " style="color:#138496;"> <?php echo $category .': <small style="color:#C92049">'.$subcategory;?></small></h1>
                    </div><!-- /.col -->
                  </div><br><!-- /.row -->
                  <table class="table table-hover table-sm" id="sub_question">
                    <thead>
                      <tr><th colspan="6"><?php echo $question;?></th></tr>
                      <tr>
                        <th></th>
                        <th>0</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th id="rating04">4</th>
                        <?php if($category == "Industry 4.0 Solutions"){ ?>
                          <th>NA</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ($results as $sub_question) { 
                        $qid = $sub_question['qid'];;
                        ?>
                        <tr><td><?php echo $sub_question['sub_question'];?></td>
                        <td><div class="icheck-success">
                        <input type="radio" value=0 id="<?php echo $qid."0"; ?>" name="<?php echo $qid; ?>" <?php if(isset($_SESSION["qid".$qid])){if($_SESSION["qid".$qid]==0) echo "checked";} ?>>
                        <label for="<?php echo $qid."0"; ?>"></label>
                        </div>
                        </td>
                        
                        <td><div class="icheck-success">
                        <input type="radio" value=1 id="<?php echo $qid."1"; ?>" name="<?php echo $qid; ?>" <?php if(isset($_SESSION["qid".$qid])){if($_SESSION["qid".$qid]==1) echo "checked";} ?>>
                        <label for="<?php echo $qid."1"; ?>"></label>
                        </div>
                        </td>
                      
                        <td><div class="icheck-success">
                        <input type="radio" value=2 id="<?php echo $qid."2"; ?>" name="<?php echo $qid; ?>" <?php if(isset($_SESSION["qid".$qid])){if($_SESSION["qid".$qid]==2) echo "checked";} ?>>
                        <label for="<?php echo $qid."2"; ?>"></label>
                        </div>
                        </td>
                      
                        <td><div class="icheck-success">
                        <input type="radio" value=3 id="<?php echo $qid."3"; ?>" name="<?php echo $qid; ?>" <?php if(isset($_SESSION["qid".$qid])){if($_SESSION["qid".$qid]==3) echo "checked";} ?>>
                        <label for="<?php echo $qid."3"; ?>"></label>
                        </div>
                        </td>
                      
                        <td><div class="icheck-success">
                        <input type="radio" value=4 id="<?php echo $qid."4"; ?>" name="<?php echo $qid; ?>" <?php if(isset($_SESSION["qid".$qid])){if($_SESSION["qid".$qid]==4) echo "checked";} ?>>
                        <label for="<?php echo $qid."4"; ?>"></label>
                        </div>
                        </td>
                        <?php if($category == "Industry 4.0 Solutions"){ ?>
                              <td><div class="icheck-success">
                              <input type="radio" value=5 id="<?php echo $qid."5"; ?>" name="<?php echo $qid; ?>" <?php if(isset($_SESSION["qid".$qid])){if($_SESSION["qid".$qid]==5) echo "checked";} ?>>
                              <label for="<?php echo $qid."5"; ?>"></label>
                              </div>
                              </td>
                        <?php } ?>
                        
                        </tr>
                      <?php } ?> 
                    </tbody>
                  </table>
                  <div class="mailbox-controls with-borde text-center">
                    <div class="btn-group">
                      <a href="save.php?qid=<?php echo $_SESSION['qgroup']; ?>" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-container="body" title="Resume Later">
                        Resume Later</a>
                      <button id="retrieve" name="retrieve" type="submit" class="btn btn-default btn-sm"><i class="fas fa-reply"></i> Previous</button>
                      <button id="save_update" name="save_update" type="submit" class="btn btn-info btn-sm">Continue <i class="fas fa-share"></i></button>
                    </div> 
                  </div>
                <?php } ?>
                </form>
               
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

