<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    $container['database'] = function ($c) {

        $db = NULL;

        $logger = $c->get('logger');
        $settdatabase = $c->get('settings')['database'];

        $host = $settdatabase['host'];
        $username = $settdatabase['username'];
        $password = $settdatabase['password'];
        $dbname   = $settdatabase['dbname'];

        $dns = "mysql:host=$host;dbname=$dbname";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ];
        
        try{

            $db = new PDO($dns, $username, $password, $options);

        }catch(PDOException $ex){

            $logger->info($ex->getMessage());

        }

        return $db;
    };


    $container['UsuarioController'] = function($c){
        return new \App\Controllers\UsuarioController($c);
    };

    

};
