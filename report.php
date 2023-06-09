<?php
include "common/scripts.php";
include "plot-data.php";//fetch individual survey entry
include "templates/header.php";

//  
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1 class="m-0 text-dark"> Survey: <small>Your Summary</small></h1> -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row" style="padding-left:25px;">
          <div class="col-lg-12">
            <div class="card card-info card-outline">
              <!-- <button id="printpdf" class="btn btn-block bg-gradient-info"><span class="nav-icon fas fa-plus"></span> Add New Customer</button> -->
              <div class="card-body">
                <h2 class="text-dark">Evaluation of Industry 4.0 Readiness</h2>
                <p class="card-text">
                We appreciate your effort in completing the Industry 4.0 readiness assessment. Below, you'll find your results and specific actions you can take to enhance and progress on your journey of implementing Industry 4.0 for Condition-Based Maintenance (CBM).
                </p>
                <div class="row"><!--SUMMARY CHART -->
                <?php 
                              $weighted = 0;
                              for ($i=0; $i < count($values); $i++) { 
                                $weighted += $values[$i];
                              }
                            ?>
                  <div class="col-md-12">
                    <h2 class="text-dark"><small style="color:#C92049">Overall Evaluation</small></h2>
                     
                    <p class="card-text">Your organisation or team has achieved a score of <strong style="color:#C92049"><?php echo round($weighted/count($values),1); ?></strong> in the overall assessment. The readiness or maturity of your organisation in the five dimensions of Industry 4.0 is as follows:</p>   
                    <div class="row">
                      <div class="col-md-5">
                        <canvas id="radarChart"></canvas>
                      </div>
                      <div class="col-md-1"></div>
                      <div class="col-md-6">
                       
                        <div class="col-md-12" style="font-size:13px;">
                          <p class="text-center">
                              
                              <strong>Overall (Weighted): <?php echo round($weighted/count($values),1); ?></strong>
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
                            <!-- /.progress-group -->
                        </div>
                        <br>
                        <!-- /.col -->
                        <div class="col-md-12">
                        <table class="table table-hover table-sm" style="font-size:13px;">
                          <thead>
                            <tr>
                              <th scope="col">Level Outcomes</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Level 0 - No evidence of I4.0 implementation.</td>
                            </tr>
                            <tr>
                              <td>Level 1 - Informal/inadequate processes in place for I4.0 integration to CBM.</td>
                            </tr>
                            <tr>
                              <td>Level 2 - Formal processes in place and partial implementation of I4.0 in CBM.</td>
                            </tr>
                              <td>Level 3 - Implementation of I4.0 in CBM commenced and in progress.</td>
                            </tr>
                              <td>Level 4 - I4.0 Fully implemented in CBM (I4.0 based CBM) with evidence of implementation.</td>
                            </tr>
                          </tbody>
                        </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!-- summary row -->
                <div class="row"><!--ORGANISATION CHART -->
                  <div class="col-md-12">
                    <h2 class="text-dark"><small style="color:#C92049">Details of Readiness in The Dimensions</small></h2><br><br>
                    <h3 class="text-dark">1. Organisation</h3>
                    <div class="row">
                      <div class="col-md-6">
                          <canvas id="orgChart"></canvas>
                      </div>
                      <div class="col-md-6">
                        <div class="col-md-9" style="font-size:12px;">
                          <!-- TODO: Sublevel Summary -->
                          <p class="text-center">
                            <?php 
                              $org_weighted = 0;
                              for ($i=0; $i < count($org_values); $i++) { 
                                $org_weighted += $org_values[$i];
                              }
                            ?>
                            <strong>Organisation (Weighted): <?php echo round($org_weighted/4,1); ?></strong>
                          </p>
                        <?php for ($i=0; $i < count($org_values); $i++) { ?>
                          <div class="progress-group">
                            <?php $org_label = $org_labels[$i]; echo $org_label;?>
                            <span class="float-right"><?php echo $org_values[$i];?></span>
                            <div class="progress progress-xs">
                              <div class="progress-bar bg-primary" style="width: <?php echo ($org_values[$i]/4)*100;?>%"></div>
                            </div>
                          </div>
                        <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="padding-left:50px;">
                        <div class="col-md-12">
                            <!-- TODO: Actions to improve readiness in this dimension -->
                          <?php
                            $connection = new PDO($dsn, $username, $password, $options);
                            $org_level = round($org_weighted/count($org_values),0);
                            $sql = "SELECT action_point FROM recommendations WHERE labels = 'Organisation' AND level =$org_level";
                            $org_stmt = $connection->prepare($sql);
                            $org_stmt->execute();
                            $org_results = $org_stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo "<h5>Actions to improve readiness in this dimension:</strong></h5>";
                            echo "<ul>";
                            foreach($org_results as $recommendation){
                              echo "<li>".$recommendation['action_point']."</li>";
                            }
                            echo "</ul>";
                            
                          ?>
                            
                        </div>
                    </div>
                  </div>
                </div><!--sub-dimension -->
                <div class="row"><!--PEOPLE CHART -->
                  <div class="col-md-12">
                    <h3 class="text-dark">2. People</h3>
                    <div class="row">
                      <div class="col-md-6">
                          <canvas id="peopleChart"></canvas>
                      </div>
                      <div class="col-md-6" style="padding-bottom:20px;">
                        <div class="col-md-9" style="font-size:12px;">
                          <!-- TODO: Sublevel Summary -->
                          <p class="text-center">
                            <?php 
                              $people_weighted = 0;
                              for ($i=0; $i < count($people_values); $i++) { 
                                $people_weighted += $people_values[$i];
                              }
                            ?>
                            <strong>People (Weighted): <?php echo round($people_weighted/count($people_values),1); ?></strong>
                          </p>
                        <?php for ($i=0; $i < count($people_values); $i++) { ?>
                          <div class="progress-group">
                            <?php $people_label = $people_labels[$i]; echo $people_label;?>
                            <span class="float-right"><?php echo $people_values[$i];?></span>
                            <div class="progress progress-xs">
                              <div class="progress-bar bg-warning" style="width: <?php echo ($people_values[$i]/4)*100;?>%"></div>
                            </div>
                          </div>
                        <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="padding-left:50px;">
                        <div class="col-md-12">
                            <!-- TODO: Actions to improve readiness in this dimension -->
                          <?php
                            $connection = new PDO($dsn, $username, $password, $options);
                            $level = round($people_weighted/count($people_values),0);
                            $sql = "SELECT action_point FROM recommendations WHERE labels = 'People' AND level =$level";
                            $org_stmt = $connection->prepare($sql);
                            $org_stmt->execute();
                            $org_results = $org_stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo "<h5>Actions to improve readiness in this dimension:</h5>";
                            echo "<ul>";
                            foreach($org_results as $recommendation){
                              echo "<li>".$recommendation['action_point']."</li>";
                            }
                            echo "</ul>";
                            
                          ?>
                            
                        </div>
                    </div>
                  </div>
                </div><!--sub-dimension -->
                <div class="row"><!--TECHNOLOGY CHART -->
                  <div class="col-md-12">
                    <h3 class="text-dark">3. Industry 4.0 Solutions</h3>
                    <div class="row">
                      <div class="col-md-6">
                          <canvas id="techChart"></canvas>
                      </div>
                      <div class="col-md-6" style="padding-bottom:20px;">
                        <div class="col-md-9" style="font-size:12px;">
                          <!-- TODO: Sublevel Summary -->
                          <p class="text-center">
                            <?php 
                              $tech_weighted = 0;
                              for ($i=0; $i < count($tech_values); $i++) { 
                                $tech_weighted += $tech_values[$i];
                              }
                            ?>
                            <strong>Industry 4.0 Solutions (Weighted): <?php echo round($tech_weighted/count($tech_values),1); ?></strong>
                          </p>
                        <?php for ($i=0; $i < count($tech_values); $i++) { ?>
                          <div class="progress-group">
                            <?php $tech_label = $tech_labels[$i]; echo $tech_label;?>
                            <span class="float-right"> <?php echo $tech_values[$i];?></span>
                            <div class="progress progress-xs">
                              <div class="progress-bar bg-danger" style="width: <?php echo ($tech_values[$i]/4)*100;?>%"></div>
                            </div>
                          </div>
                        <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="padding-left:50px;">
                        <div class="col-md-12">
                            <!-- TODO: Actions to improve readiness in this dimension -->
                          <?php
                            $connection = new PDO($dsn, $username, $password, $options);
                            $level = round($tech_weighted/count($tech_values),0);
                            $sql = "SELECT action_point FROM recommendations WHERE labels = 'Technology/IT Solutions' AND level =$level";
                            $org_stmt = $connection->prepare($sql);
                            $org_stmt->execute();
                            $org_results = $org_stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo "<h5>Actions to improve readiness in this dimension:</h5>";
                            echo "<ul>";
                            foreach($org_results as $recommendation){
                              echo "<li>".$recommendation['action_point']."</li>";
                            }
                            echo "</ul>";
                            
                          ?>
                            
                        </div>
                    </div>
                  </div>
                </div><!--sub-dimension -->
                <div class="row"><!--PROCESSES, OPERATIONS AND MAINTENANCE -->
                  <div class="col-md-12">
                    <h3 class="text-dark">4. Processes, Operations and Maintenance</h3>
                    <div class="row">
                      <div class="col-md-6">
                          <canvas id="pomChart"></canvas>
                      </div>
                      <div class="col-md-6" style="padding-bottom:20px;">
                        <div class="col-md-9" style="font-size:12px;">
                          <!-- TODO: Sublevel Summary -->
                          <p class="text-center">
                            <?php 
                              $pom_weighted = 0;
                              for ($i=0; $i < count($pom_values); $i++) { 
                                $pom_weighted += $pom_values[$i];
                              }
                            ?>
                            <strong>Processes, Operations and Maintenance (Weighted): <?php echo round($pom_weighted/count($pom_values),1); ?></strong>
                          </p>
                        <?php for ($i=0; $i < count($pom_values); $i++) { ?>
                          <div class="progress-group">
                            <?php $pom_label = $pom_labels[$i];
                            echo $pom_label;
                            ?>
                            <span class="float-right"> <?php echo $pom_values[$i];?></span>
                            <div class="progress progress-xs">
                              <div class="progress-bar bg-secondary" style="width: <?php echo ($pom_values[$i]/4)*100;?>%"></div>
                            </div>
                          </div>
                        <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="padding-left:50px;">
                        <div class="col-md-12">
                            <!-- TODO: Actions to improve readiness in this dimension -->
                          <?php
                            $connection = new PDO($dsn, $username, $password, $options);
                            $level = round($pom_weighted/count($pom_values),0);
                            $sql = "SELECT action_point FROM recommendations WHERE labels = 'Processes, Operations and Maintenance' AND level =$level";
                            $org_stmt = $connection->prepare($sql);
                            $org_stmt->execute();
                            $org_results = $org_stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo "<h5>Actions to improve readiness in this dimension:</h5>";
                            echo "<ul>";
                            foreach($org_results as $recommendation){
                              echo "<li>".$recommendation['action_point']."</li>";
                            }
                            echo "</ul>";
                            
                          ?>
                            
                        </div>
                    </div>
                  </div>
                </div><!--sub-dimension -->
                <div class="row"><!--SUSTAINABILITY (ENVIRONMENT) -->
                  <div class="col-md-12">
                    <h3 class="text-dark">5. Sustainability(Environment)</h3>
                    <div class="row">
                      <div class="col-md-6">
                          <canvas id="susChart"></canvas>
                      </div>
                      <div class="col-md-6" style="padding-bottom:20px;">
                        <div class="col-md-9" style="font-size:12px;">
                          <!-- TODO: Sublevel Summary -->
                          <p class="text-center">
                            <?php 
                              $sus_weighted = 0;
                              for ($i=0; $i < count($sus_values)-1; $i++) { 
                                $sus_weighted += $sus_values[$i];
                              }
                            ?>
                            <strong>Sustainability(Environment): <?php echo round($sus_weighted/(count($sus_values)-1),1); ?></strong>
                          </p>
                        <?php for ($i=0; $i < count($sus_values)-1; $i++) { ?>
                          <div class="progress-group">
                            <?php $sus_label = $sus_labels[$i];
                            echo $sus_label;
                            ?>
                            <span class="float-right"> <?php echo $sus_values[$i];?></span>
                            <div class="progress progress-xs">
                              <div class="progress-bar bg-info" style="width: <?php echo ($sus_values[$i]/4)*100;?>%"></div>
                            </div>
                          </div>
                        <?php } ?>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="padding-left:50px;">
                        <div class="col-md-12">
                            <!-- TODO: Actions to improve readiness in this dimension -->
                          <?php
                            $connection = new PDO($dsn, $username, $password, $options);
                            $level = round($sus_weighted/count($sus_values)-1,0);
                            $sql = "SELECT action_point FROM recommendations WHERE labels = 'Sustainability (Environment)' AND level =$level";
                            $org_stmt = $connection->prepare($sql);
                            $org_stmt->execute();
                            $org_results = $org_stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo "<h5>Actions to improve readiness in this dimension:</h5>";
                            echo "<ul>";
                            foreach($org_results as $recommendation){
                              echo "<li>".$recommendation['action_point']."</li>";
                            }
                            echo "</ul>";
                            
                          ?>
                            
                        </div>
                    </div>
                  </div>
                </div><!--sub-dimension -->
                <br>   
                
                <div class="mailbox-controls with-borde text-center">
                  <div class="btn-group">
                    <?php //if($qid != 101) {?> 
                    <a href="#" id="downloadpdf" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Previous">
                    <i class="fas fa-download"></i> Download</a>
                    <?php //}?>
                    <button id="printpdf" type="submit" class="btn btn-info btn-sm"><i class="fas fa-print"></i> Print Report</button>
                  </div>
                </div>
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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
  //print page
  const printButton = document.getElementById("printpdf");
  const downloadButton = document.getElementById('downloadpdf');

  printButton.addEventListener('click', function(){
      window.print();
  });
  downloadButton.addEventListener('click', function(){
      window.print();
  });
  ////FUNCTION////
  function formatLabel(str, maxwidth){
  var sections = [];
  var words = str.split(" ");
  var temp = "";

  words.forEach(function(item, index){
    if(temp.length > 0)
    {
      var concat = temp + ' ' + item;

      if(concat.length > maxwidth){
        sections.push(temp);
        temp = "";
      }
      else{
        if(index == (words.length-1)) {
          sections.push(concat);
          return;
        }
        else {
          temp = concat;
          return;
        }
      }
    }

    if(index == (words.length-1)) {
      sections.push(item);
      return;
    }

    if(item.length < maxwidth) {
      temp = item;
    }
    else {
      sections.push(item);
    }

  });

  return sections;
}

function shortLabels(labels){
  var new_labels = [];
  for (let i = 0; i < labels.length; i++) {
     new_labels.push(formatLabel(labels[i],15));
  }
  return new_labels;
}
  ////END FUNCTION///
  //ALL DIMENSIONS
    const rachar = document.getElementById('radarChart');
    var overall_labels = <?php echo $data_labels; ?>;
    const data = {
        labels: shortLabels(overall_labels),
        datasets: [{
            label:"Summary Dataset",
            data: <?php echo $data_values; ?>,
            fill: true,
            backgroundColor: 'rgba(42, 170, 190, 0.1)',
            borderColor: 'rgb(42, 170, 190)',
            pointBackgroundColor: 'rgb(42, 170, 190)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(42, 170, 190)'
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
                    },
                    beginAtZero:true
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

    //ORGANISATION
    const org_chart = document.getElementById('orgChart');
    var org_labels = <?php echo $org_sub; ?>;
    const org_data = {
        labels: shortLabels(org_labels),
        datasets: [{
            label:"Organisation Dataset",
            data: <?php echo $org_data; ?>,
            fill: true,
            backgroundColor: 'rgba(42, 170, 190, 0.1)',
            borderColor: 'rgb(42, 170, 190)',
            pointBackgroundColor: 'rgb(42, 170, 190)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(42, 170, 190)'
        }]
    };
    const org_config = {
        type: 'radar',
        data: org_data,
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
    new Chart(org_chart,org_config);

    //PEOPLE
    const people_chart = document.getElementById('peopleChart');
    var people_labels = <?php echo $people_sub; ?>;
    const people_data = {
        labels: shortLabels(people_labels),
        datasets: [{
            label:"People Dataset",
            data: <?php echo $people_data; ?>,
            fill: true,
            backgroundColor: 'rgba(42, 170, 190, 0.1)',
            borderColor: 'rgb(42, 170, 190)',
            pointBackgroundColor: 'rgb(42, 170, 190)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(42, 170, 190)'
        }]
    };
    const people_config = {
        type: 'radar',
        data: people_data,
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
    new Chart(people_chart,people_config);

    //TECHNOLOGY
    const tech_chart = document.getElementById('techChart');
    var tech_labels = <?php echo $tech_sub; ?>;
    const tech_data = {
        labels: shortLabels(tech_labels),
        datasets: [{
           label:"Industry 4.0 Solutions Dataset",
            data: <?php echo $tech_data; ?>,
            fill: true,
            backgroundColor: 'rgba(42, 170, 190, 0.1)',
            borderColor: 'rgb(42, 170, 190)',
            pointBackgroundColor: 'rgb(42, 170, 190)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(42, 170, 190)'
        }]
    };
    const tech_config = {
        type: 'radar',
        data: tech_data,
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
    new Chart(tech_chart,tech_config);

    //PROCESSES, OPERATIONS AND MAINTENANCE
    const pom_chart = document.getElementById('pomChart');
    var pom_labels = <?php echo $pom_sub; ?>;
    const pom_data = {
        labels: shortLabels(pom_labels),
        datasets: [{
            label:"Process, Operation & Maintenance Dataset",
            data: <?php echo $pom_data; ?>,
            fill: true,
            backgroundColor: 'rgba(42, 170, 190, 0.1)',
            borderColor: 'rgb(42, 170, 190)',
            pointBackgroundColor: 'rgb(42, 170, 190)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(42, 170, 190)'
        }]
    };
    const pom_config = {
        type: 'radar',
        data: pom_data,
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
    new Chart(pom_chart,pom_config);

    //SUSTAINABILITY(ENVIRONMENT)
    const sus_chart = document.getElementById('susChart');
    var sus_labels = <?php echo $sus_sub; ?>;
    const sus_data = {
        labels: shortLabels(sus_labels),
        datasets: [{
            label:"Sustainability(Environment) Dataset",
            data: <?php echo $sus_data; ?>,
            fill: true,
            backgroundColor: 'rgba(42, 170, 190, 0.1)',
            borderColor: 'rgb(42, 170, 190)',
            pointBackgroundColor: 'rgb(42, 170, 190)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(42, 170, 190)'
        }]
    };
    const sus_config = {
        type: 'radar',
        data: sus_data,
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
    new Chart(sus_chart,sus_config);
</script>