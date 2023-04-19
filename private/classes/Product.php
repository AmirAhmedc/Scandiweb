<?php

namespace MyApp\classes;

interface ProductInterface
{
    public function saveToDatabase($stmt, $attributes);
}

abstract class ProductFactory
{
    public static function createProduct($product_type, $args = [])
    {
        $productClass = "MyApp\\classes\\$product_type";
        if (!class_exists($productClass)) {
            throw new \Exception('Invalid product type.');
        }

        return new $productClass($args);
    }
}

class Product
{
    protected static $database;
    protected static $columns = [];
    public $id;
    public $sku;
    public $name;
    public $price = 0.0;
    public $weight_kg = 0.0;
    public $size = 0;
    public $width = 0;
    public $length = 0;
    public $height = 0;
    public $dimensions = '';

    public function __construct($args = [])
    {
        $this->sku = $args['sku'] ?? '';
        $this->name = $args['name'] ?? '';
        $this->price = $args['price'] ?? '';
    }

    // Set the database
    public static function set_database($database)
    {
        self::$database = $database;
    }

    // Save the items into the database

    public function save(Product $product)
    {
        $attributes = $this->sanitized_attributes();

        if (!($product instanceof ProductInterface)) {
            throw new \Exception('Invalid product type.');
        }

        $placeholders = implode(',', array_fill(0, count($attributes), '?'));
        $columns = implode(',', array_keys($attributes));

        $sql = "INSERT INTO products ($columns) VALUES ($placeholders)";
        $stmt = self::$database->prepare($sql);

        $product->saveToDatabase($stmt, $attributes);

        try {
            $stmt->execute();
            $this->id = $stmt->insert_id;
        } catch (\Exception$e) {
            echo "Error: " . $e->getMessage();
            return false;
        }

        $stmt->close();
        return true;
    }

    // Select all items from the database
    public static function select_all()
    {
        $sql = "SELECT * FROM products";
        $result = self::$database->query($sql);

        if ($result === false) {
            throw new \Exception("Database query failed: " . self::$database->error);
        }

        $object_array = [];
        while ($record = $result->fetch_assoc()) {
            $object_array[] = self::instantiate($record);
        }

        $result->free();

        return $object_array;
    }

    // Create objects from the database items
    protected static function instantiate($record)
    {
        $object = new self;
        foreach ($record as $property => $value) {
            if (property_exists($object, $property)) {
                $object->$property = $value;
            }
        }
        return $object;
    }

    // Get the columns and their values for the given class
    public function attributes(): array
    {
        $columns = array_filter(static::$columns, function ($column) {
            return $column !== 'id';
        });

        $attributes = [];
        foreach ($columns as $column) {
            $attributes[$column] = $this->$column;
        }

        return $attributes;
    }

    // Escape the values to make sure they can be used in a SQL statement
    protected function sanitized_attributes()
    {
        $sanitized = [];
        foreach ($this->attributes() as $key => $value) {
            $sanitized[$key] = self::$database->real_escape_string($value);
        }

        return $sanitized;
    }

    // Delete item from database
    public static function delete(array $selected): bool
    {
        if (empty($selected)) {
            return false;
        }

        $placeholders = rtrim(str_repeat('?,', count($selected)), ',');
        $sql = "DELETE FROM products WHERE id IN ($placeholders)";

        $stmt = self::$database->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($selected)), ...$selected);
        $result = $stmt->execute();

        if ($result) {
            header("Location: index.php");
        }

        return $result;
    }

}
