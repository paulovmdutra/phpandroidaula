<?php 

namespace App\Repositories;

use PDO;
use PDOException;

class Repository
{
    protected $db;

    protected function __construct($db = NULL) 
    {
        $this->db = $db;
    }

}
