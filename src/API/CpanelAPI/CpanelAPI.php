<?php

namespace KemoyCampbell\CpanelAPI\API\CpanelAPI;

use KemoyCampbell\CpanelAPI\API\Config\ConfigInterface;
use KemoyCampbell\CpanelAPI\API\CpanelAPI\Exception\BadCpanelAPIDomainException;
use KemoyCampbell\CpanelAPI\API\CpanelAPI\Exception\CpanelFunctionException;
use KemoyCampbell\CpanelAPI\API\CpanelAPI\Exception\CpanelModuleException;
use KemoyCampbell\CpanelAPI\API\CpanelAPI\Exception\InvalidCpanelCredentialException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CpanelAPI implements CPanelAPIInterface
{
    private HttpClientInterface $client;
    private ?CpanelResponseInterface $lastResponse;

    private const ACCESS_FORBIDDEN = 403;
    private const CANNOT_RESOLVE = "Couldn't resolve host";
    private const BAD_MODULE = "Failed to load module";
    private const BAD_FUNCTION = "The system could not find the function";

    public function __construct(ConfigInterface $cpanelConfig, ?HttpClientInterface $client = null)
    {
        $this->client = $client ?? $this->configureClient($cpanelConfig);
        $this->lastResponse = null;
    }

    public function request(string $module, string $function, array $parameters = []): CpanelResponseInterface
    {
        $queryString = http_build_query($parameters);
        $endpoint = "{$module}/{$function}?{$queryString}";

        $response = $this->client->request('GET', $endpoint);
        $this->handleTransportException($response);
        $this->handleBadCredential($response);

        $this->lastResponse = new CpanelResponse($response);

        $this->handleBadModule();
        $this->handleBadFunction();

        return $this->lastResponse;
    }

    private function handleBadCredential(ResponseInterface $response): void
    {
        if ($response->getStatusCode() === self::ACCESS_FORBIDDEN) {
            throw new InvalidCpanelCredentialException('Invalid cPanel credentials');
        }
    }

    private function handleTransportException(ResponseInterface $response): void
    {
        try {
            $response->getStatusCode();
        } catch (\Throwable $e) {
            if (strpos($e->getMessage(), self::CANNOT_RESOLVE) !== false) {
                throw new BadCpanelAPIDomainException('Could not resolve the cPanel API domain. Possible bad domain name');
            } else {
                throw $e;
            }
        }
    }

    private function handleBadModule(): void
    {
        if (!$this->hasErrors()) {
            return;
        }

        $errors = $this->getErrors();
        if (str_contains($errors[0], self::BAD_MODULE)) {
            throw new CpanelModuleException($errors[0]);
        }
    }

    private function handleBadFunction(): void
    {
        if (!$this->hasErrors()) {
            return;
        }

        $errors = $this->getErrors();
        if (str_contains($errors[0], self::BAD_FUNCTION)) {
            throw new CpanelFunctionException($errors[0]);
        }
    }

    public function getErrors(): array
    {
        if (is_null($this->lastResponse)) {
            return [];
        }

        $responseArray = $this->lastResponse->getResponseAsArray();
        return $responseArray['errors'] ?? [];
    }

    public function hasErrors(): bool
    {
        return count($this->getErrors()) > 0;
    }

    public function configureClient(ConfigInterface $cpanelConfig): HttpClientInterface
    {
        $baseUrl = "https://{$cpanelConfig->getServerDomain()}:{$cpanelConfig->getPort()}/execute/";
        $authorization = "cpanel {$cpanelConfig->getUsername()}:{$cpanelConfig->getToken()}";

        $options = [
            'base_uri' => $baseUrl,
            'headers' => [
                'Authorization' => $authorization,
            ]
        ];

        return HttpClient::create($options);
    }
}
