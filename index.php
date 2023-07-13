<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$request = $_SERVER['REQUEST_URI'];

$parts = explode("/", $request);


$route = $parts[2];

$method = $_SERVER['REQUEST_METHOD'];



if ($route !== 'users') : {
        echo json_encode([
            "status" => 404,
            "error" => "Page Not Found"
        ]);
    }

else :
    $users = new Users;

    switch ($method) {
        case 'GET':
            $users->selectAll();
            break;
    }


endif;
