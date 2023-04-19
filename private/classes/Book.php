<?php

namespace MyApp\classes;
use MyApp\classes\Product;

class Book extends Product implements ProductInterface {

  static protected $columns = ['id', 'sku', 'name', 'price', 'weight_kg'];

  public function __construct($args=[]) {
    parent::__construct($args);
    $this->weight_kg = $args['weight_kg'] ?? '';

  }

  public function saveToDatabase($stmt, $attributes) {
    $stmt->bind_param("ssdd", $attributes['sku'], $attributes['name'], $attributes['price'], $this->weight_kg);
  }
}

?>