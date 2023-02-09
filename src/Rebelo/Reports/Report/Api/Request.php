<?php

/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Rebelo\Reports\Report\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Promise\Utils;
use Rebelo\Reports\Config\Config;
use Rebelo\Test\Reports\Api\RequestException;

/**
 * Report Response
 * @author João Rebelo
 * @since  3.0.0
 */
class Request
{

    /**
     * @var \Logger
     * @since 3.0.0
     */
    protected \Logger $log;

    /**
     *
     * @since 3.0.0
     */
    public function __construct()
    {
        $this->log  = \Logger::getLogger(\get_class($this));
        $this->log->debug(__METHOD__);
    }

    /**
     * @param \Rebelo\Reports\Report\Api\Action $action
     * @param array|null                        $data
     * @return array{status: string, message: string, duration: string, report: ?string}
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Test\Reports\Api\RequestException
     * @since 3.0.0
     */
    public function request(Action $action, ?array $data): array
    {
        $client = new Client();
        try {
            $response = $client->request(
                method:  $action->getVerb(),
                uri:     \sprintf("%s/%s", Config::getInstance()->getApiEndpoint(), $action->get()),
                options: ['json' => $data]
            );

            $responseBody = (string)$response->getBody();
        } catch (BadResponseException $badResponseException) {
            $responseBody = (string)$badResponseException->getResponse()->getBody();
        }

        $responseData = \json_decode($responseBody, true);

        if (\count(\array_diff(["status", "message", "duration"], \array_keys($responseData))) > 0) {
            $msg = \sprintf("Wrong response: %s", $responseBody);
            $this->log->error($msg);
            throw new RequestException($msg);
        }

        return $responseData;
    }

    /**
     * Get report in bulk
     * @param array $dataStack Array of report array data to convert to json
     * @param array $errors Get client request errors
     * @return \Rebelo\Reports\Report\Api\ReportResponse[]
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \ReflectionException
     * @since 3.0.0
     */
    public function bulkReportRequest(array $dataStack, array &$errors = []): array
    {
        $client = new Client(["base_uri" => Config::getInstance()->getApiEndpoint()]);
        $promises = [];

        foreach ($dataStack as $k => $data) {
            $promises[$k] = $client->postAsync(Action::REPORT, ['json' => $data]);
        }

        $responseBulk = Utils::settle($promises)->wait();

        /** @var \Rebelo\Reports\Report\Api\ReportResponse[] $reportResponses */
        $reportResponses = [];

        foreach ($responseBulk as $k => $responseStack) {
            /** @var \GuzzleHttp\Psr7\Response $response */
            $responseBody = null;
            if ($responseStack["state"] === "rejected") {
                $throw = $responseStack["reason"];
                if ($throw instanceof BadResponseException) {
                    $responseBody = (string)$throw->getResponse()->getBody();
                } else {
                    $errors[$k] = ($throw instanceof \Throwable) ? $throw->getMessage() : $throw;
                    continue;
                }
            }

            if ($responseBody === null) {
                $response     = $responseStack["value"];
                $responseBody = (string)$response->getBody();
            }

            $responseData = \json_decode($responseBody, true);

            if (\count(\array_diff(["status", "message", "duration", "report"], \array_keys($responseData))) > 0) {
                $msg = \sprintf("Wrong response: %s", $responseBody);
                $this->log->error($msg);
                $errors[$k] = $msg;
                continue;
            }

            $reportResponses[$k] = new ReportResponse(
                new Status($responseData["status"]),
                $responseData["message"],
                $responseData["duration"],
                $responseData["report"]
            );
        }

        return $reportResponses;
    }

    /**
     * @param array $data
     * @return \Rebelo\Reports\Report\Api\ReportResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Test\Reports\Api\RequestException
     * @throws \ReflectionException
     * @since 3.0.0
     */
    public function requestReport(array $data): ReportResponse
    {
        $response = $this->request(Action::REPORT(), $data);
        return new ReportResponse(
            new Status($response["status"]),
            $response["message"] ?? "",
            $response["duration"] ?? "",
            $response["report"] ?? ""
        );
    }

    /**
     * @param \Rebelo\Reports\Report\Api\Action $action
     * @return \Rebelo\Reports\Report\Api\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Test\Reports\Api\RequestException
     * @throws \ReflectionException
     * @since 3.0.0
     */
    public function sendPrinterCommand(Action $action): Response
    {
        $response = $this->request($action, null);
        return new Response(
            new Status($response["status"]),
            $response["message"],
            $response["duration"]
        );
    }
}
