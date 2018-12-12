<?php
namespace Logisting;

putenv('GDFONTPATH=' . realpath('.'));

include 'Models/Config.php';
include 'Models/Drawing.php';
include 'Models/Kitchen.php';
include 'Models/Map.php';
include 'Models/Order.php';
include 'Models/Routing.php';
include 'Models/Route.php';
include 'Models/Timeline.php';

class App
{
  private $orders;
  private $routes;
  private $map;
  private $ordersCount = 10;
  private $ordersSentCount;
  private $routeMaxCapacity;

  public function __construct()
  {
    $config = Config::getInstance();

    $this->drawing = new Drawing();
    $this->kitchen = new Kitchen();
    $this->map = new Map($config, $this->drawing);
    $this->routing = new Routing();
    $this->timeline = new Timeline($this->drawing);

    $this->orders = [];
    $this->routes = [];
    $this->ordersCount = $config->get('orders_count');
    $this->ordersSentCount = $this->ordersCount;
    $this->routeMaxCapacity = $config->get('route_max_capacity');
  }

  public function run()
  {
    $currentTime = 0;

    $this->ordersSentCount = $this->ordersCount;
    $i = 0;
    $currentRoute = new Route($this->routeMaxCapacity);
    $maxIterations = $this->ordersCount + 100;
    while ($this->ordersSentCount > 0) {
      if (count($this->orders) < $this->ordersCount) {
        $order = new Order($i, $currentTime);
        $order->setCookingTime($this->kitchen->getCookingTime($order));
        $this->orders[] = $order;
        $this->kitchen->addOrder($order);
      }
      $this->kitchen->calc($currentTime);
      $cookedOrders = $this->kitchen->getCookedOrders();
      if (is_array($cookedOrders)) {
        foreach ($cookedOrders as $cookedOrder) {
          if ($currentRoute->canAddOrder()) {
            $currentRoute->addOrder($cookedOrder);
            $this->kitchen->removeCookedOrder($cookedOrder);
            $this->ordersSentCount--;
          } else {
            $this->routes[] = $currentRoute;
            $currentRoute = new Route($this->routeMaxCapacity);
            $currentRoute->addOrder($cookedOrder);
            $this->kitchen->removeCookedOrder($cookedOrder);
            $this->ordersSentCount--;
          }
        }
      }
      $currentTime += rand(1, 30);
      $i++;
      $maxIterations--;
      if ($maxIterations <= 0) {
        break;
      }
    }
    if (count($currentRoute->getOrders()) > 0) {
      $this->routes[] = $currentRoute;
    }

    $this->map->setRoutes($this->routes);
    $this->map->saveImage();
    $this->timeline->setRoutes($this->routes);
    $this->timeline->saveImage();
    echo $this->outputOrders();
    echo $this->outputRoutes();
  }

  public function outputRoutes(): string
  {
    $output = '';

    $output .= '<h2>Routes</h2>';
    $output .= '<table>';
    $output .= '<tr>
      <td>id</td>
      <td>orders</td>
    </tr>';
    foreach ($this->routes as $idx => $route) {
      $routeOrders = $route->getOrders();
      $routeOrdersIds = [];
      if (is_array($routeOrders)) {
        foreach ($routeOrders as $order) {
          $routeOrdersIds[] = $order->getId();
        }
      }

      $output .= '<tr>
        <td>'.$idx.'</td>
        <td>'.implode(', ', $routeOrdersIds).'</td>
      </tr>';
    }
    $output .= '<table>';

    return $output;
  }

  public function outputOrders(): string
  {
    $output = '';

    $output .= '<h2>Orders</h2>';
    $output .= '<table>';
    $output .= '<tr>
      <td>id</td>
      <td>createdOn</td>
      <td>cookedOn</td>
      <td>coords</td>
    </tr>';
    foreach ($this->orders as $order) {
      $coords = $order->getCoords();
      $output .= '<tr>
        <td>'.$order->getId().'</td>
        <td>'.$order->getCreatedOn().'</td>
        <td>'.$order->getCookedOn().'</td>
        <td>'.$coords[0].'; '.$coords[1].'</td>
      </tr>';
    }
    $output .= '<table>';

    return $output;
  }
}
