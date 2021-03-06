<?php
require_once('inc/dashboard.class.php');
$dashboard = new Dashboard(true, true, false, false);
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title><?php echo $dashboard->appName ?> - Index</title>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
<?php require_once('include.css'); ?>
  </head>
  <body>
<?php require_once('header.php'); ?>
    <div class='container-fluid'>
      <canvas id='chart'></canvas>
    </div>
    <nav class='navbar text-center'>
      <select class='btn btn-sm btn-outline-success ml-auto mr-2 id-sensor_id' data-storage='sensor_id'>
        <option value='0'>-- Sensor --</option>
<?php
foreach ($dashboard->getObjects('sensors') as $sensor) {
  echo "        <option value='{$sensor['sensor_id']}'>{$sensor['name']}</option>" . PHP_EOL;
}
?>
      </select>
      <select class='btn btn-sm btn-outline-success mr-auto id-hours' data-storage='hours'>
        <option value='0'>-- Period --</option>
<?php
$periods = [
  1 => '1 hour',
  3 => '3 hours',
  6 => '6 hours',
  12 => '12 hours',
  24 => '1 day',
  24 * 7 => '1 week',
  24 * 7 * 2 => '2 weeks',
  24 * 30 => '1 month',
  24 * 30 * 3 => '3 months',
  24 * 30 * 6 => '6 months',
  24 * 30 * 9 => '9 months',
  24 * 365 => '1 year'
];
foreach ($periods as $hours => $period) {
  echo "        <option value='{$hours}'>{$period}</option>" . PHP_EOL;
}
?>
      </select>
    </nav>
<?php require_once('include.js'); ?>
    <script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js' integrity='sha384-F13mJAeqdsVJS5kJv7MZ4PzYmJ+yXXZkt/gEnamJGTXZFzYgAcVtNg5wBDrRgLg9' crossorigin='anonymous'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js' integrity='sha384-0saKbDOWtYAw5aP4czPUm6ByY5JojfQ9Co6wDgkuM7Zn+anp+4Rj92oGK8cbV91S' crossorigin='anonymous'></script>
    <script>
      $(document).ready(function() {
        var timer;
        var config = {
          type: 'line',
          data: {
            datasets: [{
              label: 'Temperature',
              backgroundColor: 'rgba(255, 0, 0, 0.3)',
              borderColor: 'rgb(255, 0, 0)',
              borderWidth: 1,
              pointRadius: 2,
              fill: false,
              yAxisID: 'temperature'
            }, {
              label: 'Humidity',
              backgroundColor: 'rgba(30, 144, 255, 0.3)',
              borderColor: 'rgb(30, 144, 255)',
              borderWidth: 1,
              pointRadius: 2,
              fill: false,
              yAxisID: 'humidity'
            }]
          },
          options: {
            legend: {position: 'bottom'},
            scales: {
              xAxes: [{display: true, type: 'time'}],
              yAxes: [{
                display: true,
                id: 'temperature',
                position: 'left',
                scaleLabel: {display: true, labelString: 'Temperature (<?php echo $dashboard->temperature['key'] ?>)'}
              }, {
                display: true,
                id: 'humidity',
                position: 'right',
                scaleLabel: {display: true, labelString: 'Humidity (%)'},
                gridLines: {display: false}
              }]
            }
          }
        };
        var chart = new Chart($('#chart'), config);

        function getReadings() {
          $.get('src/action.php', {"func": "getReadings", "sensor_id": $('select.id-sensor_id').val(), "hours": $('select.id-hours').val()})
            .done(function(data) {
              if (data.success) {
                config.options.scales.yAxes[0].ticks = data.data.temperature;
                config.options.scales.yAxes[1].ticks = data.data.humidity;
                config.data.datasets[0].data = data.data.temperatureData;
                config.data.datasets[1].data = data.data.humidityData;
                chart.update();
              }
            })
            .fail(function(jqxhr, textStatus, errorThrown) {
              if (jqxhr.status == 401) {
                location.reload();
              } else {
                console.log(`getReadings failed: ${jqxhr.status} (${jqxhr.statusText}), ${textStatus}, ${errorThrown}`);
              }
            })
            .always(function() {
              timer = setTimeout(getReadings, $('select.id-hours').val() * 1000);
            });
        };

        $.each(['sensor_id', 'hours'], function(key, value) {
          if (result = localStorage.getItem(value)) {
            if ($(`select.id-${value} option[value="${result}"]`).length) {
              $(`select.id-${value}`).val(result);
            }
          }
        });

        if ($('select.id-sensor_id').val() != 0 && $('select.id-hours').val() != 0) {
          getReadings();
        }

        $('select.id-sensor_id, select.id-hours').change(function() {
          clearTimeout(timer);
          localStorage.setItem($(this).data('storage'), $(this).val());
          if ($('select.id-sensor_id').val() != 0 && $('select.id-hours').val() != 0) {
            getReadings();
          } else {
            delete config.options.scales.yAxes[0].ticks;
            delete config.options.scales.yAxes[1].ticks;
            delete config.data.datasets[0].data;
            delete config.data.datasets[1].data;
            chart.update();
          }
        });

        $('button.id-nav').click(function() {
          location.href=$(this).data('href');
        });
      });
    </script>
  </body>
</html>
