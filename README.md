# Aliyun Yunxiao DevOps Composer Package

This package provides a minimal PHP client for calling [云效](https://www.aliyun.com/product/devops) APIs so you can script DevOps workflows inside your application.

## Installation

```bash
composer require lingb/aliyun-yunxiao-devops
```

## Quick start

```php
use Lingb\AliyunYunxiaoDevops\YunxiaoClient;
use Lingb\AliyunYunxiaoDevops\YunxiaoConfig;

$config = new YunxiaoConfig(
    accessToken: 'your-access-token',
    organizationId: 'your-organization-id'
);
$client = new YunxiaoClient($config);

$projects = $client->listProjects();
print_r($projects);
```

## Laravel Integration

The package will automatically register the service provider.

### Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag="yunxiao-config"
```

Then configure your settings in `.env`:

```env
YUNXIAO_PERSONAL_ACCESS_TOKEN=your_token
YUNXIAO_DOMAIN=openapi.devops.aliyun.com
YUNXIAO_ORGANIZATION_ID=your_org_id
```

### Usage

```php
use Lingb\AliyunYunxiaoDevops\YunxiaoClient;

$client = app(YunxiaoClient::class);

// List projects
$projects = $client->listProjects();

// Search workitems
$workitems = $client->searchWorkitems([
    'spaceId' => 'your-space-id',
    'category' => 'Req', // Req, Task, Bug, etc.
    'conditions' => '{"conditionGroups":[[{"fieldIdentifier":"status","operator":"CONTAINS","value":["100005"]}]]}',
]);

// Create workitem
$newWorkitem = $client->createWorkitem([
    'subject' => 'New bug title',
    'assignedTo' => 'user-id-here',
    'spaceId' => 'your-space-id',
    'workitemTypeId' => 'bug-type-id',
    'description' => 'Detailed description of the bug',
]);
```

## Manual Usage

Adjust `YunxiaoConfig` with a custom domain or organization ID when needed, and rely on the `request()` helper to call more endpoints as needed.
