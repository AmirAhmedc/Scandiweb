<?php

namespace MyApp\classes;
use MyApp\classes\Product;

class Furniture extends Product implements ProductInterface {
  static protected $columns = ['id', 'sku', 'name', 'price', 'dimensions'];
  
  public function __construct($args=[]) {
    parent::__construct($args);
    $this->dimensions = $args['dimensions'] ?? '';
  }

  // public function save() {
  //   $attributes = $this->sanitized_attributes();
  //   $sql = "INSERT INTO products (";
  //   $sql .= join(', ', array_keys($attributes));
  //   $sql .= ") VALUES ";
  //   $sql .= "(?, ?, ?, ?)";
  //   $stmt = self::$database->prepare($sql);
  //   $stmt->bind_param("ssds", $attributes['sku'], $attributes['name'], $attributes['price'], json_encode([$this->height, $this->width, $this->length]));
  //   $result = $stmt->execute();
  //   if($result) {
  //     $this->id = self::$database->insert_id;
  //   }
  //   $stmt->close();
  //   return $result;
  // }

  public function saveToDatabase($stmt, $attributes) {
    $stmt->bind_param("ssds", $attributes['sku'], $attributes['name'], $attributes['price'], $this->dimensions );
}
 }

?>