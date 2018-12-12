<?php
namespace Logisting;

class Timeline
{
  private $drawing;
  private $end;
  private $height;
  private $mode;
  private $padding;
  private $routes;
  private $start;
  private $width;
  private $zoom;

  public function __construct(Drawing $drawing)
  {
    $this->height = 0;
    $this->padding = 1;
    $this->routes = [];
    $this->width = 0;
    $this->mode = 'routes';
    $this->zoom = 10;
    $this->drawing = $drawing;
  }

  private function drawGrid($image)
  {
    $lineColor = imagecolorallocate($image, 100, 100, 100);
    // Вертикальные линии
    for ($x = 0; $x < 300; $x++) {
      $this->drawing->drawLine($image, $x * $this->zoom, 0, $x * $this->zoom, $this->width, $lineColor, 1);
    }
    // Горизонтальные линии
    for ($y = 0; $y < 300; $y++) {
      $this->drawing->drawLine($image, 0, $y * $this->zoom, $this->width, $y * $this->zoom, $lineColor, 1);
    }
    
    $lineColor = imagecolorallocate($image, 0, 0, 0);
    // Вертикальные линии
    for ($x = 0; $x < 300; $x++) {
      if ($x % 10 === 0) {
        $this->drawing->drawLine($image, $x * $this->zoom, 0, $x * $this->zoom, $this->width, $lineColor, 2);
      }
    }
    // Горизонтальные линии
    for ($y = 0; $y < 300; $y++) {
      if ($y % 10 === 0) {
        $this->drawing->drawLine($image, 0, $y * $this->zoom, $this->width, $y * $this->zoom, $lineColor, 2);
      }
    }
  }

  private function drawRoutes($image)
  {
    $lineIdx = 0;
    foreach ($this->routes as $route) {
      $orders = $route->getOrders();
      $routeLineColor = $this->drawing->getRandomColor($image);
      foreach ($orders as $order) {
        $createdOn = $order->getCreatedOn();
        $cookedOn = $order->getCookedOn();
        ImageFilledRectangle(
          $image,
          $createdOn * $this->zoom,
          $lineIdx * $this->zoom,
          $cookedOn *$this->zoom,
          $lineIdx * $this->zoom + $this->zoom,
          $routeLineColor
        );
        $lineIdx++;
      }
    }
  }

  private function drawOrders($image)
  {
    foreach ($this->orders as $lineIdx => $order) {
      $routeLineColor = $this->drawing->getRandomColor($image);
      $createdOn = $order->getCreatedOn();
      $cookedOn = $order->getCookedOn();
      ImageFilledRectangle(
        $image,
        $createdOn * $this->zoom,
        $lineIdx * $this->zoom,
        $cookedOn *$this->zoom,
        $lineIdx * $this->zoom + $this->zoom,
        $routeLineColor
      );
      $lineIdx++;
    }
  }

  public function setRoutes($routes)
  {
    $this->mode = 'routes';
    $this->routes = $routes;
    $end = 0;
    $ordersCount = 0;
    foreach ($routes as $route) {
      $orders = $route->getOrders();
      foreach ($orders as $order) {
        $ordersCount++;
        if ($end < $order->getCookedOn()) {
          $end = $order->getCookedOn();
        }
      }
    }
    $this->height = $ordersCount * $this->zoom;
    $this->width = $end * $this->zoom;
  }

  public function setOrders($orders)
  {
    $this->mode = 'orders';
    $this->orders = $orders;
    $end = 0;
    $ordersCount = 0;
    foreach ($orders as $order) {
      $ordersCount++;
      if ($end < $order->getCookedOn()) {
        $end = $order->getCookedOn();
      }
    }
    $this->height = $ordersCount * $this->zoom;
    $this->width = $end * $this->zoom;
  }

  public function setTime($start, $end)
  {
    $this->start = $start;
    $this->end = $end;
  }

  public function saveImage()
  {
    $timelineImage = imagecreate($this->width, $this->height);

    $mapBackgroundColor = imagecolorallocate($timelineImage, 255, 255, 255);
    ImageFilledRectangle($timelineImage, 0, 0, 1000, 500, $mapBackgroundColor);

    $this->drawGrid($timelineImage);
    switch ($this->mode) {
      case 'routes':
        $this->drawRoutes($timelineImage);
        break;
      case 'orders':
        $this->drawOrders($timelineImage);
        break;
      default:
        break;
    }

    imagepng($timelineImage, 'timeline.png');   
    imagedestroy($timelineImage);
  }
}