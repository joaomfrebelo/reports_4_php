<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Datasource;

/**
 * Class representing JsonHttps
 *
 * Use json over https as datasource
 * @since 1.0.0
 */
class JsonHttps extends AServerHttps
{

    /**
     * Https json server as datasource
     * @param string|null                                        $url  The server url
     * @param \Rebelo\Reports\Report\Datasource\RequestType|null $type Request type GET | POST
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @since 1.0.0
     */
    public function __construct(?string $url = null, ?RequestType $type = null)
    {
        parent::__construct($url, $type);
    }

    /**
     *
     * @since 1.0.0
     */
    public function __toString()
    {
        return sprintf(
            "url: '%s': type: '%s'",
            $this->url === null
            ? "null"
            : $this->url,
            $this->type === null
            ? "null"
            : $this->type->get()
        );
    }
}
