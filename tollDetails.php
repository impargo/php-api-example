<?php 
require_once 'vendor/autoload.php';

use GraphQL\Client;
use GraphQL\Request;

$client = Client::create('http://dev.backend.impargo.eu/graphql');

$data = require("./data/simpleOrder.json");
$token = getenv('TOKEN');

$query = <<< 'GRAPHQL'
mutation importOrder($data: OrderImportInput!){
  importOrder(data:$data) {
    _id
    order {
      route {
        distance
        time
        routeDetails {
          tolls {
            summary {
              amount
            }
            byCountryAndTollSystem {
              name
              amount
            }
          }
        }
      }
    }
  }
}
GRAPHQL;

$request = new Request($query, [
    'data' => $data
]);

$request->setHeader('authorization', $token);

$promise = $client->runQuery($request);
$data = $promise->then(function ($result) {
    return $result->data;
});

echo "Tolls details of order:\n" . json_encode($data, JSON_PRETTY_PRINT);
?>