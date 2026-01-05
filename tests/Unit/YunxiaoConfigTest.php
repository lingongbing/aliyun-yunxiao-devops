<?php
declare(strict_types=1);

namespace Tests\Unit;

use Lingb\AliyunYunxiaoDevops\YunxiaoConfig;
use PHPUnit\Framework\TestCase;

class YunxiaoConfigTest extends TestCase
{
    public function test_config_initialization(): void
    {
        $config = new YunxiaoConfig(
            accessToken: 'test-token',
            organizationId: 'test-org',
            domain: 'custom.domain.com',
            defaultHeaders: ['X-Custom' => 'value']
        );

        $this->assertEquals('test-token', $config->getAccessToken());
        $this->assertEquals('test-org', $config->getOrganizationId());
        $this->assertEquals('custom.domain.com', $config->getDomain());
        $this->assertEquals('https://custom.domain.com', $config->getBaseUri());
        $this->assertEquals(['X-Custom' => 'value'], $config->getDefaultHeaders());
    }

    public function test_config_default_values(): void
    {
        $config = new YunxiaoConfig(accessToken: 'test-token');

        $this->assertEquals('openapi.devops.aliyun.com', $config->getDomain());
        $this->assertEquals('https://openapi.devops.aliyun.com', $config->getBaseUri());
        $this->assertEquals('', $config->getOrganizationId());
    }
}
