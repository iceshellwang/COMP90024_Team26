

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
  $unemployed_docs = $client->limit(5)->sort([["unemployed_Percent" => "desc"]])->fields(['name_16', 'unemployed_Percent', 'pos_rate'])->find($selector);
  $income_over_4000_docs = $client->limit(5)->sort([["weekly_income_4000_more_proportion" => "desc"]])->fields(['name_16', 'weekly_income_4000_more_proportion', 'pos_rate'])->find($selector);
  $person_did_not_go_to_school_total_docs = $client->limit(5)->sort([["did_not_go_to_schllo_Percent" => "desc"]])->fields(['name_16', 'did_not_go_to_schllo_Percent', 'pos_rate'])->find($selector);
  $person_medical_help_docs = $client->limit(5)->sort([["health_care_and_social_assistance" => "desc"]])->fields(['name_16', 'health_care_and_social_assistance', 'pos_rate'])->find($selector);
  $voluntary_work_total_docs = $client->limit(5)->sort([["volunteer_proportion" => "desc"]])->fields(['name_16', 'volunteer_proportion', 'pos_rate'])->find($selector);
  $moving_house_docs = $client->limit(5)->sort([["place_of_usual_residence_1_year_proportion" => "desc"]])->fields(['name_16', 'place_of_usual_residence_1_year_proportion', 'pos_rate'])->find($selector);

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
          <?php echo $unemployed_docs[0]->unemployed_Percent.','.
                      $unemployed_docs[1]->unemployed_Percent.','.
                      $unemployed_docs[2]->unemployed_Percent.','.
                      $unemployed_docs[3]->unemployed_Percent.','.
                      $unemployed_docs[4]->unemployed_Percent ?>
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
          <?php echo $person_did_not_go_to_school_total_docs[0]->did_not_go_to_schllo_Percent.','.
                      $person_did_not_go_to_school_total_docs[1]->did_not_go_to_schllo_Percent.','.
                      $person_did_not_go_to_school_total_docs[2]->did_not_go_to_schllo_Percent.','.
                      $person_did_not_go_to_school_total_docs[3]->did_not_go_to_schllo_Percent.','.
                      $person_did_not_go_to_school_total_docs[4]->did_not_go_to_schllo_Percent ?>
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
          <?php echo $person_medical_help_docs[0]->health_care_and_social_assistance.','.
                      $person_medical_help_docs[1]->health_care_and_social_assistance.','.
                      $person_medical_help_docs[2]->health_care_and_social_assistance.','.
                      $person_medical_help_docs[3]->health_care_and_social_assistance.','.
                      $person_medical_help_docs[4]->health_care_and_social_assistance ?>
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
          <?php echo $income_over_4000_docs[0]->weekly_income_4000_more_proportion.','.
                      $income_over_4000_docs[1]->weekly_income_4000_more_proportion.','.
                      $income_over_4000_docs[2]->weekly_income_4000_more_proportion.','.
                      $income_over_4000_docs[3]->weekly_income_4000_more_proportion.','.
                      $income_over_4000_docs[4]->weekly_income_4000_more_proportion ?>
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
          <?php echo $voluntary_work_total_docs[0]->volunteer_proportion.','.
                      $voluntary_work_total_docs[1]->volunteer_proportion.','.
                      $voluntary_work_total_docs[2]->volunteer_proportion.','.
                      $voluntary_work_total_docs[3]->volunteer_proportion.','.
                      $voluntary_work_total_docs[4]->volunteer_proportion ?>
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
          <?php echo $moving_house_docs[0]->place_of_usual_residence_1_year_proportion.','.
                      $moving_house_docs[1]->place_of_usual_residence_1_year_proportion.','.
                      $moving_house_docs[2]->place_of_usual_residence_1_year_proportion.','.
                      $moving_house_docs[3]->place_of_usual_residence_1_year_proportion.','.
                      $moving_house_docs[4]->place_of_usual_residence_1_year_proportion ?>
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
</div>