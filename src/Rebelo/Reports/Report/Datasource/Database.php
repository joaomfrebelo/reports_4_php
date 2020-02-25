<?php

namespace Rebelo\Reports\Report\Datasource;

use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\SerializeReportException;

/**
 * Database datasource
 *
 * Use database as report datasource
 * @since 1.0.0
 */
class Database
    extends ADatasource
{

    /**
     * The connection string to be used to the driver connect with the database
     * <br>
     * Ex: jdbc:oracle:thin:@//localhost:1521/XE
     *
     * @var string $connectionString
     * @since 1.0.0
     */
    private $connectionString = null;

    /**
     * The driver class name to be use to connect to the database
     *  Ex: oracle.jdbc.OracleDriver
     *  EX: com.microsoft.sqlserver.jdbc.SQLServerDriver
     *
     * @var string $driver
     * @since 1.0.0
     */
    private $driver = null;

    /**
     * The username to connect to the database
     *
     * @var string $user
     * @since 1.0.0
     */
    private $user = null;

    /**
     * The password to connect to the database
     *
     * @var string $password
     * @since 1.0.0
     */
    private $password = null;

    /**
     * Gets as connectionString
     *
     * The connection string to be used to the driver connect with the database
     * <br>
     * Ex: jdbc:oracle:thin:@//localhost:1521/XE
     *
     * @return string
     * @since 1.0.0
     */
    public function getConnectionString()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                           $this->connectionString === null
                        ? "null"
                        : $this->connectionString));
        return $this->connectionString;
    }

    /**
     * Sets a new connectionString
     *
     * The connection string to be used to the driver connect with the database
     * <br>
     * Ex: jdbc:oracle:thin:@//localhost:1521/XE
     *
     * @param string $connectionString
     * @return self
     * @since 1.0.0
     */
    public function setConnectionString($connectionString)
    {
        $this->connectionString = $connectionString;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                            $this->connectionString === null
                        ? "null"
                        : $this->connectionString));
        return $this;
    }

    /**
     * Gets as driver
     *
     * The driver class name to be use to connect to the database
     *  Ex: oracle.jdbc.OracleDriver
     *  EX: com.microsoft.sqlserver.jdbc.SQLServerDriver
     *
     * @return string
     * @since 1.0.0
     */
    public function getDriver()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                           $this->driver === null
                        ? "null"
                        : $this->driver));
        return $this->driver;
    }

    /**
     * Sets a new driver
     *
     * The driver class name to be use to connect to the database
     *  Ex: oracle.jdbc.OracleDriver
     *  EX: com.microsoft.sqlserver.jdbc.SQLServerDriver
     *
     * @param string $driver
     * @return self
     * @throws DatasourceException
     * @since 1.0.0
     */
    public function setDriver($driver)
    {
        if (!\is_string($driver) || \trim($driver) === "")
        {
            $msg = "driver must be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new DatasourceException($msg);
        }

        $this->driver = $driver;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                            $this->driver === null
                        ? "null"
                        : $this->driver));
        return $this;
    }

    /**
     * Gets as user
     *
     * The username to connect to the database
     *
     * @return string
     * @since 1.0.0
     */
    public function getUser()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                           $this->user === null
                        ? "null"
                        : $this->user));
        return $this->user;
    }

    /**
     * Sets a new user
     *
     * The username to connect to the database
     *
     * @param string $user
     * @return self
     * @since 1.0.0
     */
    public function setUser($user)
    {
        $this->user = $user;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                            $this->user === null
                        ? "null"
                        : $this->user));
        return $this;
    }

    /**
     * Gets as password
     *
     * The password to connect to the database
     *
     * @return string
     * @since 1.0.0
     */
    public function getPassword()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                           $this->password === null
                        ? "null"
                        : $this->password));
        return $this->password;
    }

    /**
     * Sets a new password
     *
     * The password to connect to the database
     *
     * @param string $password
     * @return self
     * @since 1.0.0
     */
    public function setPassword($password)
    {
        $this->password = $password;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                            $this->password === null
                        ? "null"
                        : $this->password));
        return $this;
    }

    /**
     *
     * @since 1.0.0
     */
    public function __toString()
    {
        return sprintf("driver: '%s', user: '%s', conn: '%s'",
                       $this->driver === null
            ? "null"
            : $this->driver,
                       $this->user === null
            ? "null"
            : $this->user,
                       $this->connectionString === null
            ? "null"
            : $this->connectionString);
    }

    /**
     *
     * Create the xml node
     *
     * @param \SimpleXMLElement $node
     * @throws SerializeReportException
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (!\is_string($this->getDriver()) || \trim($this->getDriver()) === "")
        {
            $msg = "Database driver must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }
        if (!\is_string($this->getConnectionString()) || \trim($this->getConnectionString()) === "")
        {
            $msg = "Database connection string must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }


        $databaseNode = $node->addChild("database");
        AReport::cdata($databaseNode->addChild("connectionString"),
                                               $this->getConnectionString());

        AReport::cdata($databaseNode->addChild("driver"), $this->getDriver());

        AReport::cdata($databaseNode->addChild("user"),
                                               $this->getUser() === null
                ? ""
                : $this->getUser());

        AReport::cdata($databaseNode->addChild("password"),
                                               $this->getPassword() === null
                ? ""
                : $this->getPassword() );
    }

}
