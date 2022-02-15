<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Datasource;

use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\SerializeReportException;

/**
 * Database datasource
 *
 * Use database as report datasource
 * @since 1.0.0
 */
class Database extends ADatasource
{

    /**
     * The connection string to be used to the driver connect with the database
     * <br>
     * Ex: jdbc:oracle:thin:@//localhost:1521/XE
     *
     * @var string|null $connectionString
     * @since 1.0.0
     */
    private ?string $connectionString = null;

    /**
     * The driver class name to be use to connect to the database
     *  Ex: oracle.jdbc.OracleDriver
     *  EX: com.microsoft.sqlserver.jdbc.SQLServerDriver
     *
     * @var string|null $driver
     * @since 1.0.0
     */
    private ?string $driver = null;

    /**
     * The username to connect to the database
     *
     * @var string|null $user
     * @since 1.0.0
     */
    private ?string $user = null;

    /**
     * The password to connect to the database
     *
     * @var string|null $password
     * @since 1.0.0
     */
    private string|null$password = null;

    /**
     * Gets as connectionString
     *
     * The connection string to be used to the driver connect with the database
     * <br>
     * Ex: jdbc:oracle:thin:@//localhost:1521/XE
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getConnectionString(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->connectionString === null
                                   ? "null"
                                   : $this->connectionString
               ));
        return $this->connectionString;
    }

    /**
     * Sets a new connectionString
     *
     * The connection string to be used to the driver connect with the database
     * <br>
     * Ex: jdbc:oracle:thin:@//localhost:1521/XE
     *
     * @param string|null $connectionString
     * @return static
     * @since 1.0.0
     */
    public function setConnectionString(?string $connectionString): static
    {
        $this->connectionString = $connectionString;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->connectionString === null
                                    ? "null"
                                    : $this->connectionString
               ));
        return $this;
    }

    /**
     * Gets as driver
     *
     * The driver class name to be use to connect to the database
     *  Ex: oracle.jdbc.OracleDriver
     *  EX: com.microsoft.sqlserver.jdbc.SQLServerDriver
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getDriver(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->driver === null
                                   ? "null"
                                   : $this->driver
               ));
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
     * @return static
     * @throws DatasourceException
     * @since 1.0.0
     */
    public function setDriver(string $driver): static
    {
        if ("" === $driver = \trim($driver)) {
            $msg = "driver must be a non empty string";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new DatasourceException($msg);
        }

        $this->driver = $driver;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->driver === null
                                    ? "null"
                                    : $this->driver
               ));
        return $this;
    }

    /**
     * Gets as user
     *
     * The username to connect to the database
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getUser(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->user === null
                                   ? "null"
                                   : $this->user
               ));
        return $this->user;
    }

    /**
     * Sets a new user
     *
     * The username to connect to the database
     *
     * @param string|null $user
     * @return static
     * @since 1.0.0
     */
    public function setUser(?string $user): static
    {
        $this->user = $user;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->user === null
                                    ? "null"
                                    : $this->user
               ));
        return $this;
    }

    /**
     * Gets as password
     *
     * The password to connect to the database
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getPassword(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->password === null
                                   ? "null"
                                   : $this->password
               ));
        return $this->password;
    }

    /**
     * Sets a new password
     *
     * The password to connect to the database
     *
     * @param string|null $password
     * @return static
     * @since 1.0.0
     */
    public function setPassword(string|null $password): static
    {
        $this->password = $password;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->password === null
                                    ? "null"
                                    : $this->password
               ));
        return $this;
    }

    /**
     *
     * @since 1.0.0
     */
    public function __toString()
    {
        return sprintf(
            "driver: '%s', user: '%s', conn: '%s'",
            $this->driver === null
                           ? "null"
                           : $this->driver,
            $this->user === null
                           ? "null"
                           : $this->user,
            $this->connectionString === null
                           ? "null"
                           : $this->connectionString
        );
    }

    /**
     *
     * Create the xml node
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws SerializeReportException
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (!\is_string($this->getDriver()) || \trim($this->getDriver()) === "") {
            $msg = "Database driver must be set";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }
        if (!\is_string($this->getConnectionString()) || \trim($this->getConnectionString()) === "") {
            $msg = "Database connection string must be set";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }


        $databaseNode = $node->addChild("database");
        AReport::cdata(
            $databaseNode->addChild("connectionString"),
            $this->getConnectionString()
        );

        AReport::cdata($databaseNode->addChild("driver"), $this->getDriver());

        AReport::cdata(
            $databaseNode->addChild("user"),
            $this->getUser() === null
                           ? ""
                           : $this->getUser()
        );

        AReport::cdata(
            $databaseNode->addChild("password"),
            $this->getPassword() === null
                           ? ""
                           : $this->getPassword()
        );

        return $databaseNode;
    }
}
