<?php
include "config.php";
//universal function for checking session status-php manual
function is_session_started(){
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}
//check status
if ( is_session_started() === FALSE ) session_start();

//progress bar colors
function progress_bar_color($dimension){
  if($dimension=="Organisation"){
    return 'progress-bar bg-primary';
  }
  if($dimension=="Technology/IT Solutions"){
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

//summary plot
$connection = new PDO($dsn, $username, $password, $options);

$sql = "SELECT dimension, AVG(response) AS score FROM survey_responses GROUP BY dimension";
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


$weighted = 0;
  for ($i=0; $i < count($values); $i++) { 
    $weighted += $values[$i];
}


//survey count - total
$sql = "SELECT COUNT(passcode) AS total FROM users WHERE last_qid <= 95";
$statement = $connection->prepare($sql);
$statement->execute();
$count = $statement->fetch(PDO::FETCH_ASSOC);

//survey count - completed
$sql = "SELECT COUNT(passcode) AS total FROM users WHERE last_qid = 95";
$statement = $connection->prepare($sql);
$statement->execute();
$completed = $statement->fetch(PDO::FETCH_ASSOC);

//survey count - unfinished
$sql = "SELECT COUNT(passcode) AS total FROM users WHERE last_qid < 95";
$statement = $connection->prepare($sql);
$statement->execute();
$unfinished = $statement->fetch(PDO::FETCH_ASSOC);

$rate = round(($completed['total']/$count['total'])*100,1);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>I4R Assessement | by Chinedu</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="shortcut icon" href="dist/img/LOGO.jpg" type="image/x-icon">
  <link rel="icon" href="dist/img/LOGO.jpg" type="image/x-icon">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Datatables -->
  <link rel="stylesheet" type="text/css" href="static/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="static/dataTables.bootstrap5.min.css">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  
  <style>
    @media print { 
    .table th { 
        background-color: transparent !important; 
        } 
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Send Email</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link" style="text-decoration:none;">
      <img src="dist/img/AdminLTELogo.png" alt="I4R Logo" class="brand-image img-circle elevation-2"
           style="opacity: 1">
      <span class="brand-text" style ="color:gold;"><b style="color:#fff">I4R</b><small> MODEL</small></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user1-128x128.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" style="text-decoration:none;">Chinedu Onyeme<?php //echo strtoupper($_SESSION['loggedIn']);?></a>
        </div>
      </div>
         <!-- Sidebar Menu -->
         <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
                     
            <a href="/dashboard.php" class="nav-link office" aria-disabled="true">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/" class="nav-link factory">
              <i class="nav-icon far fa-clipboard"></i>
              <p>
                Assessement Tool
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/recovery.php" class="nav-link office">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Reload Form
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div id="pdfContent" class="content-wrapper">
    <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/">Home</a></li>
                  <li class="breadcrumb-item active">Dashboard</li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
              <!-- Small boxes (Stat box) -->
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3><?php echo $count['total']; ?></h3>

                        <p>Total Taken</p>
                      </div>
                      <div class="icon">
                        <i class="fas fas-bag"></i>
                      </div>
                      <a href="#" class="small-box-footer">Survey Count <i class="fas fa-chart-bar"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3><?php echo $completed['total']; ?></h3>

                        <p>Completed</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                      </div>
                      <a href="#" class="small-box-footer">Survey Count <i class="fas fa-chart-bar"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3><?php echo $unfinished['total']; ?></h3>

                        <p>Unfinished</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-person-add"></i>
                      </div>
                      <a href="#" class="small-box-footer">Survey Count <i class="fas fa-chart-bar"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                      <div class="inner">
                      <h3><?php echo $rate; ?><sup style="font-size: 20px">%</sup></h3>

                        <p>Completion Rate</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                      </div>
                      <a href="#" class="small-box-footer">Survey Count <i class="fas fa-chart-bar"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                </div>
                <div class="row">
                  <div class="col-md-7 col-sm-12">
                    <div class="card">
                      <div class="card-header">
                          <span>Assessement Entries</span>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body table-responsive">
                        <table id="users_table" class="table table-striped">
                            <thead>
                            <tr>
                            <th>SRC</th>
                            <th>Full Name</th>
                            <th>E-Mail</th>
                            <th>Industry</th>
                            <th>Status</th>
                            </tr>
                            </thead>
                        </table>
                      </div>
                      <!-- /.card-body -->
                    </div>
                  </div>
                  <div class="col-md-5 col-sm-12">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">
                          Dimension Levels
                        </h3>
                      </div>
                      <div class="card-body">
                      <p class="text-center">
                           <?php
                            
                           ?>
                            <strong>All Entries (Weighted): <?php echo round($weighted/count($values),1); ?></strong>
                          </p>
                          <?php for ($i=0; $i < count($values); $i++) { ?>
                            <div class="progress-group">
                              <?php $label = $labels[$i]; echo $label;?>
                              <span class="float-right"><b>Level</b> <?php echo $values[$i];?></span>
                              <div class="progress progress-sm">
                                <div class="<?php echo progress_bar_color($label);?>" style="width: <?php echo ($values[$i]/4)*100;?>%"></div>
                              </div>
                            </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">
                          <i class="fas fa-chart-pie mr-1"></i>
                          All Entries Plot
                        </h3>
                      </div><!-- /.card-header -->
                      <div class="card-body">
                      <canvas id="radarChart"></canvas>
                      </div><!-- /.card-body -->
                    </div>
                    
                  </div> <!-- /col-6  -->
                </div>
                <!-- /.card -->
            </div>
        </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="d-none d-sm-inline-block">
      <strong>2023 &copy; <b style="color:#C92049">I4R</b><small> MODEL</small></a>.</strong>
    </div>
    <div class="float-right d-none d-sm-inline-block">
      By <a href="https://deepr.ng">AJThompson</a>
    </div>    
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script type="text/javascript" src="static/jquery.js"></script>

<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- Datatable responsive -->
<script type="text/javascript" src="static/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="static/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
//datatable
$(document).ready(function(){
    $("#users_table").DataTable({
          ajax:{
            url:'survey_dt.php',
            dataSrc:''
          },
          columns:[
            {data:'passcode'},
            {data:'fullname'},
            {data:'email_address'},
            {data:'industry'},
            {data:5},
          ],
          responsive: true,
          paging: true,
          scrollY: 400,
          autoWidth: true,
    });
});

//radar plot
const rachar = document.getElementById('radarChart');

const data = {
    labels: <?php echo $data_labels;?>,
    datasets: [{
        label: 'Dimension Summary',
        data: <?php echo $data_values; ?>,
        fill: true,
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgb(255, 99, 132)',
        pointBackgroundColor: 'rgb(255, 99, 132)',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgb(255, 99, 132)'
    }]
};
const config = {
        type: 'radar',
        data: data,
        options: {
            elements: {
                line: {
                    borderWidth: 3
                }
            },
            scales: {
                r: {
                    max: 4,
                    min: 0,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
            legend: {
                display: true,
                labels: {
                    color: 'rgb(42, 170, 190)'
                }
            }
        }
        },
    };
new Chart(rachar,config);
</script>
</body>
</html>
