<?php
namespace Logisting;

class Kitchen
{
  private $ordersCooking;
  private $ordersCooked;

  public function __construct()
  {
    $this->ordersCooking = [];
    $this->ordersCooked = [];
  }

  public function addOrder(Order $order) {
    $this->ordersCooking[$order->getId()] = $order;
  }

  public function removeOrder(int $orderId)
  {
    if (isset($this->ordersCooking[$orderId])) {
      unset($this->ordersCooking[$orderId]);
    }
    if (isset($this->ordersCooked[$orderId])) {
      unset($this->ordersCooked[$orderId]);
    }
  }

  public function getCookingOrdersCount(): int
  {
    return count($this->ordersCooking);
  }

  public function getCookedOrdersCount(): int
  {
    return count($this->ordersCooked);
  }

  public function getCookedOrders(): array
  {
    return $this->ordersCooked;
  }

  // За сколько минут кухня сможет приготовить указанный заказ
  public function getCookingTime(Order $order): int
  {
    return rand(10, 30);
  }

  public function calc(int $currentTime)
  {
    foreach ($this->ordersCooking as $order) {
      if ($order->getCookedOn() <= $currentTime) {
        $this->ordersCooked[$order->getId()] = $order;
        if (isset($this->ordersCooking[$order->getId()])) {
          unset($this->ordersCooking[$order->getId()]);
        }
      }
    }
  }

  public function removeCookedOrder(Order $order)
  {
    if (isset($this->ordersCooked[$order->getId()])) {
      unset($this->ordersCooked[$order->getId()]);
    }
  }
}