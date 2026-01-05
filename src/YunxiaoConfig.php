<?php
declare(strict_types=1);

namespace Lingb\AliyunYunxiaoDevops;

final class YunxiaoConfig
{
    public function __construct(
        private readonly string $accessToken,
        private readonly string $organizationId = '',
        private readonly string $domain = 'openapi.devops.aliyun.com',
        private readonly array $defaultHeaders = []
    ) {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getBaseUri(): string
    {
        return sprintf('https://%s', $this->domain);
    }

    public function getDefaultHeaders(): array
    {
        return $this->defaultHeaders;
    }
}
