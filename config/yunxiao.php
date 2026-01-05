<?php

return [
    /**
     * 个人访问令牌 (Personal Access Token)
     * 获取个人访问令牌，具体操作，请参见获取个人访问令牌。
     */
    'personal_access_token' => env('YUNXIAO_PERSONAL_ACCESS_TOKEN', ''),

    /**
     * 服务接入点 (Domain)
     * 获取服务接入点，替换 API 请求语法中的 <domain>
     */
    'domain' => env('YUNXIAO_DOMAIN', 'openapi-rdc.aliyuncs.com'),

    /**
     * 组织 ID (Organization ID)
     * 请前往组织管理后台的基本信息页面获取组织 ID 。
     */
    'organization_id' => env('YUNXIAO_ORGANIZATION_ID', ''),
];
