

<?php

  //Setup an autoloader (using src/autoload.php)
  // $srcDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src';
  require 'vendor/autoload.php';

  use PHPOnCouch\CouchClient; //The CouchDB client object

  $couchDsn = "http://admin:admin@localhost:5984/";
  $couchDB = "geo_db";

  /**
  * create the client
  */
  $client = new CouchClient($couchDsn,$couchDB);

  $selector = [
      '$and' =>
      [
          ['_id' => ['$gt' => null]]
      ]
  ];
  $unemployed_docs = $client->limit(5)->sort([["unemployed" => "desc"]])->fields(['name_16', 'unemployed', 'pos_rate'])->find($selector);
  $income_over_4000_docs = $client->limit(5)->sort([["income_over_4000" => "desc"]])->fields(['name_16', 'income_over_4000', 'pos_rate'])->find($selector);
  $person_did_not_go_to_school_total_docs = $client->limit(5)->sort([["person_did_not_go_to_school_total" => "desc"]])->fields(['name_16', 'person_did_not_go_to_school_total', 'pos_rate'])->find($selector);
  $person_medical_help_docs = $client->limit(5)->sort([["person_medical_help" => "desc"]])->fields(['name_16', 'person_medical_help', 'pos_rate'])->find($selector);
  $voluntary_work_total_docs = $client->limit(5)->sort([["voluntary_work_total" => "desc"]])->fields(['name_16', 'voluntary_work_total', 'pos_rate'])->find($selector);
  $moving_house_docs = $client->limit(5)->sort([["moving_house" => "desc"]])->fields(['name_16', 'moving_house', 'pos_rate'])->find($selector);

 ?>
<div class="container" id="content-container">
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
      <h3>Analysis</h3>
    </div>
  </div>
  <div class="row">
    <canvas id="unemployedChart" height="100"></canvas>
  </div>
  <script>
    var unemployedChartContext = document.getElementById('unemployedChart').getContext('2d');
    var unemployedChartData = {
      labels: [
        <?php echo '"'.$unemployed_docs[0]->name_16.'","'.
                    $unemployed_docs[1]->name_16.'","'.
                    $unemployed_docs[2]->name_16.'","'.
                    $unemployed_docs[3]->name_16.'","'.
                    $unemployed_docs[4]->name_16.'"' ?>
                      ],
      datasets: [{
        label: 'unemployed',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        yAxisID: 'y-axis-1',
        borderWidth: 1,
        data: [
          <?php echo $unemployed_docs[0]->unemployed.','.
                      $unemployed_docs[1]->unemployed.','.
                      $unemployed_docs[2]->unemployed.','.
                      $unemployed_docs[3]->unemployed.','.
                      $unemployed_docs[4]->unemployed ?>
        ]
      }, {
        label: 'tweets sentiment',
        backgroundColor: 'rgb(132, 99, 255)',
        borderColor: 'rgb(132, 99, 255)',
        yAxisID: 'y-axis-2',
        data: [
          <?php echo $unemployed_docs[0]->pos_rate.','.
                      $unemployed_docs[1]->pos_rate.','.
                      $unemployed_docs[2]->pos_rate.','.
                      $unemployed_docs[3]->pos_rate.','.
                      $unemployed_docs[4]->pos_rate ?>
        ]
      }]
    };
    var unemployedChart = new Chart(unemployedChartContext, {
        // The type of chart we want to create
        type: 'bar',
        // The data for our dataset
        data: unemployedChartData,
        // Configuration options go here
        options: {
          responsive: true,
          title: {
            display: true,
            text: 'Unemployed and Tweet Sentiment Relation'
          },
          tooltips: {
            mode: 'index',
            intersect: true
          },
          scales: {
            yAxes: [{
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'left',
              id: 'y-axis-1'
            }, {
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'right',
              id: 'y-axis-2',
              gridLines: {
                drawOnChartArea: false
              }
            }]
          }
        }
    });
  </script>


  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <canvas id="educationChart" height="100"></canvas>
  </div>
  <script>
    var educationChartContext = document.getElementById('educationChart').getContext('2d');
    var educationChartData = {
      labels: [
        <?php echo '"'.$person_did_not_go_to_school_total_docs[0]->name_16.'","'.
                    $person_did_not_go_to_school_total_docs[1]->name_16.'","'.
                    $person_did_not_go_to_school_total_docs[2]->name_16.'","'.
                    $person_did_not_go_to_school_total_docs[3]->name_16.'","'.
                    $person_did_not_go_to_school_total_docs[4]->name_16.'"' ?>
                      ],
      datasets: [{
        label: 'non-education',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        yAxisID: 'y-axis-1',
        borderWidth: 1,
        data: [
          <?php echo $person_did_not_go_to_school_total_docs[0]->person_did_not_go_to_school_total.','.
                      $person_did_not_go_to_school_total_docs[1]->person_did_not_go_to_school_total.','.
                      $person_did_not_go_to_school_total_docs[2]->person_did_not_go_to_school_total.','.
                      $person_did_not_go_to_school_total_docs[3]->person_did_not_go_to_school_total.','.
                      $person_did_not_go_to_school_total_docs[4]->person_did_not_go_to_school_total ?>
        ]
      }, {
        label: 'tweets sentiment',
        backgroundColor: 'rgb(132, 99, 255)',
        borderColor: 'rgb(132, 99, 255)',
        yAxisID: 'y-axis-2',
        data: [
          <?php echo $person_did_not_go_to_school_total_docs[0]->pos_rate.','.
                      $person_did_not_go_to_school_total_docs[1]->pos_rate.','.
                      $person_did_not_go_to_school_total_docs[2]->pos_rate.','.
                      $person_did_not_go_to_school_total_docs[3]->pos_rate.','.
                      $person_did_not_go_to_school_total_docs[4]->pos_rate ?>
        ]
      }]
    };
    var educationChart = new Chart(educationChartContext, {
        // The type of chart we want to create
        type: 'bar',
        // The data for our dataset
        data: educationChartData,
        // Configuration options go here
        options: {
          responsive: true,
          title: {
            display: true,
            text: 'Education and Tweet Sentiment Relation'
          },
          tooltips: {
            mode: 'index',
            intersect: true
          },
          scales: {
            yAxes: [{
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'left',
              id: 'y-axis-1'
            }, {
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'right',
              id: 'y-axis-2',
              gridLines: {
                drawOnChartArea: false
              }
            }]
          }
        }
    });
  </script>


  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <canvas id="medicationChart" height="100"></canvas>
  </div>
  <script>
    var medicationChartContext = document.getElementById('medicationChart').getContext('2d');
    var medicationChartData = {
      labels: [
        <?php echo '"'.$person_medical_help_docs[0]->name_16.'","'.
                    $person_medical_help_docs[1]->name_16.'","'.
                    $person_medical_help_docs[2]->name_16.'","'.
                    $person_medical_help_docs[3]->name_16.'","'.
                    $person_medical_help_docs[4]->name_16.'"' ?>
                      ],
      datasets: [{
        label: 'medication care',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        yAxisID: 'y-axis-1',
        borderWidth: 1,
        data: [
          <?php echo $person_medical_help_docs[0]->person_medical_help.','.
                      $person_medical_help_docs[1]->person_medical_help.','.
                      $person_medical_help_docs[2]->person_medical_help.','.
                      $person_medical_help_docs[3]->person_medical_help.','.
                      $person_medical_help_docs[4]->person_medical_help ?>
        ]
      }, {
        label: 'tweets sentiment',
        backgroundColor: 'rgb(132, 99, 255)',
        borderColor: 'rgb(132, 99, 255)',
        yAxisID: 'y-axis-2',
        data: [
          <?php echo $person_medical_help_docs[0]->pos_rate.','.
                      $person_medical_help_docs[1]->pos_rate.','.
                      $person_medical_help_docs[2]->pos_rate.','.
                      $person_medical_help_docs[3]->pos_rate.','.
                      $person_medical_help_docs[4]->pos_rate ?>
        ]
      }]
    };
    var educationChart = new Chart(medicationChartContext, {
        // The type of chart we want to create
        type: 'bar',
        // The data for our dataset
        data: medicationChartData,
        // Configuration options go here
        options: {
          responsive: true,
          title: {
            display: true,
            text: 'Medication and Tweet Sentiment Relation'
          },
          tooltips: {
            mode: 'index',
            intersect: true
          },
          scales: {
            yAxes: [{
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'left',
              id: 'y-axis-1'
            }, {
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'right',
              id: 'y-axis-2',
              gridLines: {
                drawOnChartArea: false
              }
            }]
          }
        }
    });
  </script>


  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <canvas id="incomeChart" height="100"></canvas>
  </div>
  <script>
    var incomeChartContext = document.getElementById('incomeChart').getContext('2d');
    var incomeChartData = {
      labels: [
        <?php echo '"'.$income_over_4000_docs[0]->name_16.'","'.
                    $income_over_4000_docs[1]->name_16.'","'.
                    $income_over_4000_docs[2]->name_16.'","'.
                    $income_over_4000_docs[3]->name_16.'","'.
                    $income_over_4000_docs[4]->name_16.'"' ?>
                      ],
      datasets: [{
        label: 'income over 4000/week',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        yAxisID: 'y-axis-1',
        borderWidth: 1,
        data: [
          <?php echo $income_over_4000_docs[0]->income_over_4000.','.
                      $income_over_4000_docs[1]->income_over_4000.','.
                      $income_over_4000_docs[2]->income_over_4000.','.
                      $income_over_4000_docs[3]->income_over_4000.','.
                      $income_over_4000_docs[4]->income_over_4000 ?>
        ]
      }, {
        label: 'tweets sentiment',
        backgroundColor: 'rgb(132, 99, 255)',
        borderColor: 'rgb(132, 99, 255)',
        yAxisID: 'y-axis-2',
        data: [
          <?php echo $income_over_4000_docs[0]->pos_rate.','.
                      $income_over_4000_docs[1]->pos_rate.','.
                      $income_over_4000_docs[2]->pos_rate.','.
                      $income_over_4000_docs[3]->pos_rate.','.
                      $income_over_4000_docs[4]->pos_rate ?>
        ]
      }]
    };
    var educationChart = new Chart(incomeChartContext, {
        // The type of chart we want to create
        type: 'bar',
        // The data for our dataset
        data: incomeChartData,
        // Configuration options go here
        options: {
          responsive: true,
          title: {
            display: true,
            text: 'Income and Tweet Sentiment Relation'
          },
          tooltips: {
            mode: 'index',
            intersect: true
          },
          scales: {
            yAxes: [{
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'left',
              id: 'y-axis-1'
            }, {
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'right',
              id: 'y-axis-2',
              gridLines: {
                drawOnChartArea: false
              }
            }]
          }
        }
    });
  </script>



  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <canvas id="voluntaryChart" height="100"></canvas>
  </div>
  <script>
    var voluntaryChartContext = document.getElementById('voluntaryChart').getContext('2d');
    var voluntaryChartData = {
      labels: [
        <?php echo '"'.$voluntary_work_total_docs[0]->name_16.'","'.
                    $voluntary_work_total_docs[1]->name_16.'","'.
                    $voluntary_work_total_docs[2]->name_16.'","'.
                    $voluntary_work_total_docs[3]->name_16.'","'.
                    $voluntary_work_total_docs[4]->name_16.'"' ?>
                      ],
      datasets: [{
        label: 'voluntary',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        yAxisID: 'y-axis-1',
        borderWidth: 1,
        data: [
          <?php echo $voluntary_work_total_docs[0]->voluntary_work_total.','.
                      $voluntary_work_total_docs[1]->voluntary_work_total.','.
                      $voluntary_work_total_docs[2]->voluntary_work_total.','.
                      $voluntary_work_total_docs[3]->voluntary_work_total.','.
                      $voluntary_work_total_docs[4]->voluntary_work_total ?>
        ]
      }, {
        label: 'tweets sentiment',
        backgroundColor: 'rgb(132, 99, 255)',
        borderColor: 'rgb(132, 99, 255)',
        yAxisID: 'y-axis-2',
        data: [
          <?php echo $voluntary_work_total_docs[0]->pos_rate.','.
                      $voluntary_work_total_docs[1]->pos_rate.','.
                      $voluntary_work_total_docs[2]->pos_rate.','.
                      $voluntary_work_total_docs[3]->pos_rate.','.
                      $voluntary_work_total_docs[4]->pos_rate ?>
        ]
      }]
    };
    var educationChart = new Chart(voluntaryChartContext, {
        // The type of chart we want to create
        type: 'bar',
        // The data for our dataset
        data: voluntaryChartData,
        // Configuration options go here
        options: {
          responsive: true,
          title: {
            display: true,
            text: 'Voluntary and Tweet Sentiment Relation'
          },
          tooltips: {
            mode: 'index',
            intersect: true
          },
          scales: {
            yAxes: [{
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'left',
              id: 'y-axis-1'
            }, {
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'right',
              id: 'y-axis-2',
              gridLines: {
                drawOnChartArea: false
              }
            }]
          }
        }
    });
  </script>




  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <h1> </h1>
  </div>
  <div class="row">
    <canvas id="movingHouseChart" height="100"></canvas>
  </div>
  <script>
    var movingHouseChartContext = document.getElementById('movingHouseChart').getContext('2d');
    var movingHouseChartData = {
      labels: [
        <?php echo '"'.$moving_house_docs[0]->name_16.'","'.
                    $moving_house_docs[1]->name_16.'","'.
                    $moving_house_docs[2]->name_16.'","'.
                    $moving_house_docs[3]->name_16.'","'.
                    $moving_house_docs[4]->name_16.'"' ?>
                      ],
      datasets: [{
        label: 'moving house',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        yAxisID: 'y-axis-1',
        borderWidth: 1,
        data: [
          <?php echo $moving_house_docs[0]->moving_house.','.
                      $moving_house_docs[1]->moving_house.','.
                      $moving_house_docs[2]->moving_house.','.
                      $moving_house_docs[3]->moving_house.','.
                      $moving_house_docs[4]->moving_house ?>
        ]
      }, {
        label: 'tweets sentiment',
        backgroundColor: 'rgb(132, 99, 255)',
        borderColor: 'rgb(132, 99, 255)',
        yAxisID: 'y-axis-2',
        data: [
          <?php echo $moving_house_docs[0]->pos_rate.','.
                      $moving_house_docs[1]->pos_rate.','.
                      $moving_house_docs[2]->pos_rate.','.
                      $moving_house_docs[3]->pos_rate.','.
                      $moving_house_docs[4]->pos_rate ?>
        ]
      }]
    };
    var educationChart = new Chart(movingHouseChartContext, {
        // The type of chart we want to create
        type: 'bar',
        // The data for our dataset
        data: movingHouseChartData,
        // Configuration options go here
        options: {
          responsive: true,
          title: {
            display: true,
            text: 'Moving House and Tweet Sentiment Relation'
          },
          tooltips: {
            mode: 'index',
            intersect: true
          },
          scales: {
            yAxes: [{
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'left',
              id: 'y-axis-1'
            }, {
              type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
              display: true,
              position: 'right',
              id: 'y-axis-2',
              gridLines: {
                drawOnChartArea: false
              }
            }]
          }
        }
    });
  </script>















  <!-- <script>
    var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var color = Chart.helpers.color;
    var horizontalBarChartData = {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [{
        label: 'Dataset 1',
        backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
        borderColor: window.chartColors.red,
        borderWidth: 1,
        data: [
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor()
        ]
      }, {
        label: 'Dataset 2',
        backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
        borderColor: window.chartColors.blue,
        data: [
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor(),
          randomScalingFactor()
        ]
      }]

    };

    window.onload = function() {
      var ctx = document.getElementById('canvas').getContext('2d');
      window.myHorizontalBar = new Chart(ctx, {
        type: 'horizontalBar',
        data: horizontalBarChartData,
        options: {
          // Elements options apply to all of the options unless overridden in a dataset
          // In this case, we are setting the border of each horizontal bar to be 2px wide
          elements: {
            rectangle: {
              borderWidth: 2,
            }
          },
          responsive: true,
          legend: {
            position: 'right',
          },
          title: {
            display: true,
            text: 'Chart.js Horizontal Bar Chart'
          }
        }
      });

    };

    document.getElementById('randomizeData').addEventListener('click', function() {
      var zero = Math.random() < 0.2 ? true : false;
      horizontalBarChartData.datasets.forEach(function(dataset) {
        dataset.data = dataset.data.map(function() {
          return zero ? 0.0 : randomScalingFactor();
        });

      });
      window.myHorizontalBar.update();
    });

    var colorNames = Object.keys(window.chartColors);

    document.getElementById('addDataset').addEventListener('click', function() {
      var colorName = colorNames[horizontalBarChartData.datasets.length % colorNames.length];
      var dsColor = window.chartColors[colorName];
      var newDataset = {
        label: 'Dataset ' + horizontalBarChartData.datasets.length,
        backgroundColor: color(dsColor).alpha(0.5).rgbString(),
        borderColor: dsColor,
        data: []
      };

      for (var index = 0; index < horizontalBarChartData.labels.length; ++index) {
        newDataset.data.push(randomScalingFactor());
      }

      horizontalBarChartData.datasets.push(newDataset);
      window.myHorizontalBar.update();
    });

    document.getElementById('addData').addEventListener('click', function() {
      if (horizontalBarChartData.datasets.length > 0) {
        var month = MONTHS[horizontalBarChartData.labels.length % MONTHS.length];
        horizontalBarChartData.labels.push(month);

        for (var index = 0; index < horizontalBarChartData.datasets.length; ++index) {
          horizontalBarChartData.datasets[index].data.push(randomScalingFactor());
        }

        window.myHorizontalBar.update();
      }
    });

    document.getElementById('removeDataset').addEventListener('click', function() {
      horizontalBarChartData.datasets.splice(0, 1);
      window.myHorizontalBar.update();
    });

    document.getElementById('removeData').addEventListener('click', function() {
      horizontalBarChartData.labels.splice(-1, 1); // remove the label first

      horizontalBarChartData.datasets.forEach(function(dataset) {
        dataset.data.pop();
      });

      window.myHorizontalBar.update();
    });
  </script> -->
</div>