<?php
use GraphQL\Client;
use GraphQL\Query;

$data = require("./data/advancedOrder.json");
$endpoint = getenv('ENDPOINT') ?: "https://dev.backend.impargo.eu/";
$token = getenv('TOKEN');

$query = <<<'GQL'
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
GQL;

$client = new Client($endpoint);
$query = new Query($query);
$query->setVariables(['data' => $data]);
$query->setHeader('authorization', $token);

$result = $client->runQuery($query);
echo "Order successfully created:\n", json_encode($result->data, JSON_PRETTY_PRINT), "\n";
?>