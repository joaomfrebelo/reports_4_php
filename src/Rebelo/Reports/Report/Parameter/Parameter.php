<?php

namespace Rebelo\Reports\Report\Parameter;

use Rebelo\Reports\Report\Parameter\Type;
use Rebelo\Reports\Report\AReport;

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
    private $type = null;

    /**
     * The name of the parameter
     *
     * @var string $name
     * @since 1.0.0
     */
    protected $name = null;

    /**
     * The value of the parameter,
     *  the attribute "format" is the date formate
     *  for SimpleDateFormat, and only where the parameter type
     *  is "date", for other types must be empty
     *
     * @var mixed
     * @since 1.0.0
     */
    protected $value = null;

    /**
     * @var string $format
     * @since 1.0.0
     */
    protected $format = null;

    /**
     *
     * A report parameter
     *
     * @param \Rebelo\Reports\Report\Parameter\Type $type The data type of the parameter
     * @param string $name the name of the parameter
     * @param mixed $value the prefered type is string, if is object the __toString() will be called
     * @param type $format
     * @since 1.0.0
     */
    public function __construct(Type $type, $name, $value, $format = null)
    {
        \Rebelo\Reports\Config\Config::configLog4Php();
        \Logger::getLogger(\get_class($this))->debug("Class initialization");
        $this->setType($type);
        $this->setName($name);
        $this->setValue($value);
        $isDate = \in_array($type->get(),
                            array(
                Type::P_SQL_DATE,
                Type::P_DATE));

        if ($format == null)
        {
            if ($isDate)
            {
                $msg = "For type DATE or SQL_DATE must have format defined";
                \Logger::getLogger(\get_class($this))
                    ->error(\sprintf(__METHOD__ . " '%s'", $msg));
                throw new ParameterException($msg);
            }
        }
        else
        {
            if ($isDate)
            {
                $this->setFormat($format);
            }
            else
            {
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
    public function getType()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->type->get()));
        return $this->type;
    }

    /**
     * Get parameter name
     *
     * @return string
     * @since 1.0.0
     */
    public function getName()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->name));
        return $this->name;
    }

    /**
     * Get the parameter value
     *
     * @return string
     * @since 1.0.0
     */
    public function getValue()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->value));
        return $this->value;
    }

    /**
     * Set da parameter data type
     * @param \Rebelo\Reports\Report\Parameter\Type $type
     * @return $this
     * @since 1.0.0
     */
    public function setType(Type $type)
    {
        $this->type = $type;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->type->get()));
        return $this;
    }

    /**
     *
     * Set the parameter name
     *
     * @param string $name
     * @return $this
     * @throws ParameterException
     * @since 1.0.0
     */
    public function setName($name)
    {
        if (!\is_string($name) || \trim($name) === "")
        {
            $msg = "The name must be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ParameterException($msg);
        }
        $this->name = $name;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->name));
        return $this;
    }

    /**
     * Set the parameter value<br>
     * string is the prefered type to be passed as value, all scalar
     * will be converted to string and if a object is passed
     * the __toString() method will be called
     *
     * @param Mixed $value string is the prefered type
     * @return $this
     * @throws ParameterException
     * @since 1.0.0
     */
    public function setValue($value)
    {
        if ($value === null)
        {
            $msg = "Parameter value can not be null";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ParameterException($msg);
        }
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " value is of type '%s', it will converted to string",
                            gettype($value) === "object"
                        ? get_class($value)
                        : gettype($value)));
        if (\is_scalar($value))
        {
            switch (true)
            {
                case \is_bool($value):
                    $this->value = $value
                        ? "true"
                        : "false";
                    break;
                default :
                    $this->value = strval($value);
            }
        }
        elseif (is_object($value))
        {
            $this->value = $value->__toString();
        }
        else
        {
            $msg = sprintf("unknow type to be converted");
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ParameterException($msg);
        }

        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->value));

        return $this;
    }

    /**
     * Gets as format
     *
     * @return string
     * @since 1.0.0
     */
    public function getFormat()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->format));
        return $this->format;
    }

    /**
     * Sets a new format
     *
     * @param string $format
     * @throws ParameterException
     * @return self
     * @since 1.0.0
     */
    public function setFormat($format)
    {
        if (!\is_string($format) || $format === null || trim($format) === "")
        {
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
        return sprintf("{type: '%s', value: '%s', format: '%s'}",
                       $this->type == null
            ? "null"
            : $this->type->get(),
                       $this->value == null
            ? "null"
            : $this->value,
                       $this->format == null
            ? "null"
            : $this->format);
    }

    /**
     * Create the xml node
     * @param \SimpleXMLElement $node
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if ($this->getType() === null)
        {
            $msg = "The parameter data type must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        if (!\is_string($this->getName()) || \trim($this->getName()) === "")
        {
            $msg = "The parameter name must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        if ($this->getValue() === null)
        {
            $msg = "The parameter value must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        $paramNode = $node->addChild("parameter");
        $paramNode->addChild("type", $this->getType()->get());
        AReport::cdata($paramNode->addChild("name"), $this->getName());
        $valueNode = AReport::cdata($paramNode->addChild("value"),
                                                         $this->getValue());
        if ($this->getFormat() != null)
        {
            $valueNode->addAttribute("format", $this->getFormat());
        }
    }

}
