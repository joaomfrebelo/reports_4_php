<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Datasource;

/**
 * XmlHttp
 *
 * Use xml over http as datasource
 * @since 1.0.0
 */
class XmlHttp extends AServerHttp
{

    /**
     * Https xml server as datasource
     *
     * @param string|null                                        $url  The server url
     * @param \Rebelo\Reports\Report\Datasource\RequestType|null $type Request type GET | POST
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @since 1.0.0
     */
    public function __construct(?string $url = null, ?RequestType $type = null)
    {
        parent::__construct($url, $type);
    }
}
