<?php
/*
 * Author: Dahir Muhammad Dahir
 * Date: 26-April-2020 12:05 AM
 * About: I will tell you later
 */


namespace fingerprint;


use mysqli;

date_default_timezone_set("Asia/Jakarta");

class Database
{
    // private host = $ENV["localhost"];
    // private user = $ENV['main'];
    // private password = $ENV["DB_PASSWORD"];
    // private database = $ENV["DB_DATABASE"];
    private $connection;

    function __construct()
    {
        // global $ENV;
        $this->connection = new mysqli(getenv("DB_HOST"), getenv('DB_USERNAME'), getenv("DB_PASSWORD"), getenv('DB_DATABASE'), getenv("DB_PORT"));
        // $this->connection = new mysqli($ENV["DB_HOST"], $ENV['DB_USERNAME'], $ENV["DB_PASSWORD"], $ENV['DB_DATABASE'], $ENV["DB_PORT"]);
        if (mysqli_connect_errno()) {
            printf("Connection Failed: %s\n",  mysqli_connect_errno());
            exit();
        }
    }

    function getUserInfo($username): array
    {
        $sql_query = "SELECT * FROM users WHERE username=?";
        $param_type = "s";
        $param_array = [$username];
        $result = $this->select($sql_query, $param_type, $param_array);
        return $result;
    }

    function select($sql_query, $sql_param_type = "", $param_array = array())
    {
        if ($statement = $this->connection->prepare($sql_query)) {
            if (!empty($sql_param_type) && !empty($param_array)) {
                $this->bindQueryParams($statement, $sql_param_type, $param_array);
            }

            $statement->execute();
            $sql_query_result = $statement->get_result();

            if ($sql_query_result->num_rows > 0) {
                while ($row = $sql_query_result->fetch_assoc()) {
                    $result_set[] = $row;
                }
            }

            if (!empty($result_set)) {
                return $result_set;
            }
        }
    }

    function insert($sql_query, $sql_param_type = "", $param_array = array())
    {
        if ($statement = $this->connection->prepare($sql_query)) {
            $this->bindQueryParams($statement, $sql_param_type, $param_array);
            $statement->execute();
            $insert_id = $statement->insert_id;
            return $insert_id;
        }
    }

    function execute()
    {
        //pass
    }

    function update($sql_query, $sql_param_type = "", $param_array = array())
    {
        if ($statement = $this->connection->prepare($sql_query)) {
            if (!empty($sql_param_type) and !(empty($param_array))) {
                $this->bindQueryParams($statement, $sql_param_type, $param_array);
                $statement->execute();
                $statement->store_result();
                return $statement->affected_rows;
            }
        }
        return 0;
    }

    function bindQueryParams($statement, $sql_param_type, $param_array = array())
    {
        $param_value_reference[] = &$sql_param_type;
        for ($i = 0; $i < count($param_array); $i++) {
            $param_value_reference[] = &$param_array[$i];
        }

        call_user_func_array(array($statement, 'bind_param'), $param_value_reference);
    }
}
