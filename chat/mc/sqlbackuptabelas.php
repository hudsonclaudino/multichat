<?php 
/**
 * This file contains the Backup_Database class wich performs
 * a partial or complete backup of any given MySQL database
 * @author Daniel López Azaña <-->
 * @version 1.0
 */

// Report all errors
error_reporting(E_ALL);

/**
 * Define database parameters here
 */
//define("DB_USER", 'multichatuser');
//define("DB_PASSWORD", 'berimbau01');
//define("DB_NAME", 'multichatdb');
//define("DB_HOST", '127.0.0.1');

define("DB_USER", 'governancapm');
define("DB_PASSWORD", '999274307');
define("DB_NAME", 'governancapm');
define("DB_HOST", 'mysql.db5.net2.com.br');

define("OUTPUT_DIR", 'cache');
define("TABLES", '*');

            $host = "mysql.db5.net2.com.br";
            $usuario = "governancapm";
            $senha = "999274307";
            $banco = "governancapm"; 

/**
 * Instantiate Backup_Database and perform backup
 */
$backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$status = $backupDatabase->backupTables(TABLES, OUTPUT_DIR) ? 'OK' : 'KO';
echo "


Backup result: ".$status;

/**
 * The Backup_Database class
 */
class Backup_Database {
    /**
     * Host where database is located
     */
    var $host = '';

    /**
     * Username used to connect to database
     */
    var $username = '';

    /**
     * Password used to connect to database
     */
    var $passwd = '';

    /**
     * Database to backup
     */
    var $dbName = '';

    /**
     * Database charset
     */
    var $charset = '';
    var $conn;

    /**
     * Constructor initializes database
     */
    function Backup_Database($host, $username, $passwd, $dbName, $charset = 'utf8')
    {
        $this->host     = $host;
        $this->username = $username;
        $this->passwd   = $passwd;
        $this->dbName   = $dbName;
        $this->charset  = $charset;

        $this->initializeDatabase();
    }

    protected function initializeDatabase()
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);        
        if (! mysqli_set_charset ($this->conn, $this->charset))
        {
            mysqli_query('SET NAMES '.$this->charset);
        }
    }

    /**
     * Backup the whole database or just some tables
     * Use '*' for whole database or 'table1 table2 table3...'
     * @param string $tables
     */
    public function backupTables($tables = '*', $outputDir = '.')
    {
        try
        {
            /**
            * Tables to export
            */
            if($tables == '*')
            {
                $tables = array();
                $result = mysqli_query($this->conn,'SHOW TABLES');
                while($row = mysqli_fetch_row($result))
                {
                    $tables[] = $row[0];
                }
            }
            else
            {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
            }

            $sql = 'CREATE DATABASE IF NOT EXISTS '.$this->dbName.";\n\n";
            $sql .= 'USE '.$this->dbName.";\n\n";

            /**
            * Iterate tables
            */
            foreach($tables as $table)
            {
                echo "Backing up ".$table." table...";

//                $result = mysqli_query($this->conn,'SELECT * FROM '.$table);
//                $numFields = mysqli_num_fields($result);

                $sql .= 'DROP TABLE IF EXISTS '.$table.';';
                $row2 = mysqli_fetch_row(mysqli_query($this->conn,'SHOW CREATE TABLE '.$table));
                $sql.= "\n\n".$row2[1].";\n\n";

                $sql.="\n\n\n";

                echo " OK" . "
";
            }
        }
        catch (Exception $e)
        {
            var_dump($e->getMessage());
            return false;
        }

        return $this->saveFile($sql, $outputDir);
    }

    /**
     * Save SQL to file
     * @param string $sql
     */
    protected function saveFile(&$sql, $outputDir = '.')
    {
        if (!$sql) return false;

        try
        {
            $handle = fopen($outputDir.'/db-backup-est-'.$this->dbName.'-'.date("Ymd-His", time()).'.sql','w+');
            fwrite($handle, $sql);
            fclose($handle);
        }
        catch (Exception $e)
        {
            var_dump($e->getMessage());
            return false;
        }

        return true;
    }
}

?>