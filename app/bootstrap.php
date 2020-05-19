<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Database\Connector;

$conn = new Connector();
$db = $conn->getConn();
