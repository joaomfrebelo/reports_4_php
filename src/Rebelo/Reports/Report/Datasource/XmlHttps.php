<?php

namespace Rebelo\Reports\Report\Datasource;

/**
 * Xmlhttps
 *
 * Use xml over https as datasource
 * @since 1.0.0
 */
class XmlHttps
    extends AServerHttps
{

    /**
     * Https xml server as datasource
     *
     * @param string $url Server url
     * @param \Rebelo\Reports\Report\Datasource\RequestType $type Request type GET | POST
     * @since 1.0.0
     */
    public function __construct($url = null, RequestType $type = null)
    {
        parent::__construct($url, $type);
    }

}
