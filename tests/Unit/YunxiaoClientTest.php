<?php
declare(strict_types=1);

namespace Tests\Unit;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Lingb\AliyunYunxiaoDevops\YunxiaoClient;
use Lingb\AliyunYunxiaoDevops\YunxiaoConfig;
use PHPUnit\Framework\TestCase;

class YunxiaoClientTest extends TestCase
{
    public function test_search_workitems(): void
    {
        $config = new YunxiaoConfig(
            accessToken: 'test-token',
            organizationId: 'test-org'
        );

        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'oapi/v1/projex/organizations/test-org/workitems:search',
                $this->callback(function ($options) {
                    return $options['headers']['x-yunxiao-token'] === 'test-token' &&
                           $options['headers']['Authorization'] === 'Bearer test-token' &&
                           $options['json']['category'] === 'Req';
                })
            )
            ->willReturn(new Response(200, [], json_encode(['result' => 'ok'])));

        $client = new YunxiaoClient($config, $mockClient);
        $result = $client->searchWorkitems([
            'category' => 'Req',
            'spaceId' => 'space-123'
        ]);

        $this->assertEquals(['result' => 'ok'], $result);
    }

    public function test_list_projects_uses_api_prefix(): void
    {
        $config = new YunxiaoConfig(accessToken: 'test-token');
        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient->expects($this->once())
            ->method('request')
            ->with('GET', 'api/projects', $this->isType('array'))
            ->willReturn(new Response(200, [], json_encode([])));

        $client = new YunxiaoClient($config, $mockClient);
        $client->listProjects();
    }

    public function test_create_workitem(): void
    {
        $config = new YunxiaoConfig(
            accessToken: 'test-token',
            organizationId: 'test-org'
        );

        $mockClient = $this->createMock(ClientInterface::class);
        $mockClient->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'oapi/v1/projex/organizations/test-org/workitems',
                $this->callback(function ($options) {
                    return $options['json']['subject'] === 'test-subject' &&
                           $options['json']['assignedTo'] === 'user-123';
                })
            )
            ->willReturn(new Response(200, [], json_encode(['id' => 'workitem-123'])));

        $client = new YunxiaoClient($config, $mockClient);
        $result = $client->createWorkitem([
            'subject' => 'test-subject',
            'assignedTo' => 'user-123',
            'spaceId' => 'space-123',
            'workitemTypeId' => 'type-123'
        ]);

        $this->assertEquals(['id' => 'workitem-123'], $result);
    }
}
