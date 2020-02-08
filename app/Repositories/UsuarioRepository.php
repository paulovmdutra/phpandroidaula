<?php 

namespace App\Repositories;

use PDO;
use PDOException;

class UsuarioRepository extends Repository
{
    
    function __construct($db = NULL) 
    {
        parent::__construct($db);
    }

    public function inserir($usuario)
    {
        $resultado = array("status" => "1", 
                           "mensagem" => "", 
                           "dados" => array() );

        if ($this->db != NULL)
        {
            try{

                $nome  = $usuario["nome"];
                $email = $usuario["email"];
                $login = $usuario["login"];
                $senha = $usuario["senha"];

                if (!$this->usuarioExiste($login))
                {

                    $stmt = $this->db->prepare("INSERT INTO USUARIO (nome, email, login, senha) values (:nome, :email, :login, :senha) ");
                    $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
                    $stmt->bindParam(":email",$email, PDO::PARAM_STR);
                    $stmt->bindParam(":login",$login, PDO::PARAM_STR);
                    $stmt->bindParam(":senha",$senha, PDO::PARAM_STR);
                    
                    if ($stmt->execute())
                    {
                        $codigo = $this->db->lastInsertId();

                        $resultado = array("status" => "1", 
                                        "mensagem" => "Registro efetuado!", 
                                        "dados" => array("codigo" => $codigo ) );

                    }

                }
                else
                {
                    $resultado = array("status" => "2", 
                                       "mensagem" => "Usuário já cadastrado!", 
                                       "dados" => array() );
                }

            }catch(PDOException $ex){ 
                $resultado = array("status" => "-100", 
                                   "mensagem" => $ex->getMessage(), 
                                    "dados" => array() );
            }

        }                           

        return $resultado;

    }

    public function logar($login, $senha)
    {
        $resultado = array("status" => "1", 
                           "mensagem" => "", 
                           "dados" => array() );

        if ($this->db != NULL)
        {
            try{

                $stmt = $this->db->prepare("SELECT codigo, nome FROM USUARIO WHERE login = (:login) AND senha = (:senha) ");
                $stmt->bindParam(":login",$login, PDO::PARAM_STR);
                $stmt->bindParam(":senha",$senha, PDO::PARAM_STR);
                $stmt->execute();
                   
                if ($stmt->rowCount() > 0)
                {
                    $linha = $stmt->fetch();
                    
                    $resultado = array("status" => "1", 
                                       "mensagem" => "Usuário logado!", 
                                       "dados" => array("codigo" => $linha["codigo"], 
                                                        "nome"   =>  $linha["nome"] ) );
                }
                else{
                    $resultado = array("status" => "2", 
                                       "mensagem" => "Usuário não encontrado!", 
                                       "dados" => array() );
                }

            }catch(PDOException $ex){ 
                $resultado = array("status" => "-100", 
                    "mensagem" => $ex->getMessage(), 
                    "dados" => array() );
            }  
        }
                           

        return $resultado;
    }


    public function usuarioExiste($login)
    {
        if ($this->db != NULL)     
        {
            $stmt = $this->db->prepare("SELECT codigo FROM USUARIO WHERE login = (:login) ");
            $stmt->bindParam(":login",$login, PDO::PARAM_STR);            
            return $stmt->execute();                  
        }                           

        return false;
    }    


}