<?php


namespace App\Utils;


use SQLite3;
use SQLite3Result;

class DataAccessor
{
    private const DATA_BASE = __DIR__ . '/../Data/sso.db';
    
    /**
     * @var SQLite3
     */
    private $db_handle;
    private $serverId;
    
    /**
     * DataAccessor constructor.
     * @param $serverId
     */
    public function __construct($serverId)
    {
        $this->db_handle = new SQLite3(self::DATA_BASE);
        $this->serverId = $serverId;
    }
    
    /**
     * Init the data base
     */
    public function initDataBase()
    {
        $this->createDataBase();
        
        $query = 'SELECT count(*) AS total FROM config WHERE server_identity = :serverId';
        $total = $this->executeQuery($query, [['serverId', $this->serverId, SQLITE3_TEXT]])->fetchArray()['total'];
        
        if ($total === 0) {
            $this->createInitialConfig($this->serverId);
        }
    }
    
    private function createDataBase()
    {
        $query = '
            CREATE TABLE IF NOT EXISTS config (id INTEGER PRIMARY KEY,server_identity TEXT, user_data text, verify INTEGER DEFAULT 1);
        ';
        
        $this->executeQuery($query);
    }
    
    private function createInitialConfig($server)
    {
        $userData = file_get_contents(__DIR__ . '/../Data/user-data.json');
        $this->executeQuery('INSERT INTO config(server_identity, user_data) VALUES (:serverIdentity, :userData)', [
            [':serverIdentity', $server, SQLITE3_TEXT],
            [':userData', $userData, SQLITE3_TEXT]
        ]);
    }
    
    /**
     * @param string $query
     * @param array $parameters
     * @return SQLite3Result
     */
    public function executeQuery($query, $parameters = [])
    {
        $stmt = $this->db_handle->prepare($query);
        foreach ($parameters as $parameter) {
            call_user_func_array([$stmt, 'bindValue'], $parameter);
        }
        
        return $stmt->execute();
    }
    
    public function getConfigData()
    {
        return $this->executeQuery('SELECT * FROM config WHERE server_identity = :serverIdentity', [
            ['serverIdentity', $this->serverId, SQLITE3_TEXT]
        ])->fetchArray();
    }
    
    public function getVerify()
    {
        $result = $this->executeQuery('SELECT verify FROM config WHERE server_identity = :serverIdentity', [
            ['serverIdentity', $this->serverId, SQLITE3_TEXT]
        ])->fetchArray()['verify'];
        
        return (boolean)$result;
    }
    
    public function getUserData()
    {
        return $this->executeQuery('SELECT user_data FROM config WHERE server_identity = :serverIdentity', [
            ['serverIdentity', $this->serverId, SQLITE3_TEXT]
        ])->fetchArray()['user_data'];
    }
}