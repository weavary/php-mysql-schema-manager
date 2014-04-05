<?php
require_once 'helper.php';
require_once 'config.php';
require_once 'vendors/adodb/adodb.inc.php';
require_once 'vendors/adodb/adodb-exceptions.inc.php';


class DatabaseModel {

    /**
     * Username for database
     * 
     * @var string
     */
    private $_user;

    /**
     * Password for database
     * 
     * @var string
     */
    private $_password;

    /**
     * Host for database
     * 
     * @var string
     */
    private $_host;

    /**
     * The current selected database
     * 
     * @var mixed
     */
    private $_selected_db;

    /**
     * ADOdb connection
     * 
     * @var mixed
     */
    private $_conn;

    public function __construct() {
        $this->_user = DB_ROOT_USER;
        $this->_password = DB_ROOT_PASSWORD;
        $this->_host = DB_HOST;
        $this->_selected_db = 'information_schema';
        $this->_conn = null;
    }

    protected function _connect() {
         $dsn = sprintf('mysql:host=%s', $this->_host);
         try {
             $this->_conn =& NewADOConnection('pdo');
             $this->_conn->Connect('mysql:host=localhost', $this->_user, $this->_password, $this->_selected_db);

         } catch (Exception $e) {
            firelog($e); 
            // adodb_backtrace($e->gettrace());
         }
    }

    protected function _disconnect() {
        $this->_conn->close();
        $this->_conn = null;
    }

    public function getAllDatabases() {
        $this->_connect();
        
        $this->_conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $exclude_list = array(
            'mysql',
            'phpmyadmin',
            'performance_schema',
            'information_schema',
            'test',
        );
        $resultset = $this->_conn->Execute('SELECT schema_name FROM schemata WHERE schema_name NOT IN (\''.implode('\',\'', $exclude_list).'\') ORDER BY schema_name');

        $dbs = $resultset->getArray();
        $database_names = array();
        foreach ($dbs as $db_schema) {
            $database_names[] = $db_schema['schema_name'];
        }
        $resultset->close();
        $this->_disconnect();
        return $database_names;
    }
}
