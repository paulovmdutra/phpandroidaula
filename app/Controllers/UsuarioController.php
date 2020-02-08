<?php

namespace App\Controllers;

use App\Repositories\UsuarioRepository;
use Psr\Container\ContainerInterface;

class UsuarioController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index($request, $response)
    {
        return "UsuarioController:index11111111111";
    }

    public function inserir($request, $response)
    {
        $db     = $this->container->get('database');
        
        $parametros = $request->getParsedBody();

        $usuarioRepository = new UsuarioRepository($db);

        $resultado = $usuarioRepository->inserir($parametros);

        return $response->withJson($resultado);

    }

    public function logar($request, $response)
    {
        $db     = $this->container->get('database');
        
        $parametros = $request->getParsedBody();

        $login = filter_var($parametros["login"], FILTER_SANITIZE_STRING);
        $senha = filter_var($parametros["senha"], FILTER_SANITIZE_STRING);

        $usuarioRepository = new UsuarioRepository($db);

        $resultado = $usuarioRepository->logar($login, $senha);

        return $response->withJson($resultado);

    }


}

?>