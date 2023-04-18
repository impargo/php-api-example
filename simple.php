<?php 
use GraphQL\Client;

$endpoint = 'https://dev.backend.impargo.eu/';
$token = $_ENV['TOKEN'];
$data = require('./data/simpleOrder.json');

$query = <<<'GRAPHQL'
mutation importOrder($data: OrderImportInput!){
  importOrder(data:$data) {
    _id
    order {
      reference
      route {
        distance
        time
      }
    }
  }
}
GRAPHQL;

$client = new Client($endpoint);
$request = $client->request($query, ['data' => $data]);
$response = $client->send($request, ['authorization' => $token]);
$result = $response->getData();

echo "Order successfully created:\n" . json_encode($result, JSON_PRETTY_PRINT);
?>