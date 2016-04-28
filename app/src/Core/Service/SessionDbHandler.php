<?php

namespace Core\Service;

/**
 * Class SessionDbHandler
 *
 * @package Component\Service
 * @author sumiyoshi
 */
class SessionDbHandler implements \SessionHandlerInterface
{

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var array
     */
    protected $sqls = array(
        'read' => 'SELECT session_data FROM php_sessions WHERE session_id = :session_id',
        'write' => 'REPLACE INTO php_sessions VALUES (:session_id, NULL, :session_data)',
        'destroy' => 'DELETE FROM php_sessions WHERE session_id = :session_id',
        'gc' => 'DELETE FROM php_sessions WHERE TIMESTAMP(session_updated) < NOW() - :second'
    );

    /**
     * Constructor
     *
     * @param array $settings
     * @throws \Exception
     */
    public function __construct($settings = array())
    {
        $defaults = array(
            'host' => 'localhost',
            'port' => '3306',
            'database' => 'db',
            'user' => 'root',
            'pass' => 'password',
            'table' => 'php_sessions'
        );
        $this->settings = array_merge($defaults, $settings);

        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );

        register_shutdown_function('session_write_close');
        session_cache_limiter(false);
        session_start();
    }

    /**
     * @param $save_path
     * @param $session_id
     * @return bool
     */
    public function open($save_path, $session_id)
    {
        $dsn = "mysql:dbname={$this->settings['database']};host={$this->settings['host']}";
        $this->pdo = new \PDO($dsn, $this->settings['user'], $this->settings['pass']);
        return true;
    }

    /**
     * @return bool
     */
    public function close()
    {
        $this->pdo = null;
        return true;
    }

    /**
     * @param $session_id
     * @return string
     */
    public function read($session_id)
    {
        $smt = $this->pdo->prepare($this->sqls['read']);
        $smt->bindParam(':session_id', $session_id, \PDO::PARAM_STR);
        if (!$smt->execute()) {
            return '';
        }

        return $smt->fetchColumn(0);
    }

    /**
     * @param $session_id
     * @param $session_data
     * @return bool
     */
    public function write($session_id, $session_data)
    {
        $smt = $this->pdo->prepare($this->sqls['write']);
        $smt->bindParam(':session_id', $session_id, \PDO::PARAM_STR);
        $smt->bindParam(':session_data', $session_data, \PDO::PARAM_STR);

        return $smt->execute();
    }

    /**
     * @param $session_id
     * @return bool
     */
    public function destroy($session_id)
    {
        $smt = $this->pdo->prepare($this->sqls['destroy']);
        $smt->bindParam(':session_id', $session_id, \PDO::PARAM_STR);

        return $smt->execute();
    }

    /**
     * @param $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        $sql = "DELETE FROM php_sessions WHERE TIMESTAMP(session_updated) < NOW() - {$maxlifetime}";

        return $this->pdo->exec($sql);
    }
}