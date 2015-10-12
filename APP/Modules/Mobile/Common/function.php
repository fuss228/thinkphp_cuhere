<?php

//生成用户唯一标识
function createUUID(){
	return md5(get_client_ip() . time() . mt_rand(1,1000000));
}

//验证手机号码格式
function isMobile($mobile){
	$reg = '/^1(([3][0-9])|([5][0-9])|([8][0-9]))[0-9]{8}$/';//手机号码正则
	return strlen($mobile)==11 && preg_match($reg, $mobile);
}

//用户标签输入过滤
function labelFilter($str){
	$reg1='/[\da-zA-Z]/';
	$reg2='/[\x81-\xfe][\x40-\xfe]/';	
	$returnStr='';
	$length=mb_strlen($str, 'utf-8');//字符串的长度
	for($i=0;$i<$length;$i++){
		$char=mb_substr( $str, $i, 1 ,'utf-8');
		if(preg_match($reg1, $char) || preg_match($reg2, $char)){
			$returnStr.=$char;
		}else{
			$returnStr.='#';
		}
	}
	return $returnStr;
}
//判断字符串是否为utf8编码，英文和半角字符返回ture
function isUTF8($string) {
	return preg_match('%^(?:
		[\x09\x0A\x0D\x20-\x7E] # ASCII
		| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
		| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
		| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
		| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
		| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
		| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
		| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
	)*$%xs', $string);
}

//短信发送6位数随机验证码
function createMoibleCode(){
	return rand(1,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
}

//发送手机验证码
function sendMobileCode($mobile, $text){
	$result=SendSMS_HTTP("http://pi.noc.cn/SendSMS.aspx","100673","anjietuo8",$mobile,$text."【楼下见】","5","11");
	if(stripos($result,"操作成功")===false){
		return false;
	}
	return true;
}
//用户昵称验证 （4-30个字符，中英文、数字、下划线）
function verifyUsername($username){
	$reg = '/^([a-zA-Z0-9_]|[\x{4e00}-\x{9fa5}]){4,30}$/u';//用户昵称正则
	return preg_match($reg, $username);
}
//图片大小尺寸设定
function setPicSize($str, $size){
	$arr=explode('/', $str);
	return $arr[0].'/'.$size.'_'.$arr[1];
}
//时间友好显示
function timeFriendly($str, $type=1){
	if($type==2) return date("Y-m-d H:i:s", intval($str));
	$diffTime=time()-intval($str);
	$return_str='';
	if($diffTime<30){
		$return_str='刚刚';
	}else if($diffTime>30 && $diffTime<60){
		$return_str='30秒前';
	}else if($diffTime>60 && $diffTime<60*5){
		$return_str='1分钟前';
	}else if($diffTime>60*5 && $diffTime<60*10){
		$return_str='5分钟前';
	}else if($diffTime>60*10 && $diffTime<60*30){
		$return_str='10分钟前';
	}else if($diffTime>60*30 && $diffTime<60*60){
		$return_str='30分钟前';
	}else if($diffTime>60*60 && $diffTime<60*60*24){
		//大于1个小时小于24个小时
		$return_str=floor($diffTime/3600).'小时前';
	}else if($diffTime>60*60*24 && $diffTime<60*60*24*7){
		//大于1天小于7天
		$return_str=floor($diffTime/(60*60*24)).'天前';
	}else{
		$return_str=date("Y年m月d日",intval($str));
	}
	return $return_str;
}
//判断一个二维数组中是否存在某个值
function inErArray($value, $arr, $item){
	if(!is_array($arr)){
		return false;
	}
	$newArr=array();
	for($i=0;$i<count($arr);$i++){
		$newArr[]=$arr[$i][$item];
	}
	if(in_array($value, $newArr)){
		return true;
	}
	return false;
}
/*********************************************************
 * 短信发送接口
 ********************************************************/
/**
*post请求http; 
*$remote_server：远程地址，
*$post_string：post参数
*/
function postRequest($remote_server,$post_string){
	$context = array(
		'http'=>array(
		'method'=>'POST',
		'header'=>'Content-type: application/x-www-form-urlencoded'."\r\n".
		'User-Agent : Jimmy\'s POST Example beta'."\r\n".
		'Content-length: '.strlen($post_string)+8,
		'content'=>$post_string)
	);
	$stream_context = stream_context_create($context);
	$data = file_get_contents($remote_server,FALSE,$stream_context);
	return $data;
}

/**
 *发送短信
 *@param string $url 服务器地址
 *@param string $ececcid 接入账户，非空
 *@param string $password 接入密码，非空。
 *@param string $msisdn 接收号码，多个用逗号隔开，非空。
 *@param string $smscontent 短信内容，非空。长度不能超过500字符
 *@param int $msgtype=5 短信类型，默认值为5。
 *@param int $longcode="" 扩展码，可为空。
 */
function SendSMS_HTTP($url,$ececcid,$password,$msisdn,$smscontent,$msgtype=5,$longcode=""){
	$post_string="ececcid=$ececcid&password=$password&msisdn=$msisdn&smscontent=$smscontent&msgtype=$msgtype&longcode=$longcode";		
	return postRequest($url,$post_string);
}






/*
function smsdemo()
{
	// 配置项

	$api = 'https://api.sms.mob.com/sms/verify';
	$appkey = '89eef30aaec8';

	$response = mobsmspostRequest( $api, array(
	'appkey' => $appkey,
    'phone' => '18616527736',
    'zone' => '86',
	'code' => '4231',
	) );
	return  $response;
}

*/








// 发送验证码


/**
 * 发起一个post请求到指定接口
 * 
 * @param string $api 请求的接口
 * @param array $params post参数
 * @param int $timeout 超时时间
 * @return string 请求结果
 */
/*
function mobsmspostRequest( $api, array $params = array(), $timeout = 30 ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $api );
	// 以返回的形式接收信息
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	// 设置为POST方式
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
	// 不验证https证书
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
		'Accept: application/json',
	) ); 
	// 发送数据
	$response = curl_exec( $ch );
	// 不要忘记释放资源
	curl_close( $ch );
	return $response;
}
*/









?>