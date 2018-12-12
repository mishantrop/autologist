<?php
namespace Logisting;

class Drawing
{
  public function drawLine($image, $x1, $y1, $x2, $y2, $color, $thick = 2)
  {
    if ($thick == 1) {
      return imageline($image, $x1, $y1, $x2, $y2, $color);
    }
    $t = $thick / 2 - 0.5;
    if ($x1 == $x2 || $y1 == $y2) {
      return imagefilledrectangle(
        $image,
        round(min($x1, $x2) - $t),
        round(min($y1, $y2) - $t),
        round(max($x1, $x2) + $t),
        round(max($y1, $y2) + $t),
        $color
      );
    }
    $k = ($y2 - $y1) / ($x2 - $x1);
    $a = $t / sqrt(1 + pow($k, 2));
    $points = [
      round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
      round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
      round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
      round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
    ];
    imagefilledpolygon($image, $points, 4, $color);
    return imagepolygon($image, $points, 4, $color);
  }

  public function getRandomColor($image)
  {
    return imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
  }
}