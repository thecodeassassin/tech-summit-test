<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$client = new Predis\Client(getenv('DB_PORT'));

$app->get('/name/:name', function($name) use($client) {
    $client->set('name', $name);
    echo "Name changed to $name";
});

$app->get('/', function () use($client){
    if ($client->exists('name')) {
        $name = $client->get('name');
        echo "Welcome back, $name";
    } else {
        echo "Hello, stranger";
    }
});
$app->run();