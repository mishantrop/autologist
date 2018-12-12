<?php
namespace Logisting;

class Map
{
  private $center;
  private $drawing;
  private $height;
  private $width;

  public function __construct(Config $config, Drawing $drawing)
  {
    $this->width = $config->get('map_width');
    $this->height = $config->get('map_height');
    $this->center = [$this->width/2, $this->width/2];
    $this->routes = [];
    $this->drawing = $drawing;
  }

  private function drawHome($image)
  {
    $homeEllipseColor = imagecolorallocate($image, 255, 0, 0);
    imagefilledellipse(
      $image,
      $this->center[0] - 15,
      $this->center[1] - 15,
      40,
      40,
      $homeEllipseColor
    );
  }

  public function saveImage()
  {
    $mapImage = imagecreate($this->width, $this->height);

    $mapBackgroundColor = imagecolorallocate($mapImage, 255, 255, 255);

    ImageFilledRectangle($mapImage, 0, 0, $this->width, $this->height, $mapBackgroundColor);
    $this->drawHome($mapImage);

    foreach ($this->routes as $route) {
      $prevCoords = [$this->center[0], $this->center[1]];
      $orders = $route->getOrders();
      $routeLineColor = $this->drawing->getRandomColor($mapImage);

      foreach ($orders as $order) {
        $orderCoords = $order->getCoords();
        $this->drawing->drawLine(
          $mapImage,
          $prevCoords[0] - 15,
          $prevCoords[1] - 15,
          $orderCoords[0] + $this->center[0] - 15,
          $orderCoords[1] + $this->center[1] - 15,
          $routeLineColor,
          count($orders)
        );
        $prevCoords = [
          $orderCoords[0] + $this->center[0] - 15,
          $orderCoords[1] + $this->center[1] - 15,
        ];
        $this->drawOrder($mapImage, $order, $prevCoords);
      }
    }

    imagepng($mapImage, 'map.png');   
    imagedestroy($mapImage);
  }

  public function setRoutes($routes)
  {
    $this->routes = $routes;
  }

  private function drawOrder($image, Order $order, $prevCoords)
  {
    $orderEllipseColor = imagecolorallocate($image, 0, 0, 255);
    $coords = $order->getCoords();
    imagefilledellipse(
      $image,
      $coords[0] + $this->center[0] - 45,
      $coords[1] + $this->center[1] - 45,
      90,
      90,
      $orderEllipseColor
    );
    $fontColor = imagecolorallocate($image, 255, 255, 255);
    imagettftext(
      $image,
      48,
      0,
      $coords[0] + $this->center[0] - 60,
      $coords[1] + $this->center[1] - 25,
      $fontColor,
      'OpenSansRegular',
      $order->getId()
    );
  }
}