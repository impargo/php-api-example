<?php

// autoload all composer dependencies
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

// parse env file where is api key stored
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$data = file_get_contents(__DIR__ . '/data/simpleOrder.json');
$data = json_decode($data, true);

$query = <<<GRAPHQL
    mutation importOrder(\$data: OrderImportInput!){
      importOrder(data:\$data) {
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

$client = new Client();
$response = $client->post(
    'https://dev.backend.impargo.eu/',
    [
        RequestOptions::HEADERS => [
            'Content-Type' => 'application/json',
            'authorization' => $_ENV['IMPARGO_API_KEY'],
        ],
        RequestOptions::JSON => [
            'query' => $query,
            'variables' => [
                'data' => $data,
            ],
        ],
    ]
);

$jsonResult = json_decode($response->getBody()->getContents(), true);

printf("Tolls details of order: \n %s \n", json_encode($jsonResult, JSON_PRETTY_PRINT));
