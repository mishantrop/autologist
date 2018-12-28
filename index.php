<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Logisting</title>
  <style>
    * {
      font-family: Verdana, tahoma, Arial;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    table td {
      border: 1px solid #222;
    }
  </style>
</head>
<body>
<?php
use Logisting\App;

include 'App.php';

$app = new App();
$app->run();
?>

<h2>Map</h2>
<img src="/map.png" alt="map" style="display: block; margin: 20px 0; max-width: 100%;" />

<h2>Distribution</h2>
<img src="/distribution.png" alt="distribution" style="display: block; margin: 20px 0; max-width: 100%;" />

<h2>Timeline</h2>
<div style="overflow-x: auto;">
  <img src="/timeline.png" alt="timeline" style="display: block; margin: 20px 0;" />
</div>

<footer style="padding: 2rem 1rem; text-align: center;">
  <a href="https://github.com/mishantrop">github.com/mishantrop</a>
</footer>

</body>
</html>

