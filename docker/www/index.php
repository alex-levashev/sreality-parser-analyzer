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
  if(file_exists('constants.php')) { require_once("constants.php"); } else { echo('No constants.php file found!'); }
  if(file_exists('lib.php')) { require_once("lib.php"); } else { echo('No lib.php file found!'); }

  echo '<div class="container col-sm-12">';
    echo '<h2 class="text-center">Real Estate</h2>';

    $request_array = explode('&',$_SERVER["QUERY_STRING"]);
    foreach($request_array as $item) {

    }

    $mng = new MongoDB\Driver\Manager("mongodb://root:root@mongodb:27017");

    $filter = [];

    if($_GET['category_sub_cb'] != '' AND $_GET['category_sub_cb'] != 'NA') {
      $filter["seo.category_sub_cb"] = (int)$_GET['category_sub_cb'];
    }

    if($_GET['building_type_search'] != '' AND $_GET['building_type_search'] != 'NA') {
      $filter["codeItems.building_type_search"] = (int)$_GET['building_type_search'];
    }

    if($_GET['ownership'] != '' AND $_GET['ownership'] != 'NA') {
      $filter["codeItems.ownership"] = (int)$_GET['ownership'];
    }

    if($_GET['building_condition'] != '' AND $_GET['building_condition'] != 'NA') {
      $filter["items.name"] = 'Stav objektu';
      $filter["items.value"] = $building_condition[$_GET['building_condition']];
    }

    if($_GET['furnished'] != '' AND $_GET['furnished'] != 'NA') {
      $filter["items.name"] = 'Vybavení';
      $filter["items.value"] = $furnished[$_GET['furnished']];
    }

    if($_GET['locality_district_id'] != '' AND $_GET['locality_district_id'] != 'NA') {
      $filter["locality_district_id"] = (int)$_GET['locality_district_id'];
    }

    $options = [];
    // echo('<pre>');
    // var_dump($filter);
    // echo('</pre>');


    $query = new MongoDB\Driver\Query($filter, $options);
    $rows = $mng->executeQuery("real_estate.raw_requests", $query);
    $prices_by_date = [];
    $count = 0;
    foreach ($rows as $row) {
      $count += 1;
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

    echo('<center>TOTAL NUMBER OF RECORDS IS ') . $count . '</center><br>';
    DateTimeOptionsPick(key($prices_by_date), end(array_keys($prices_by_date)));
    GraphFromArray($prices_by_date, $FilterStartDate, $FilterEndDate);

  echo '</div>'



?>
</body>
