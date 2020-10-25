<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PWD", "");
define("DB_NAME", "");

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PWD;
    private $db_name = DB_NAME;


    private $connection;
    private $error;
    private $query;
    private $db_conn = false;

    public function __construct()
    {
        //set pdo connection
        $DNS = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
        $options = array(
            PDO::ATTR_PERSISTENT => TRUE,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->connection = new PDO(
                $DNS,
                $this->user,
                $this->pass,
                $options
            );
            $this->db_conn = true;
        } catch (PDOException $ex) {
            $this->error = $ex->getMessage() . PHP_EOL;
            $this->db_conn = false;
        }
    }

    public function getError()
    {
        return $this->error;
    }

    public function isConnected()
    {
        return $this->db_conn;
    }
    // Prepare statement with Query
    public function query($query)
    {
        $this->query = $this->connection->prepare($query);
    }
    //Execute the Prepared Statement
    public function execute()
    {
        return $this->query->execute();
    }
    //Get result set as an array of objects
    public function resultSet()
    {
        $this->execute();
        return $this->query->fetchAll(PDO::FETCH_OBJ);
    }
    //Get the tuple count of the records
    public function rowCount()
    {
        return $this->query->rowCount();
    }
    //Get a single record as an object
    public function single()
    {
        $this->execute();
        return $this->query->fetch(PDO::FETCH_OBJ);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->query->bindValue($param, $value, $type);
    }
}

// $db = new Database();
// echo $db->isConnected() ? "DB Connected" . PHP_EOL : "DB Not Connected" . PHP_EOL;
// if (!$db->isConnected()) {
//     echo $db->getError();
//     die('Unable to connect');
// }

