<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->get('/teste/{id}', function(Request $request, Response $response, array $args)
    {
        return $args['id'];
    });

    $app->get('/teste/bancodedados/conexao', function(Request $request, Response $response, array $args) use ($container)
    {

        $logger = $container->get('logger');
        $db     = $container->get('database');

        if ($db != NULL)
        {
            try{

                $stmt = $db->prepare("SELECT * FROM USUARIO");
                $stmt->execute();
                
                return "Consulta realizada com sucesso!";

            }catch(PDOException $ex){
                $logger->info($ex->getMessage());
                return "Falha na realização da consulta: ".$ex->getMessage();
            }  
        }
        
        return "Houve uma problema durante a requisição";
        
    });    

    $app->get('/usuario/index', 'UsuarioController:index');

    $app->post('/usuario/logar', 'UsuarioController:logar');

    $app->post('/usuario/inserir', 'UsuarioController:inserir');

};
