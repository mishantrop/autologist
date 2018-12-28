<?php
namespace Logisting;

class Distribution
{
  public function __construct(Config $config, Drawing $drawing)
  {
    $this->orders = [];
    $this->drawing = $drawing;

    $this->width = 1000;
    $this->height = 1000;
  }

  private function getData() {
    return [
      0 => 1,
      16 => 2,
      43 => 3,
      59 => 4,
      65 => 5,
      73 => 6,
      76 => 7,
      100 => 8,
      112 => 9,
      123 => 10,
    ];
  }

  public function saveImage()
  {
    $distributionImage = imagecreate($this->width, $this->height);
    $mapBackgroundColor = imagecolorallocate($distributionImage, 255, 255, 255);
    ImageFilledRectangle($distributionImage, 0, 0, $this->width, $this->height, $mapBackgroundColor);
    $routeLineColor = imagecolorallocate($distributionImage, 0, 0, 255);
    $data = $this->getData();
    $prevCoords = [0, 0];
    foreach ($data as $t => $count) {
      $this->drawing->drawLine(
        $distributionImage,
        $prevCoords[0] * 10,
        $this->height - ($prevCoords[1] * 50),
        ($t * 10),
        $this->height - ($count * 50),
        $routeLineColor,
        2
      );
      $prevCoords = [
        $t,
        $count,
      ];
    }

    imagepng($distributionImage, 'distribution.png');   
    imagedestroy($distributionImage);
  }

  public function setOrders($orders)
  {
    $this->orders = $orders;
  }
}