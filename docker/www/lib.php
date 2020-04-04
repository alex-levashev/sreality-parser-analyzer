<?php

function DropDown($name, $default_option, $array) {
  echo '<div class="form-group">';
    echo '<div class="input-group" id="' . $name . '">';
      echo '<select style="width: 200px !important;" type="text" class="form-control" name="' . $name . '" type="' . $name . '">';
        if($_GET[$name] == '' OR $_GET[$name] == 'NA') {
          echo '<option selected value="NA">' . $default_option . '</option>';
        } else {
          echo '<option value="NA">' . $default_option . '</option>';
        }
        foreach($array as $key => $value) {
          if($_GET[$name] == $key) {
            echo '<option selected value="' . $key . '">' . $value . '</option>';
          } else {
            echo '<option value="' . $key . '">' . $value . '</option>';
          }
        }
      echo '</select>';
    echo '</div>';
  echo '</div>';
}

function DateTimeOptionsPick($mindate, $maxdate) {
  global $FilterStartDate, $FilterEndDate;
  global $category_type_cb, $category_main_cb, $category_sub_cb, $something_more1, $something_more2, $something_more3, $building_type_search, $ownership, $floor_number, $building_condition, $czk_price_summary_order2, $usable_area, $furnished, $estate_age, $locality_country_id, $locality_region_id, $locality_district_id;

  echo '<form>';
    echo '<div class="center container col-sm-12">';

      echo '<div class="col-sm-3"></div>';

      echo '<div class="col-sm-3">';
        echo '<div class="form-group">';
          echo '<div class="input-group date" id="datetimepicker6">';
                if ($_GET["start"] == '') { $FilterStartDate = $mindate; } else { $FilterStartDate = $_GET["start"]; }
                echo '<input type="text" class="form-control" name="start" type="start" value="' . $FilterStartDate .'" />';
                echo '<span class="input-group-addon">';
                  echo '<span class="glyphicon glyphicon-calendar"></span>';
                echo '</span>';
          echo '</div>';
        echo '</div>';
      echo '</div>';

      echo '<div class="col-sm-3">';
        echo '<div class="form-group">';
          echo '<div class="input-group date" id="datetimepicker7">';
            if ($_GET["end"] == '') { $FilterEndDate = $maxdate; } else { $FilterEndDate = $_GET["end"]; }
            echo '<input type="text" class="form-control" name="end" type="end" value="' . $FilterEndDate .'" />';
            echo '<span class="input-group-addon">';
              echo '<span class="glyphicon glyphicon-calendar"></span>';
            echo '</span>';
          echo '</div>';
        echo '</div>';
      echo '</div>';

      echo '<div class="col-sm-3"></div>';
    echo '</div>';

    echo '<div class="center container col-sm-12">';

      echo '<div class="col-sm-2">';
        DropDown("category_sub_cb", "Flat Type", $category_sub_cb);
      echo '</div>';

      echo '<div class="col-sm-2">';
        DropDown("building_type_search", "Building Type", $building_type_search);
      echo '</div>';

      echo '<div class="col-sm-2">';
        DropDown("ownership", "Ownership", $ownership);
      echo '</div>';

      echo '<div class="col-sm-2">';
        DropDown("building_condition", "Condition", $building_condition);
      echo '</div>';

      echo '<div class="col-sm-2">';
        DropDown("furnished", "Furnished", $furnished);
      echo '</div>';

      echo '<div class="col-sm-2">';
        DropDown("locality_district_id", "Location", $locality_district_id);
      echo '</div>';

    echo '</div>';

    echo '<div class="center container col-sm-12">';
      echo '<center><button type="submit" class="btn btn-secondary">Submit</button></center>';
    echo '</div>';

  echo '</form>';

  echo '<script type="text/javascript">';
  echo 'moment.updateLocale("en", {';
    echo 'week: { dow: 1 }';
    echo '});';
    echo '$(function () {';
    echo '$("#datetimepicker6").datetimepicker({';
      echo 'format: "YYYY-MM-DD",';
    echo '});';
    echo '$("#datetimepicker7").datetimepicker({';
      echo 'useCurrent: false,';
      echo 'format: "YYYY-MM-DD",';
      echo '});';
      echo '$("#datetimepicker6").on("dp.change", function (e) {';
        echo '$("#datetimepicker7").data("DateTimePicker").minDate(e.date);';
      echo '});';
      echo '$("#datetimepicker7").on("dp.change", function (e) {';
        echo '$("#datetimepicker6").data("DateTimePicker").maxDate(e.date);';
      echo '});';
    echo '});';
  echo '</script>';
}

function GraphFromArray($init_array, $start_date, $end_date) {
  $dates = ('"' . implode('", "', array_keys($init_array)) . '"');
  $values = implode(', ', array_values($init_array));
  if($init_array == []) {
    echo '<pre>No Data!</pre>';
  } else {
    echo "<script>";
    echo 'function draw_graph_js() {';
      echo 'var config = {';
        echo 'type: "line",';
        echo 'data: {';
          echo 'labels: glabels,';
          echo 'datasets: [';
            echo '{';
              echo 'label: "Average Price, CZK",';
              echo 'data: gdata,';
              echo 'borderColor: "green",';
              echo 'backgroundColor: "white",';
              echo 'fill: false,';
            echo '},';
          echo ']';
        echo '},';
        echo 'options: {';
          echo 'scales: {';
            echo 'xAxes: [{';
              echo 'ticks: {';
                echo 'autoSkip: false,';
                echo 'maxRotation: 90,';
                echo 'minRotation: 90,';
                echo 'major: {';
                  echo 'fontColor: "black"';
                echo '},';
                echo 'minor: {';
                  echo 'fontColor: "black",';
                echo '},';
              echo '},';
              echo 'type: "time",';
              echo 'time: {';
                echo 'min: ' . strtotime($start_date)*1000 . ',';
                echo 'max: ' . strtotime($end_date)*1000 . ',';
                if(strtotime($end_date)-strtotime($start_date) < 5094000) {
                  echo 'unit: "day",';
                } else {
                  echo 'unit: "week",';
                }
                echo 'unitStepSize: 1,';
                echo 'displayFormats: {';
                  echo '"millisecond": "YYYY-MM-DD",';
                  echo '"second": "YYYY-MM-DD",';
                  echo '"minute": "YYYY-MM-DD",';
                  echo '"hour": "YYYY-MM-DD",';
                  echo '"day": "YYYY-MM-DD",';
                  echo '"week": "YYYY-MM-DD",';
                  echo '"month": "YYYY-MM-DD",';
                  echo '"quarter": "YYYY-MM-DD",';
                  echo '"year": "YYYY-MM-DD",';
                echo '},';
              echo '}';
            echo '}],';
            echo 'yAxes:[{';
                echo 'ticks: {';
                    echo 'suggestedMin: 0,';

                echo '}';
            echo '}],';
          echo '},';
        echo '}';
      echo '};';

      echo 'var ctx = document.getElementById(gburn).getContext("2d");';
      echo 'new Chart(ctx, config);';
    echo '}';
    echo '</script>';
    echo '<div class="container col-sm-12">';
      echo '<h5 class="text-center">Average Price, CZK</h5>';
      echo '<canvas id="price_graph" width="100%" height="40%"></canvas>';
      echo '<script>';
        echo 'var gburn = "price_graph";';
        echo 'var glabels = [' . $dates . '];';
        echo 'var gdata = [' . $values . '];';
        echo 'draw_graph_js()';
      echo '</script>';
    echo '</div>';
  }
}

?>
