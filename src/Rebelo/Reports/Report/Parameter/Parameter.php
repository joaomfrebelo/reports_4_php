<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Parameter;

use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\SerializeReportException;

/**
 * Parameter
 * <p>The report parameter</p>
 * @since 1.0.0
 */
class Parameter
{

    /**
     * The type of the parameter (java class)
     *
     * @var \Rebelo\Reports\Report\Parameter\Type $type
     * @since 1.0.0
     */
    private Type $type;

    /**
     * The name of the parameter
     *
     * @var string $name
     * @since 1.0.0
     */
    protected string $name;

    /**
     * The value of the parameter,
     *  the attribute "format" is the date format
     *  for SimpleDateFormat, and only where the parameter type
     *  is "date", for other types must be empty
     *
     * @var mixed
     * @since 1.0.0
     */
    protected mixed $value;

    /**
     * @var ?string $format
     * @since 1.0.0
     */
    protected ?string $format = null;

    /**
     *
     * A report parameter
     *
     * @param \Rebelo\Reports\Report\Parameter\Type $type  The data type of the parameter
     * @param string                                $name  the name of the parameter
     * @param mixed                                 $value the preferred type is string
     * @param ?string                               $format
     * @throws \Rebelo\Reports\Report\Parameter\ParameterException
     * @since 1.0.0
     */
    public function __construct(Type $type, string $name, mixed $value, ?string $format = null)
    {
        Config::configLog4Php();
        \Logger::getLogger(\get_class($this))->debug("Class initialization");
        $this->setType($type);
        $this->setName($name);
        $this->setValue($value);
        $isDate = \in_array($type->get(), [Type::P_SQL_DATE, Type::P_DATE]);

        if ($format == null) {
            if ($isDate) {
                $msg = "For type DATE or SQL_DATE must have format defined";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__ . " '%s'", $msg));
                throw new ParameterException($msg);
            }
        } else {
            if ($isDate) {
                $this->setFormat($format);
            } else {
                $msg = "Format property is only for DATE and SQL_DATE";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__ . " '%s'", $msg));
                throw new ParameterException($msg);
            }
        }
    }

    /**
     *
     * Get parameter type
     *
     * @return \Rebelo\Reports\Report\Parameter\Type
     * @since 1.0.0
     */
    public function getType():Type
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " get '%s'", $this->type->get()));
        return $this->type;
    }

    /**
     * Get parameter name
     *
     * @return string
     * @since 1.0.0
     */
    public function getName(): string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " get '%s'", $this->name));
        return $this->name;
    }

    /**
     * Get the parameter value
     *
     * @return mixed
     * @since 1.0.0
     */
    public function getValue(): mixed
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " get '%s'", $this->value));
        return $this->value;
    }

    /**
     * Set da parameter data type
     * @param \Rebelo\Reports\Report\Parameter\Type $type
     * @return static
     * @since 1.0.0
     */
    public function setType(Type $type): static
    {
        $this->type = $type;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->type->get()));
        return $this;
    }

    /**
     *
     * Set the parameter name
     *
     * @param string $name
     * @return static
     * @throws ParameterException
     * @since 1.0.0
     */
    public function setName(string $name): static
    {
        if ("" === $name = \trim($name)) {
            $msg = "The name must be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ParameterException($msg);
        }
        $this->name = $name;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->name));
        return $this;
    }

    /**
     * Set the parameter value<br>
     * string is the preferred type to be passed as value, all scalar
     * will be converted to string and if a object is passed
     * the __toString() method will be called
     *
     * @param mixed $value string is the preferred type
     * @return static
     * @throws ParameterException
     * @since 1.0.0
     */
    public function setValue(mixed $value): static
    {
        if ($value === null) {
            $msg = "Parameter value can not be null";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ParameterException($msg);
        }
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                __METHOD__ . " value is of type '%s', it will converted to string",
                gettype($value) === "object"
                        ? get_class($value)
                : gettype($value)
            ));
        if (\is_scalar($value)) {
            $this->value = match (true) {
                \is_bool($value) => $value
                    ? "true"
                    : "false",
                default => strval($value),
            };
        } elseif (is_object($value)) {
            $this->value = $value->__toString();
        } else {
            $msg = "unknown type to be converted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ParameterException($msg);
        }

        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->value));

        return $this;
    }

    /**
     * Gets as format
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getFormat(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " get '%s'", $this->format));
        return $this->format;
    }

    /**
     * Sets a new format
     *
     * @param string|null $format
     * @throws ParameterException
     * @return static
     * @since 1.0.0
     */
    public function setFormat(?string $format): static
    {
        if ($format !== null && "" === $format = \trim($format)) {
            $msg = "The format must be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ParameterException($msg);
        }
        $this->format = $format;
        return $this;
    }

    /**
     * Gets a string value
     *
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        return sprintf(
            "{type: '%s', value: '%s', format: '%s'}",
            $this->type->get(),
            $this->value == null
            ? "null"
            : $this->value,
            $this->format == null
            ? "null"
            : $this->format
        );
    }

    /**
     * Create the xml node
     * @param \SimpleXMLElement $node
     * @throws \Rebelo\Reports\Report\SerializeReportException
     */
    public function createXmlNode(\SimpleXMLElement $node): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if ($this->getType() === null) {
            $msg = "The parameter data type must be set";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        if (!\is_string($this->getName()) || \trim($this->getName()) === "") {
            $msg = "The parameter name must be set";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        if ($this->getValue() === null) {
            $msg = "The parameter value must be set";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        $paramNode = $node->addChild("parameter");
        $paramNode->addChild("type", $this->getType()->get());
        AReport::cdata($paramNode->addChild("name"), $this->getName());
        $valueNode = AReport::cdata(
            $paramNode->addChild("value"),
            $this->getValue()
        );
        if ($this->getFormat() != null) {
            $valueNode->addAttribute("format", $this->getFormat());
        }
    }
}
