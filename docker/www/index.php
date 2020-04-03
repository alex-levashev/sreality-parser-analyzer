<html>
  <head>
    <title>Real Estates</title>
    <meta charset="utf-8">

    <link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-datetimepicker.css" rel="stylesheet">

    <script src="js/tether.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Chart.bundle.js"></script>
    <script src="js/Chart.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>
  </head>

<body>

<?php

  if(file_exists('lib.php')) { require_once("lib.php"); } else { echo('No lib.php file found!'); }

  $mng = new MongoDB\Driver\Manager("mongodb://root:root@mongodb:27017");
  $query = new MongoDB\Driver\Query([]);
  $rows = $mng->executeQuery("real_estate.raw_requests", $query);
  $prices_by_date = [];
  foreach ($rows as $row) {
    $row = json_decode(json_encode($row), true);
    foreach($row['items'] as $i) {
      if($i['name'] == 'Aktualizace') {
        $date = date('Y-m-d', strtotime($i['value']));
      }
      if($i['name'] == 'Užitná plocha') {
        $prices_by_date[$date][] = round($row['price_czk']['value_raw']/$i['value'], 0);
      }
    }
  }

  foreach($prices_by_date as $key => $value) {
    $prices_by_date[$key] = array_sum($value)/count($value);
  }
  ksort($prices_by_date);

  date_default_timezone_set('Europe/Prague');
  $now_date = date("d-m-Y H:i:s");
  echo '<div class="container col-sm-12">';
    echo '<h2 class="text-center">Real Estate</h2>';
    DateTimePick(key($prices_by_date), end(array_keys($prices_by_date)));
    GraphFromArray($prices_by_date, $FilterStartDate, $FilterEndDate);
  echo '</div>'
?>
</body>
