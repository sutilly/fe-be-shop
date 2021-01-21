<?php


class DatabaseService
{

    protected $productDatabase;

    public function __construct($dbHost, $dbName, $dbUser, $dbPass)
    {
        $this->productDatabase = new PDO("mysql:host=". $dbHost . ";dbname=" . $dbName . ";charset=utf8", $dbUser, $dbPass);
    }

    public function processQuery($sqlQuery)
    {

        $result = array();

        try {
            $sql = $this->productDatabase->query($sqlQuery, PDO::FETCH_ASSOC);
            foreach ($sql as $row) {
                $result[] = $row;
            }
        } catch
        (PDOException $ex) {
            error_log("PDO ERROR: querying database: " . $ex->getMessage() . "\n" . $sqlQuery);
            return $result;
        }

        return $result;
    }

}




