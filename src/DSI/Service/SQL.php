<?php

namespace DSI\Service;

class SQL
{
    private $query = NULL;
    private $resource = NULL;
    private static $link = NULL;
    private static $credentials = array();
    public static $useUTF8 = TRUE;

    public $returnField = NULL;

    public function __construct($query = NULL)
    {
        if (!isset(self::$link) OR !self::$link) {
            if (!isset(self::$credentials['host']) OR !self::$credentials['host'])
                self::$credentials['host'] = 'localhost';

            ### Connect to mysql server
            self::$link = mysqli_connect(self::$credentials['host'], self::$credentials['username'], self::$credentials['password'], self::$credentials['db']);
            if (!self::$link) {
                http_response_code(503);
                echo 'Service Unavailable. Please try again later';
                die();
            }

            $filename = date('Y-m-d') . '.sql.log';
            $handle = fopen(__DIR__ . '/../../../logs/sql/' . $filename, 'a');
            fwrite($handle, date('Y:m:d H:i:s') . "\n" . $query . "\n\n");
            fclose($handle);

            if (self::$useUTF8 == TRUE) {
                self::$link->query('SET CHARACTER SET utf8');
                self::$link->query('SET SESSION collation_connection ="utf8_general_ci"');
            }
        }


        $this->query = $query;
        return $this;
    }

    public function __destruct()
    {
        if ($this->resource)
            $this->resource->free_result();
    }

    public function query()
    {
        $tmp = self::$link->query($this->query);
        if (!$tmp)
            $this->debug();

        return true;
    }

    public function fetch($returnField = NULL)
    {
        ### Handle ->fetch(field1, field2); {
        if (func_num_args() > 1) {
            return $this->fetch(func_get_args());
        }
        ### }

        if (!$returnField) $returnField = $this->returnField;

        $this->resource = self::$link->query($this->query);

        if ($this->resource and $returnField) {
            $new = $this->resource->fetch_assoc();
            return $new[$returnField];
        } elseif ($this->resource) {
            return $this->resource->fetch_assoc();
        } else {
            $this->debug();
        }
    }

    public function fetch_all($returnField = NULL)
    {
        ### Handle ->fetch_all(field1, field2); {
        if (func_num_args() > 1) {
            return $this->fetch_all(func_get_args());
        }
        ### }

        if (!$returnField) $returnField = $this->returnField;

        $this->resource = self::$link->query($this->query);

        if ($this->resource) {
            $return = array();
            while ($row = $this->resource->fetch_assoc()) {
                if (is_string($returnField)) {
                    $return[] = $row[$returnField];
                } else {
                    $return[] = $row;
                }
            }

            return $return;
        } else {
            $this->debug();
        }
    }

    public function last_insert_id()
    {
        return self::$link->insert_id;
    }

    public function insert_id()
    {
        return self::$link->insert_id;
    }

    public function affected_rows()
    {
        return self::$link->affected_rows;
    }

    public function pr()
    {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        echo '<pre>';
        print_r($this->query);
        echo "</pre>\n";
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function debug()
    {
        throw new SQL_couldNotExecuteQuery('<pre>' . var_export(array(
                'sql' => $this->query,
                'error' => self::$link->error
            ), 1) . '</pre>');
    }

    public static function transaction()
    {
        return (new SQL('START TRANSACTION'))->query();
    }

    public static function commit()
    {
        return (new SQL('COMMIT'))->query();
    }

    public static function rollback()
    {
        return (new SQL('ROLLBACK'))->query();
    }

    public static function credentials($arr)
    {
        self::$credentials = $arr;
    }
}

class SQL_couldNotExecuteQuery extends \Exception
{
}