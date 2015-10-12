<?php
return array(
	//路由配置
	'URL_MODEL' => 2,
	'URL_ROUTER_ON' => true,
	'URL_ROUTE_RULES' => array(
	),
	
	'URL_CASE_INSENSITIVE' =>true,
	
	//取消伪静态
	'URL_HTML_SUFFIX' => '',
		
	//配置该分组__PUBLIC__目录
	'TMPL_PARSE_STRING' => array(
		'__PUBLIC__' => __ROOT__.'/'.APP_NAME.'/'.C('APP_GROUP_PATH').'/'.GROUP_NAME.'/Tpl/Public',
	),
);
?>