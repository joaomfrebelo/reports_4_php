<?php

namespace Rebelo\Reports\Report\Datasource;

/**
 * Xmlhttp
 *
 * Use xml over http as datasource
 * @since 1.0.0
 */
class XmlHttp
    extends AServerHttp
{

    /**
     * Https xml server as datasource
     *
     * @param string $url The server url
     * @param \Rebelo\Reports\Report\Datasource\RequestType $type Request type GET | POST
     * @since 1.0.0
     */
    public function __construct($url = null, RequestType $type = null)
    {
        parent::__construct($url, $type);
    }

}
