<?php


namespace Deployee\Components\Persistence;

/**
 * @mixin \PDO
 */
class LazyPDO
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $options;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $options
     */
    public function __construct(string $dsn, string $username = '', string $password = '', array $options = [])
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getConnectionType(): string
    {
        return substr($this->dsn, 0, strpos($this->dsn, ':'));
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return substr($this->dsn, strrpos($this->dsn, '='));
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->pdo(), $name], $arguments);
    }

    /**
     * @return \PDO
     */
    private function pdo(): \PDO
    {
        if($this->pdo === null){
            $this->pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        }

        return $this->pdo;
    }
}