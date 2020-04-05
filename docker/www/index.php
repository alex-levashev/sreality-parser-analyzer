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

    if($_SERVER['QUERY_STRING'] != '') {
      $query  = explode('&', $_SERVER['QUERY_STRING']);
      $params = array();

      foreach( $query as $param ) {
          if (strpos($param, '=') === false) $param += '=';
          list($name, $value) = explode('=', $param, 2);
          $params[urldecode($name)][] = (int)urldecode($value);
      }
    }

    if($params['category_sub_cb'] != '') {
        $filter["seo.category_sub_cb"] = [ '$in' => $params['category_sub_cb'] ];
    }

    if($params['building_type_search'] != '') {
      $filter["codeItems.building_type_search"] = [ '$in' => $params['building_type_search'] ];
    }

    if($params['ownership'] != '') {
      $filter["codeItems.ownership"] = [ '$in' => $params['ownership'] ];
    }

    if($params['building_condition'] != '') {
      $filter["items.name"] = 'Stav objektu';
      foreach($params['building_condition'] as $item) {
        $params['building_condition'][array_search($item, $params['building_condition'])] = $building_condition[$item];
      }
      $filter["items.value"] = [ '$in' => $params['building_condition'] ];
    }

    // if($params['furnished'] != '') {
    //   $filter["items.name"] = 'Vybavení';
    //   foreach($params['furnished'] as $item) {
    //     $params['furnished'][array_search($item, $params['furnished'])] = $building_condition[$item];
    //   }
    //   $filter["items.value"] = [ '$in' => $params['furnished'] ];
    // }

    if($params['locality_district_id'] != '') {
      $filter["locality_district_id"] = [ '$in' => $params['locality_district_id'] ];
    }

    $options = [];
    // $filter = [ "seo.category_sub_cb" => [ '$in' => [3,2] ] ];
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
    DateTimeOptionsPick(key($prices_by_date), end(array_keys($prices_by_date)), $params);
    GraphFromArray($prices_by_date, $FilterStartDate, $FilterEndDate);

  echo '</div>'



?>
</body>
