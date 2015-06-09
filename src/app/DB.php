<?php

class SQLParameter {

    public $parameter;
    public $value;
    public $dataType;

    public function __construct($parameter, $value, $dataType = "string") {
        $this->parameter = $parameter;
        $this->value = $value;
        switch ($dataType) {
            case "string":
                $this->dataType = PDO::PARAM_STR;
                break;
            case "int":
            case "integer":
                $this->dataType = PDO::PARAM_INT;
                break;
            case "bool":
                $this->dataType = PDO::PARAM_BOOL;
                break;
            case "null":
                $this->dataType = PDO::PARAM_NULL;
                break;
            case "datetime":
                $this->dataType = PDO::PARAM_STR;
                $this->value = date('Y-m-d H:i:s', strtotime($value));
            default:
                $this->dataType = PDO::PARAM_STR;
        }
    }

}

class DBConnection {

    public $readOnly;
    public $readWrite;

    public function __construct($readOnly, $readWrite) {
        $this->readOnly = $readOnly;
        $this->readWrite = $readWrite;
    }

}

class DB extends ConnectionSettings {

    private static $dbConnection;
    const DB_ErrorMessage = "Your request was not able to be completed due to a system error has occured";
    
    function EstablishConnections() {
        //Establish Read Only Connection
        if (!isset($mysql) || $mysql == null) {
            $mysqlReader = new PDO("mysql:host=" . self::Host . ";dbname=" . self::DBName, self::ReadOnlyUser, self::ReadOnlyPassword);
        }

        //Establish Read Write Connection
        if (!isset($mysql) || $mysql == null) {
            $mysqlAdmin = new PDO("mysql:host=" . self::Host . ";dbname=" . self::DBName, self::ReadWriteUser, self::ReadWritePassword);
        }

        self::$dbConnection = new DBConnection($mysqlReader, $mysqlAdmin);
    }
    
    function CloseConnections(){
        self::$dbConnection=null;
    }

    function Query($sqlCommand, $sqlParameters = null) {
        try {
            $readOnly = self::$dbConnection->readOnly;
            $readOnly->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($sqlParameters != null) {
                $sqlQuery = $readOnly->prepare($sqlCommand);
                foreach ($sqlParameters as $sqlParameter) {
                    $sqlQuery->bindParam($sqlParameter->parameter, $sqlParameter->value, $sqlParameter->dataType);
                }
                $sqlQuery->execute();
                return $sqlQuery->fetchAll();
            } else {
                $sqlResponse = $readOnly->query($sqlCommand);
                return $sqlResponse;
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    function QueryCount($sqlCommand, $sqlParameters = null) {
        try {
            $readOnly = self::$dbConnection->readOnly;
            $readOnly->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($sqlParameters != null) {
                $sqlQuery = $readOnly->prepare($sqlCommand);
                foreach ($sqlParameters as $sqlParameter) {
					$sqlQuery->bindParam($sqlParameter->parameter, $sqlParameter->value, $sqlParameter->dataType);
				}
				$sqlQuery->execute();
                return sizeof($sqlQuery->fetchAll());
            } else {
                $readOnly->query($sqlCommand);
                $foundRows = $readOnly->query("SELECT FOUND_ROWS()")->fetchColumn();
                return $foundRows;
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    function Execute($sqlCommand, $sqlParameters) {
        try {
            $readWrite = self::$dbConnection->readWrite;
            $readWrite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sqlQuery = $readWrite->prepare($sqlCommand);
            foreach ($sqlParameters as $sqlParameter) {
                $sqlQuery->bindParam($sqlParameter->parameter, $sqlParameter->value, $sqlParameter->dataType);
            }
            $sqlQuery->execute();
            return true;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }
    
    function ExecuteGetIdentity($sqlCommand, $sqlParameters){
        try {
            $readWrite = self::$dbConnection->readWrite;
            $readWrite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sqlQuery = $readWrite->prepare($sqlCommand);
            foreach ($sqlParameters as $sqlParameter) {
                $sqlQuery->bindParam($sqlParameter->parameter, $sqlParameter->value, $sqlParameter->dataType);
            }
            $sqlQuery->execute();
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
        $selectIdentityCommand = "SELECT @@IDENTITY AS identity";
        $sqlQuery = $readWrite->prepare($selectIdentityCommand);
        $sqlQuery->execute();
        $identity = $sqlQuery->fetchAll();
        
        return $identity[0]["identity"];
    }
}

$db = new DB();
$db->EstablishConnections();
unset($db);
?>