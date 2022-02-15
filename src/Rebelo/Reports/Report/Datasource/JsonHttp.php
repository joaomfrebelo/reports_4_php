<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Datasource;

/**
 * Jsonhttp
 *
 * Use json over http as datasource
 * @since 1.0.0
 */
class JsonHttp extends AServerHttp
{

    /**
     * Https json server as datasource
     *
     * @param ?string                                            $url  The server url
     * @param \Rebelo\Reports\Report\Datasource\RequestType|null $type Requet type POST | GET
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @since 1.0.0
     */
    public function __construct(?string $url = null, ?RequestType $type = null)
    {
        parent::__construct($url, $type);
    }
}
