<?php
return array(
    //允许模块列表
    'MODULE_ALLOW_LIST' => array('Home', 'Admin', 'Login'),

    /* 模块子域名配置 */
    'APP_DOMAIN_SUFFIX'=>'com',
    'APP_SUB_DOMAIN_DEPLOY' => 1,
    'APP_SUB_DOMAIN_RULES'  => array(
        'admin'    => 'Admin',
        'home'      => 'Home',
        'login'      => 'Login',
    ),

	//'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'myblog',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  '',    // 数据库表前缀

    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：

    'SHOW_PAGE_TRACE'=>true,

    'SESSION_TYPE' => 'Memcache',

    /* 验证码 */
    'VERIFY_CFG' => array(
        'fontSize'  =>  14,          // 验证码字体大小(px)
        'useNoise'  =>  false,       // 是否添加杂点
        'length'    =>  3,           // 验证码位数
        'useCurve'  =>  false,
    ),

    /* 模板引擎设置 */
    'TMPL_TEMPLATE_SUFFIX' => '.php',

    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'Layout',

    'MEMCACHE_HOST' => '127.0.0.1',
    'MEMCACHE_PORT' => 11211,

    'UPLOAD_MAX_SIZE' => 52428800, // 文件上传限制,50M,设为0不限制
);