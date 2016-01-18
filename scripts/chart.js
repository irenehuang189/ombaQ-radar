// Graphical timeline for showing posts volume during the report period
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawPostVolumeChart);
google.charts.setOnLoadCallback(drawPostTypeChart);
function drawPostVolumeChart() {
  var data = google.visualization.arrayToDataTable([
    ['Time', 'Images', 'Videos'],
    ['2013',  1000,      400],
    ['2014',  1170,      460],
    ['2015',  660,       1120],
    ['2016',  1030,      540]
  ]);

  var options = {
    title: 'Posts Volume in 3 Days',
    vAxis: {minValue: 0},
    width: 900,
    height: 500
  };

  var chart = new google.visualization.AreaChart(document.getElementById('post-volume_chart'));
  chart.draw(data, options);
}

function drawPostTypeChart() {
  var data = google.visualization.arrayToDataTable([
    ['Task', 'Hours per Day'],
    ['Work',     11],
    ['Eat',      2],
    ['Commute',  2],
    ['Watch TV', 2],
    ['Sleep',    7]
  ]);

  var options = {
    title: 'My Daily Activities',
    pieHole: 0.4,
    width: 900,
    height: 500
  };

  var chart = new google.visualization.PieChart(document.getElementById('post-type_chart'));
  chart.draw(data, options);
}