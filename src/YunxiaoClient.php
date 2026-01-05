<?php
declare(strict_types=1);

namespace Lingb\AliyunYunxiaoDevops;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Lingb\AliyunYunxiaoDevops\Exceptions\YunxiaoException;

final class YunxiaoClient
{
    private ClientInterface $httpClient;

    public function __construct(
        private readonly YunxiaoConfig $config,
        ?ClientInterface $httpClient = null
    ) {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => rtrim($this->config->getBaseUri(), '/') . '/',
            'timeout' => 30,
        ]);
    }

    public function getConfig(): YunxiaoConfig
    {
        return $this->config;
    }

    public function listProjects(): array
    {
        return $this->request('GET', 'api/projects');
    }

    public function createPipeline(string $projectId, array $payload): array
    {
        $path = sprintf('api/projects/%s/pipelines', rawurlencode($projectId));

        return $this->request('POST', $path, $payload);
    }

    /**
     * Search workitems (搜索工作项)
     *
     * @param array{
     *     spaceId: string,
     *     category: string,
     *     conditions?: string,
     *     orderBy?: string,
     *     page?: int,
     *     perPage?: int,
     *     sort?: string
     * } $payload
     * @return array
     */
    public function searchWorkitems(array $payload): array
    {
        $organizationId = $this->config->getOrganizationId();
        $path = sprintf('oapi/v1/projex/organizations/%s/workitems:search', rawurlencode($organizationId));

        return $this->request('POST', $path, $payload);
    }

    /**
     * Create workitem (创建工作项)
     *
     * @param array{
     *     assignedTo: string,
     *     spaceId: string,
     *     subject: string,
     *     workitemTypeId: string,
     *     customFieldValues?: array<string, mixed>,
     *     description?: string,
     *     labels?: string[],
     *     parentId?: string,
     *     participants?: string[],
     *     sprint?: string,
     *     trackers?: string[],
     *     verifier?: string,
     *     versions?: string[]
     * } $payload
     * @return array
     */
    public function createWorkitem(array $payload): array
    {
        $organizationId = $this->config->getOrganizationId();
        $path = sprintf('oapi/v1/projex/organizations/%s/workitems', rawurlencode($organizationId));

        return $this->request('POST', $path, $payload);
    }

    public function request(string $method, string $path, array $payload = []): array
    {
        $options = $this->buildOptions($method, $payload);

        try {
            $response = $this->httpClient->request($method, ltrim($path, '/'), $options);
        } catch (GuzzleException $e) {
            throw new YunxiaoException('Yunxiao API request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }

        $body = $response->getBody()->getContents();

        if ($body === '') {
            return [];
        }

        try {
            return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new YunxiaoException('Yunxiao response is not valid JSON: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function buildOptions(string $method, array $payload): array
    {
        $headers = array_merge([
            'Authorization' => 'Bearer ' . $this->config->getAccessToken(),
            'x-yunxiao-token' => $this->config->getAccessToken(),
            'Accept' => 'application/json',
        ], $this->config->getDefaultHeaders());

        $options = ['headers' => $headers];

        if ($payload === []) {
            return $options;
        }

        if (strcasecmp($method, 'GET') === 0) {
            $options['query'] = $payload;
        } else {
            $options['json'] = $payload;
        }

        return $options;
    }
}
