<?php

namespace App\Db;

use \PDO;

class Database{

    
    /**
     * Host de conexão como banco de dados
     * @var string
     */
    const HOST = '';

    /**
     * Nomde do banco de dados
     * @var string
     */
    const NAME = 'wdev_vagas';

    /**
     * Usuário do banco de dados
     * @var string
     */
    const USER = 'root';

    /**
     * Senha de acesso do banco de dados
     * @var string
     */
    const PASS = 'toor';

    /**
     * Nome da tabela a ser manipulada
     * @var string
     */
    private $table;

    /**
     * Instância de conexão com banco de dados
     * @var PDO
     */
    private $connection;

    /**
     * Define a tabela e instancia a conexão
     * @param string $table
     */
    public function __construct($table = null){
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * Método responsável por criar uma conexão com o banco de dados
     */
    private function setConnection(){
        try{
            $this->connection = new PDO("sqlite:" . self::NAME);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOExeption $e){
            die('ERROR: '.$e->getMessage());
        }
    }

    /**
     * Método responsável por executar queries dentro do banco de dados 
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function execute($query, $params = []){
        try{    
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;           

          }catch(PDOExeption $e){
            die('ERROR: '.$e->getMessage());
        }

    }



    /**
     * Método responsável por inserir dados no banco
     * @param arrays $values [ field => value ]
     * @return interger
     */
     public function insert($values){
        //dados da query
        $fields = array_keys($values);
        $binds  = array_pad([], count($fields), '?');
        
        //monta a query
        $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';
        
        //pega os valores da requisição
        $value = array_values($values);
 
        //executa o insert
        $this->execute($query, $value);

        //retorna o id inserido
        return $this->connection->lastInsertId();
    }


    /**
     * Método responsável por executar uma consulta no banco de dados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * return PDOStatement
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*'){
        //dados da query
        $where = strlen($where) ? 'WHERE '.$where : '';
        $order = strlen($order) ? 'ORDER BY '.$order : '';
        $limit = strlen($limit) ? 'LIMIT '.$limit : '';

        //monta a query
        $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

        //executa a query
        return $this->execute($query);
    }

    /**
     * Método responsável por executar atualizações no banco de dados
     * @param string $where
     * @param array $values [ field => value ]
     * @return boolean
     */
    public function update($where, $values){
        //dados da query
        $fields = array_keys($values);

        //valores da query
        $value = array_values($values);

        //monta a query
        $query = 'UPDATE '.$this->table.' SET '.implode('=?,',$fields).'=? WHERE '.$where;
        //echo "<pre>";   print_r($value);   echo "</pre>";  exit; 

        //executa a query
        $this->execute($query, $value);

        //retorna sucesso
        return true;
    }


    /**
     * Método responsável por excluir dados do banco
     * @param string $where
     * @return boolean
     */
    public function delete($where){
        //monta a query
        $query = 'DELETE FROM '.$this->table.' WHERE '.$where;

        //executa a query
        $this->execute($query);

        //retorna sucesso
        return true;        
    }


}