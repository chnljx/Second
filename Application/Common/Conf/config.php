<?php
return array(
	//'配置�?=>'配置�?

    /* 数据库设�?*/
    'DB_TYPE'               =>  'mysql',            // 数据库类�?
    'DB_HOST'               =>  'localhost',                 // 服务器地址
    'DB_NAME'               =>  'qianmo',           // 数据库名
    'DB_USER'               =>  'root',                 // 用户�?
    'DB_PWD'                =>  '',                 // 密码
    'DB_PORT'               =>  '3306',             // 端口
    'DB_PREFIX'             =>  'qm_',               // 数据库表前缀

    /* 模板引擎设置 */
    'TMPL_L_DELIM'          =>  '<{',               // 模板引擎普通标签开始标�?
    'TMPL_R_DELIM'          =>  '}>',               // 模板引擎普通标签结束标�?
    
    'SHOW_PAGE_TRACE'       =>  true,               // 显示页面Trace信息
     //配置上传目录
    'TMPL_PARSE_STRING' =>array(
        '__UPLOAD__' => __ROOT__.'/Upload',
    ),
);
