<?php
//Ustawienia połączenia z bazą danych
$servername = "***************";
$dbname = "**********";
$username = "****************";
$password = "****************";

//Utwórz połączenie
$conn = new mysqli($servername, $username, $password, $dbname);
//Sprawdź połączenie
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}
$n_limit=600;
if (isset($_POST['n_limit']))
{
    $n_limit = $_POST['n_limit']; 
}
$sql="SELECT id, value1, value2, value3, reading_time FROM Sensor order by reading_time desc limit " .$n_limit;
$sql2="SELECT id, value1, value2, value3, reading_time FROM SensorSzymon order by reading_time desc limit " .$n_limit;

$result = $conn->query($sql);
$result2 = $conn->query($sql2);

while ($data = $result->fetch_assoc())
{
    $sensor_data[] = $data;
}
while ($data = $result2->fetch_assoc())
{
    $sensor_data2[] = $data;
}

$readings_time = array_column($sensor_data, 'reading_time');
$readings_time2 = array_column($sensor_data2, 'reading_time');

$value1 = json_encode(array_reverse(array_column($sensor_data, 'value1')), JSON_NUMERIC_CHECK);
$value2 = json_encode(array_reverse(array_column($sensor_data, 'value2')), JSON_NUMERIC_CHECK);
$value3 = json_encode(array_reverse(array_column($sensor_data, 'value3')), JSON_NUMERIC_CHECK);
$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);

$value11 = json_encode(array_reverse(array_column($sensor_data2, 'value1')), JSON_NUMERIC_CHECK);
$value22 = json_encode(array_reverse(array_column($sensor_data2, 'value2')), JSON_NUMERIC_CHECK);
$value33 = json_encode(array_reverse(array_column($sensor_data2, 'value3')), JSON_NUMERIC_CHECK);
$reading_time2 = json_encode(array_reverse($readings_time2), JSON_NUMERIC_CHECK);

$result->free();
$result2->free();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
      <meta name="viewport" content="width=device-width, initial-scale=0.65">
      <meta name="description" content="AGH Dziarmaga Krzysztof">
      <meta name="Keywords" content="Krzysztof Dziarmaga, AGH, IMiR">
      <meta name="Author" content="Krzysztof Dziarmaga">
      <meta charset="utf-8">
      <title>K. Dziarmaga</title>
      <link rel="stylesheet" type="text/css" href="style.css">
      <script src="https://code.highcharts.com/highcharts.js"></script>
      <script src="https://code.highcharts.com/stock/highstock.js"></script>
      <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
      <style>
          body {
              min-width: 310px;
              max-width: 1280px;
              height: 500px;
              margin: 0 auto;
          }
          h2 {
              font-family: Arial;
              font-size: 2.5rem;
              text-align: center;
          }
      </style>
</head>
<body>
        <div id="container">
            <div id="header">
                <h1>Krzysztof Dziarmaga, Aleksander Dusiński, Szymon Folek</h1>
            </div>
            <div id="content">
                <div id="nav">
                    <h3>Nawigacja</h3>
                    <ul>
                        <li><a class="selected" href="index.html">Home</a></li>
                        <li><a href="index2.html">HTML Form</a></li>
                        <li><a href="./Folder_pliki/">Pliki</a></li>
                        <li><a href="esp-chart.php">Projekt ISP</a></li>
<a href='http://www.counterliczniki.com'>licznik odwiedzin strony</a> <script type='text/javascript' src='https://www.freevisitorcounters.com/auth.php?id=abe6d260897ec88d1cce1659531a9c089244dae3'></script>
<script type="text/javascript" src="https://www.freevisitorcounters.com/en/home/counter/780229/t/10"></script>
                    </ul>
                </div>
                <div id="main">
                    <h2>ESP Rozproszony system monitorujący parametry środowiskowe</h2>
		    <form method="post">
		    <input type="text" name="n_limit" placeholder="Liczba wyświetlanych punktów pomiarowych" size="40">
		    <input type="submit" value="Wyświetl">
		    </form>
                    <div id="myDIV">
                      <button class="btn active" onclick="changeData(0)">ESP32 DS Itaka</button>
                      <button class="btn" onclick="changeData(1)">ESP32 DS Babilon</button>
                    </div>
                    <div id="chart1" class="container"></div>
                    <div id="chart2" class="container"></div>
                    <div id="chart3" class="container"></div>
                </div>
            </div>
            <div id="footer">
                Copyright &copy; 2021 K. Dz.
            </div>
        </div>
	
<script>

var value1 = <?php echo $value1; ?>;
var value2 = <?php echo $value2; ?>;
var value3 = <?php echo $value3; ?>;
var reading_time = <?php echo $reading_time; ?>;

function changeData(index) {
  if (index == 0){
    var value1 = <?php echo $value1; ?>;
    var value2 = <?php echo $value2; ?>;
    var value3 = <?php echo $value3; ?>;
    var reading_time = <?php echo $reading_time; ?>;
  }else if (index == 1){
    var value1 = <?php echo $value11; ?>;
    var value2 = <?php echo $value22; ?>;
    var value3 = <?php echo $value33; ?>;
    var reading_time = <?php echo $reading_time2; ?>;
  }
  // document.write(index);
  chartT.update({
    series: [{
    name: 'Temperatura',
    //type: 'area',
    //fillOpacity: 0.1,
    showInLegend: false,
    data: value1
  }],
    xAxis: { 
    type: 'datetime',
    dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  }
  });

  chartH.update({
    series: [{
    name: 'Wilgotność',
    //type: 'area',
    //fillOpacity: 0.1,
    showInLegend: false,
    data: value2
  }],
  xAxis: { 
    type: 'datetime',
    dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  }
  });

  chartP.update({
    series: [{
    name: 'Ciśnienie',
    //type: 'area',
    //fillOpacity: 0.1,
    showInLegend: false,
    data: value3
  }],
  xAxis: { 
    type: 'datetime',
    dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  }
  });
}
// aktywacja przycisków
var header = document.getElementById("myDIV");
var btns = header.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}

var chartT = new Highcharts.Chart({
  chart:{ renderTo : 'chart1',
          zoomType: 'x'  },
  title: { text: 'Temperatura' },
  subtitle: {
                text: document.ontouchstart === undefined ?
                    'Przeciągnij po wykresie, aby zbliżyć' : 'Rozciągnij, aby powiększyć'
            },
  series: [{
    name: 'Temperatura',
    //type: 'area',
    //fillOpacity: 0.1,
    showInLegend: false,
    data: value1
  }],
  plotOptions: {
    line: { 
      dataLabels: { enabled: true }
    },
    series: { color: '#48ef80',
              animation: {
                duration: 2000
              } }
  },
  xAxis: { 
    type: 'datetime',
    dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Temperatura (Celsjusz)' }
    //title: { text: 'Temperatura (Fahrenheit)' }
  },
  credits: { enabled: false }
});

var chartH = new Highcharts.Chart({
  chart:{ renderTo:'chart2',
          zoomType: 'x' },
  title: { text: 'Wilgotność' },
  subtitle: {
                text: document.ontouchstart === undefined ?
                    'Przeciągnij po wykresie, aby zbliżyć' : 'Rozciągnij, aby powiększyć'
            },
  series: [{
    name: 'Wilgotność',
    //type: 'area',
    //fillOpacity: 0.1,
    showInLegend: false,
    data: value2
  }],
  plotOptions: {
    line: {
      dataLabels: { enabled: true }
    },
    series: { color: '#076de9',
	      animation: {
                duration: 1000
              } }
  },
  xAxis: {
    type: 'datetime',
    //dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Wilgotność (%)' }
  },
  credits: { enabled: false }
});


var chartP = new Highcharts.Chart({
  chart:{ renderTo:'chart3',
          zoomType: 'x'  },
  title: { text: 'Ciśnienie' },
  subtitle: {
                text: document.ontouchstart === undefined ?
                    'Przeciągnij po wykresie, aby zbliżyć' : 'Rozciągnij, aby powiększyć'
            },
  series: [{
    name: 'Ciśnienie',
    //type: 'area',
    //fillOpacity: 0.1,
    showInLegend: false,
    data: value3
  }],
  plotOptions: {
    line: {
      dataLabels: { enabled: true }
    },
    series: { color: '#ef486c',
	      animation: {
                duration: 1000
              } }
  },
  xAxis: {
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Ciśnienie (hPa)' }
  },
  credits: { enabled: false }
});


</script>
</body>
</html>
