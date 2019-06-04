<?php declare(strict_types=1);

namespace Bupy7\Doctrine\NestedSet\Test;

use InvalidArgumentException;

class Env
{
    /**
     * @var array
     */
    private $params;
    /**
     * @var Env
     */
    private static $instance;

    private function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return Env
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Env($_ENV);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->get('BDNS_DB_NAME');
    }

    /**
     * @return string
     */
    public function getDbUsername()
    {
        return $this->get('BDNS_DB_USERNAME');
    }

    /**
     * @return string
     */
    public function getDbPassword()
    {
        return $this->get('BDNS_DB_PASSWORD');
    }

    /**
     * @return string
     */
    public function getDbHost()
    {
        return $this->get('BDNS_DB_HOST');
    }

    /**
     * @return string|int
     */
    public function getDbPort()
    {
        return $this->get('BDNS_DB_PORT');
    }

    /**
     * @param string $name
     * @return string|int
     * @throw InvalidArgumentException
     */
    private function get($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }
        throw new InvalidArgumentException('Specified name of `' . $name . '` not exists.');
    }
}