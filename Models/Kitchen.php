<?php
namespace Logisting;

class Kitchen
{
  private $cookingLimit;
  private $ordersCooked;
  private $ordersCooking;
  private $ordersQueue;

  public function __construct()
  {
    $this->cookingLimit = 2;
    $this->ordersCooked = [];
    $this->ordersCooking = [];
    $this->ordersQueue = [];
  }

  public function addOrder(Order $order) {
    if ($this->getCookingOrdersCount() >= $this->cookingLimit) {
      $this->ordersQueue[$order->getId()] = $order;
    } else {
      $this->ordersCooking[$order->getId()] = $order;
    }
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

  public function getQueueOrdersCount(): int
  {
    return count($this->ordersQueue);
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
    // Готовится всё, что должно
    $cookedCount = $this->subcalc($this->cookingLimit, $currentTime);
    if ($this->getCookingOrdersCount() >= $this->cookingLimit) {

    }
    $this->subcalc($this->cookingLimit - $cookedCount, $currentTime);
  }

  private function subcalc($max, int $currentTime)
  {
    // Сколько было приготовлено в текущей итерации
    $cookedCount = 0;
    if ($cookedCount <= $max) {
      foreach ($this->ordersCooking as $order) {
        if ($order->getCookedOn() <= $currentTime) {
          $this->ordersCooked[$order->getId()] = $order;
          if (isset($this->ordersCooking[$order->getId()])) {
            unset($this->ordersCooking[$order->getId()]);
            $cookedCount++;
          }
        }
      }
    }
    return $cookedCount;
  }

  public function removeCookedOrder(Order $order)
  {
    if (isset($this->ordersCooked[$order->getId()])) {
      unset($this->ordersCooked[$order->getId()]);
    }
  }
}