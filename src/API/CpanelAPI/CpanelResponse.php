<?php

namespace KemoyCampbell\CpanelAPI\API\CpanelAPI;

use KemoyCampbell\CpanelAPI\API\CpanelAPI\Exception\CpanelResponseParsingException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CpanelResponse implements CpanelResponseInterface
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getResponseAsArray(): array
    {
        try {
            return $this->response->toArray(false);
        } catch (\Throwable $e) {
            throw new CpanelResponseParsingException('Failed to parse response as array', 0, $e);
        }
    }

    public function getResponseAsJson(): string
    {
        $content = $this->response->getContent();
        $decoded = json_decode($content);

        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new CpanelResponseParsingException('Failed to parse response as JSON');
        }

        return $content;
    }

    public function getResponseAsStdClass(): \stdClass
    {
        $decoded = json_decode($this->response->getContent());

        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new CpanelResponseParsingException('Failed to parse response as JSON');
        }

        return $decoded;
    }

    public function getResponseAsString(): string
    {
        try {
            return $this->response->getContent();
        } catch (\Throwable $e) {
            throw new CpanelResponseParsingException('Failed to get response content as string', 0, $e);
        }
    }

    public function getResponseAsXml(): \SimpleXMLElement
    {
        if (!extension_loaded('simplexml')) {
            throw new \Exception('SimpleXML extension is not enabled!');
        }

        $xml = simplexml_load_string($this->response->getContent());

        if ($xml === false) {
            throw new CpanelResponseParsingException('Failed to parse response as XML');
        }

        return $xml;
    }
}
