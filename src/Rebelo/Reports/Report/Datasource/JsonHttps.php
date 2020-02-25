<?php

namespace Rebelo\Reports\Report\Datasource;

/**
 * Class representing Jsonhttps
 *
 * Use json over https as datasource
 * @since 1.0.0
 */
class JsonHttps
    extends AServerHttps
{

    /**
     * Https json server as datasource
     * @param string $url The server url
     * @param \Rebelo\Reports\Report\Datasource\RequestType $type Reuest type GET | POST
     * @since 1.0.0
     */
    function __construct($url = null, RequestType $type = null)
    {
        parent::__construct($url, $type);
    }

    /**
     *
     * @since 1.0.0
     */
    public function __toString()
    {
        return sprintf("url: '%s': type: '%s'",
                       $this->url === null
            ? "null"
            : $this->url,
                       $this->type === null
            ? "null"
            : $this->type->get());
    }

}
