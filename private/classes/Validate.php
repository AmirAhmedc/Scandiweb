<?php

namespace MyApp\classes;

class Validate {
    
    private $database;
    
    public function __construct($database) {
        $this->database = $database;
    }

    public function checkSkuExists($sku) {
        $stmt = $this->database->prepare("SELECT * FROM products WHERE sku = ?");
        $stmt->bind_param("s", $sku);
        $stmt->execute();
        $result = $stmt->get_result();

        $sku_exists = false;
        if ($result->num_rows > 0) {
            $sku_exists = true;
        }
        return array(
            'exists' => $sku_exists, 
        );
    }
}
