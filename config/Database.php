<?php
namespace config;
use PDO;
use PDOException;
class Database
{
  private $servername = 'localhost';
  private $database = 'asm_php2_ver2';
  private $username = 'root';
  private $password = '';
  private $charset = "utf8mb4";
  private $pdo;

public function __construct()
{
  try {
    $dsn = "mysql:host=$this->servername;dbname=$this->database;charset=$this->charset";
    $this->pdo = new PDO($dsn, $this->username, $this->password);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection error. Please try again later.");
}

}

public function query($sql, $param = [])
{
  $stmt = $this->pdo->prepare($sql);
  if ($param) {
    $stmt->execute($param);
  } else {
    $stmt->execute();
  }
  return $stmt;
}
public function beginTransaction()
{
    return $this->pdo->beginTransaction();
}

public function commit()
{
  return $this->pdo->commit();
}

public function rollBack()
{
  return $this->pdo->rollBack();
}

public function getAll($sql, $param = [])
{
  $stmt = $this->query($sql, $param); 
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getOne($sql, $param = [])
{
  $stmt = $this->query($sql, $param);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function insert($sql, $param = [])
{
  $this->query($sql, $param);
  return $this->pdo->lastInsertId();
}


public function update($sql, $param = [])
{
  $stmt = $this->query($sql, $param);
  return $stmt->rowCount();
}


public function delete($sql, $param = [])
{
  $stmt = $this->query($sql, $param);
  return $stmt->rowCount();
}


public function __destruct()
{
  unset($this->pdo);
}
}
