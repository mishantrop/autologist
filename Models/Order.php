<?php
namespace Logisting;

class Order
{
  private $_id;
  // Куда заказ должен быть доставлен
  private $_coords;
  // Во сколько заказ должен быть приготовлен
  private $_cookedOn;
  // Время создания
  private $_createdOn;
  // Статус
  private $_status;

  private const STATUS_COOKING = 1;
  private const STATUS_DELIVERING = 2;
  private const STATUS_DELIVERED = 3;

  public function __construct($id, $createdOn)
  {
    $this->_id = $id;
    $this->_createdOn = $createdOn;
    $this->__cookedOn = null;
    $this->_coords = [
      rand(-1000, 1000),
      rand(-1000, 1000),
    ];
    $this->_status = self::STATUS_COOKING;
  }

  public function getCoords(): array
  {
    return $this->_coords;
  }

  public function getId(): string
  {
    return $this->_id;
  }

  public function getCreatedOn(): string
  {
    return $this->_createdOn;
  }

  public function getCookedOn(): int
  {
    return $this->_cookedOn;
  }

  public function getStatusName(): string
  {
    switch ($this->_status) {
      case self::STATUS_COOKING:
        return 'Готовится';
      case self::STATUS_DELIVERING:
        return 'DELIVERING';
      case self::STATUS_DELIVERED:
        return 'Доставлен';
    }
  }

  public function setCookingTime(int $cookingTime)
  {
    $this->_cookedOn = $this->_createdOn + $cookingTime;
  }
}