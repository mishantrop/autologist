<?php
namespace Logisting;

class Route
{
  private $maxCount;
  private $orders;

  public function __construct(int $maxCount)
  {
    $this->id = rand(1000, 9999);
    $this->maxCount = $maxCount;
    $this->orders = [];
  }

  public function addOrder(Order $order)
  {
    if ($this->canAddOrder()) {
      $this->orders[] = $order;
    }
  }

  public function canAddOrder(): int
  {
    return count($this->orders) < $this->maxCount;
  }

  public function getOrders(): array
  {
    return $this->orders;
  }

  public function getId(): int
  {
    return $this->id;
  }
}