<?php

namespace MyApp\classes;
use MyApp\classes\Product;

class Dvd extends Product implements ProductInterface {
  static protected $columns = ['id', 'sku', 'name', 'price', 'size'];

  public function __construct($args=[]) {
    parent::__construct($args);
    $this->size = $args['size'] ?? '';
  }
  
  public function saveToDatabase($stmt, $attributes) {
    $stmt->bind_param("ssdi", $attributes['sku'], $attributes['name'], $attributes['price'], $this->size);
  }
}

?>