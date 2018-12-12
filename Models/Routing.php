<?php
namespace Logisting;

class Routing
{
  public function getDistanceBetweenOrders(Order $order1, Order $order2): int
  {
    $coords1 = $order1->getCoords();
    $coords2 = $order2->getCoords();

    return $this->getDistance($coords1[0], $coords1[1], $coords2[0], $coords2[1]);
  }

  private function getDistance(int $x1, int $y1, int $x2, int $y2): int
  {
    $dx = $x1 - $x2;
    $dy = $y1 - $y2;
    return sqrt($dx * $dx + $dy * $dy); 
  }
}