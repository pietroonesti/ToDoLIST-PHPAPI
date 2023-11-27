<?php
declare (strict_types=1);
/* 
 * TodoList API corso Udemy
 * Pietro Onesti  * 
 * 2023  * 
 */

//front controller CONTROLLO DI OGNI OPERAZIONE

//include 'src/TaskController.php';
//require dirname(__DIR__) . "/vendor/autoload.php";
require "../vendor/autoload.php";

//definisco l'ErrorHandler
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

//dotenv da composer
$dotenv = Dotenv\Dotenv::createImmutable(dirname("../.env"));
$dotenv->load();

//prendo la url
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode ("/" , $path);
$resource = $parts[3];
$id = $parts[4] ?? null;
//echo $resource . ' - '. $id;
//echo '<br>';
//var_dump($parts);
//echo '<br>';
//echo $_SERVER["REQUEST_METHOD"];


if ($resource != "task"){
    http_response_code(400);
    echo 'RICHIESTA ERRATA';
    
    exit();
}
//print_r($parts);

//require dirname(__DIR__) . "/src/TaskController.php";

header("Content-type: application/json; charset=UTF-8");

//database connection with composer DOTENV
$database = new Database($_ENV["DB_HOST"],$_ENV["DB_NAME"],$_ENV["DB_USER"],$_ENV["DB_PASS"]);

//test connessione
$database->getConnection();

//richiamo il TaskGateway
$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway);
$controller ->processRequest($_SERVER["REQUEST_METHOD"], $id);