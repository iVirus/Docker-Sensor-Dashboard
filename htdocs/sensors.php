<?php
require_once('inc/dashboard.class.php');
$dashboard = new Dashboard(true, true, true, false);
?>
<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Sensor Dashboard - Sensors</title>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css' integrity='sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB' crossorigin='anonymous'>
    <link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootswatch/4.1.1/darkly/bootstrap.min.css' integrity='sha384-ae362vOLHy2F1EfJtpMbNW0i9pNM1TP2l5O4VGYYiLJKsaejqVWibbP6BSf0UU5i' crossorigin='anonymous'>
    <link rel='stylesheet' href='//use.fontawesome.com/releases/v5.1.0/css/all.css' integrity='sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt' crossorigin='anonymous'>
  </head>
  <body>
    <nav class='navbar'>
      <button class='btn btn-sm btn-outline-success id-nav' data-href='<?php echo dirname($_SERVER['PHP_SELF']) ?>'>Home</button>
      <button class='btn btn-sm btn-outline-info ml-auto mr-2 id-nav' data-href='sensors.php'>Sensors</button>
      <button class='btn btn-sm btn-outline-info mr-2 id-nav' data-href='users.php'>Users</button>
      <button class='btn btn-sm btn-outline-info id-nav' data-href='events.php'>Events</button>
    </nav>
    <div class='container'>
      <table class='table table-striped table-hover table-sm'>
        <thead>
          <tr>
            <th><button type='button' class='btn btn-sm btn-outline-success id-add'>Add</button></th>
            <th>Sensor ID</th>
            <th>Sensor Name</th>
            <th>Min. Temperature</th>
            <th>Max. Temperature</th>
            <th>Min. Humidity</th>
            <th>Max. Humidity</th>
          </tr>
        </thead>
        <tbody>
<?php
foreach ($dashboard->getObjects('sensors') as $sensor) {
  $tableClass = $sensor['disabled'] ? 'text-warning' : 'table-default';
  echo "          <tr class='{$tableClass}'>" . PHP_EOL;
  echo "            <td><button type='button' class='btn btn-sm btn-outline-info id-details' data-sensor_id='{$sensor['sensor_id']}'>Details</button></td>" . PHP_EOL;
  echo "            <td>{$sensor['sensor_id']}</td>" . PHP_EOL;
  echo "            <td>{$sensor['name']}</td>" . PHP_EOL;
  echo "            <td>{$sensor['min_temperature']} {$dashboard->temperature['key']}</td>" . PHP_EOL;
  echo "            <td>{$sensor['max_temperature']} {$dashboard->temperature['key']}</td>" . PHP_EOL;
  echo "            <td>{$sensor['min_humidity']} %</td>" . PHP_EOL;
  echo "            <td>{$sensor['max_humidity']} %</td>" . PHP_EOL;
  echo "          </tr>" . PHP_EOL;
}
?>
        </tbody>
      </table>
    </div>
    <div class='modal fade id-modal'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <form>
            <div class='modal-header'>
              <h5 class='modal-title'></h5>
            </div>
            <div class='modal-body'>
              <div class='form-row'>
                <div class='form-group col'>
                  <label>Sensor Name <sup class='text-danger'>*</sup></label>
                  <input class='form-control' id='name' type='text' name='name' required>
                </div>
                <div class='form-group col'>
                  <label>Access Token <sup class='text-danger id-required'>*</sup></label>
                  <input class='form-control id-token' id='token' type='text' name='token' minlength='16' maxlength='16' pattern='[A-Za-z0-9]{16}' required>
                </div>
              </div>
              <div class='form-row'>
                <div class='form-group col'>
                  <label>Min. Temperature</label>
                  <div class='input-group'>
                    <input class='form-control' id='min_temperature' type='number' name='min_temperature' min='<?php echo $dashboard->temperature['min'] ?>' max='<?php echo $dashboard->temperature['max'] ?>' step='0.01'>
                    <div class='input-group-append'>
                      <span class='input-group-text'><?php echo $dashboard->temperature['key'] ?></span>
                    </div>
                  </div>
                </div>
                <div class='form-group col'>
                  <label>Max. Temperature</label>
                  <div class='input-group'>
                    <input class='form-control' id='max_temperature' type='number' name='max_temperature' min='<?php echo $dashboard->temperature['min'] ?>' max='<?php echo $dashboard->temperature['max'] ?>' step='0.01'>
                    <div class='input-group-append'>
                      <span class='input-group-text'><?php echo $dashboard->temperature['key'] ?></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class='form-row'>
                <div class='form-group col'>
                  <label>Min. Humidity</label>
                  <div class='input-group'>
                    <input class='form-control' id='min_humidity' type='number' name='min_humidity' min='0' max='100' step='0.1'>
                    <div class='input-group-append'>
                      <span class='input-group-text'>%</span>
                    </div>
                  </div>
                </div>
                <div class='form-group col'>
                  <label>Max. Humidity</label>
                  <div class='input-group'>
                    <input class='form-control' id='max_humidity' type='number' name='max_humidity' min='0' max='100' step='0.1'>
                    <div class='input-group-append'>
                      <span class='input-group-text'>%</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class='form-row id-notified'>
                <fieldset class='form-group col' disabled>
                  <label>Notifications Sent</label>
                  <div class='form-check'>
                    <input class='form-check-input' id='notified_min_temperature' type='checkbox'>
                    <label class='form-check-label'>Min. Temperature</label>
                  </div>
                  <div class='form-check'>
                    <input class='form-check-input' id='notified_max_temperature' type='checkbox'>
                    <label class='form-check-label'>Max. Temperature</label>
                  </div>
                  <div class='form-check'>
                    <input class='form-check-input' id='notified_min_humidity' type='checkbox'>
                    <label class='form-check-label'>Min. Humidity</label>
                  </div>
                  <div class='form-check'>
                    <input class='form-check-input' id='notified_max_humidity' type='checkbox'>
                    <label class='form-check-label'>Max. Humidity</label>
                  </div>
                </fieldset>
              </div>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-outline-warning id-modify id-volatile'></button>
              <button type='button' class='btn btn-outline-danger mr-auto id-modify' data-action='delete'>Delete</button>
              <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
              <button type='submit' class='btn id-submit'></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src='//code.jquery.com/jquery-3.3.1.min.js' integrity='sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT' crossorigin='anonymous'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' integrity='sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49' crossorigin='anonymous'></script>
    <script src='//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js' integrity='sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T' crossorigin='anonymous'></script>
    <script>
      $(document).ready(function() {
        $('button.id-add').click(function() {
          $('h5.modal-title').text('Add Sensor');
          $('form').removeData('sensor_id').data('func', 'createSensor').trigger('reset');
          $('sup.id-required').addClass('d-none');
          $('input.id-token').prop('required', false).attr('placeholder', 'Will be generated if empty');
          $('div.id-notified').addClass('d-none');
          $('button.id-modify').addClass('d-none').removeData('sensor_id');
          $('button.id-submit').removeClass('btn-info').addClass('btn-success').text('Add');
          $('div.id-modal').modal('toggle');
        });

        $('button.id-details').click(function() {
          $('h5.modal-title').text('Sensor Details');
          $('form').removeData('sensor_id').data('func', 'updateSensor').trigger('reset');
          $('sup.id-required').removeClass('d-none');
          $('input.id-token').removeAttr('placeholder').prop('required', true);
          $('div.id-notified').removeClass('d-none');
          $('button.id-modify').removeClass('d-none').removeData('sensor_id');
          $('button.id-submit').removeClass('btn-success').addClass('btn-info').text('Save');
          $.get('src/action.php', {"func": "getObjectDetails", "type": "sensor", "value": $(this).data('sensor_id')})
            .done(function(data) {
              if (data.success) {
                sensor = data.data;
                $('form').data('sensor_id', sensor.sensor_id);
                $('#name').val(sensor.name);
                $('#token').val(sensor.token);
                $('#min_temperature').val(sensor.min_temperature);
                $('#max_temperature').val(sensor.max_temperature);
                $('#min_humidity').val(sensor.min_humidity);
                $('#max_humidity').val(sensor.max_humidity);
                $('#notified_min_temperature').prop('checked', sensor.notified_min_temperature);
                $('#notified_max_temperature').prop('checked', sensor.notified_max_temperature);
                $('#notified_min_humidity').prop('checked', sensor.notified_min_humidity);
                $('#notified_max_humidity').prop('checked', sensor.notified_max_humidity);
                $('button.id-modify.id-volatile').data('action', sensor.disabled ? 'enable' : 'disable').text(sensor.disabled ? 'Enable' : 'Disable');
                $('button.id-modify').data('sensor_id', sensor.sensor_id);
                $('div.id-modal').modal('toggle');
              }
            })
            .fail(function(jqxhr, textStatus, errorThrown) {
              console.log(`getObjectDetails failed: ${jqxhr.status} (${jqxhr.statusText}), ${textStatus}, ${errorThrown}`);
            });
        });

       $('button.id-modify').click(function() {
          if (confirm(`Want to ${$(this).data('action').toUpperCase()} sensor ${$(this).data('sensor_id')}?`)) {
            $.get('src/action.php', {"func": "modifyObject", "action": $(this).data('action'), "type": "sensor_id", "value": $(this).data('sensor_id')})
              .done(function(data) {
                if (data.success) {
                  location.reload();
                }
              })
              .fail(function(jqxhr, textStatus, errorThrown) {
                console.log(`modifySensor failed: ${jqxhr.status} (${jqxhr.statusText}), ${textStatus}, ${errorThrown}`);
              });
          }
        });

        $('form').submit(function(e) {
          e.preventDefault();
          $.post('src/action.php', {"func": $(this).data('func'), "sensor_id": $(this).data('sensor_id'), "name": $('#name').val(), "token": $('#token').val(), "min_temperature": $('#min_temperature').val(), "max_temperature": $('#max_temperature').val(), "min_humidity": $('#min_humidity').val(), "max_humidity": $('#max_humidity').val()})
            .done(function(data) {
              if (data.success) {
                location.reload();
              }
            })
            .fail(function(jqxhr, textStatus, errorThrown) {
              console.log(`${$(this).data('func')} failed: ${jqxhr.status} (${jqxhr.statusText}), ${textStatus}, ${errorThrown}`);
            });
        });

        $('button.id-nav').click(function() {
          location.href=$(this).data('href');
        });
      });
    </script>
  </body>
</html>
