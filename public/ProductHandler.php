<?php 
include_once '../private/initialize.php';

use MyApp\classes\ProductFactory;

class ProductHandler
{
  
    public function handleRequest()
    {
        $response = array();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $args = [];

            foreach ($_POST as $key => $value) {
              $args[$key] = $value ?? null;
            }

            $type = $_POST['selectedOption'];
            if ($type == 'furniture') {
                $args['dimensions'] = $args['height'] . 'x' . $args['width'] . 'x' . $args['length'];
            }

            $product = ProductFactory::createProduct(ucfirst($type), $args);
            $result = $product->save($product);

            if ($result === true) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['error'] = $result;
            }
        } else {
            $response['success'] = false;
            $response['error'] = 'Invalid request method.';
        }

        // Output the response as a JSON-encoded object
        echo json_encode($response);
    }
}

// create an instance of the ProductHandler class and handle the request
$handler = new ProductHandler();
$handler->handleRequest();
