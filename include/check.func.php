<?php
	if(!defined("IN_TG"))
		exit('error access!');
	if(!function_exists('alert_back'))
		exit('alert_back函数不存在');
	if(!function_exists('mysql_string'))
		exit('mysql_string函数不存在');
	
/* check_string(): 用来过滤敏感的字符
 * @param string $str受污染的用户名
 * @return public
 * @param int $min_num
 * @param int $max_num
 * return string 过滤后的用户名
 */	
	function check_username($str, $min_num, $max_num)
	{
		global $sys;
		$str = trim($str);
		//限制长度
		if(mb_strlen($str, 'utf-8') < $min_num || mb_strlen($str, 'utf-8') > $max_num)
			alert_back('用户名长度不得小于'.$min_num.'位或者大于'.$max_num.'位');
		//限制敏感字符
		$pattern = '/[<>\'\"\\ ]/';
		if(preg_match($pattern, $str))
			alert_back('用户名不得包含敏感字符!');
		//限制敏感用户名
		$sensitive_name[0] = 'lupk_z';
		$mg = explode('|', $sys['tg_string']);
// 		foreach ($sensitive_name as $value)
// 			$mg .= $value.'\n';		
		//绝对匹配
		if(in_array($str, $mg))
		{
			alert_back($sys['tg_string'].'以上敏感用户名不得注册');
		}
		//将用户名转义输入
		return mysql_string($str);
	}
	
/* check_pwd(); 用来检查密码并加密
 * @access public
 * @param string $pwd; 
 * @param stinrg $tpwd; 密码确认
 * @param ing $num; 密码的最小长度
 * return string $pwd; 返回加密后的密码 
 */
	function check_pwd($pwd, $tpwd, $num)
	{
		//密码长度限制
		if(strlen($pwd) < $num)
			alert_back('密码长度不得小于'.$num.'位!');
		//密码和密码确认必须一致
		if($pwd != $tpwd)
			alert_back('密码和密码确认不一致!');
		return mysql_string(sha1($pwd));		
	}
	
/* check_pwd(); 用来检查密码并加密 在修改个人资料密码项时需要。。。
 * @access public
 * @param string $pwd;
 * @param ing $num; 密码的最小长度
 * return string $pwd; 返回加密后的密码 
 */
	function check_modify_pwd($pwd, $num)
	{
		if(!empty($pwd))
		{
			if(strlen($pwd) < $num)		//密码长度限制
				alert_back('密码长度不得小于'.$num.'位!');
			return mysql_string(sha1($pwd));
		}
		return null;		
	}
	
/* check_prompt(); 用来检查密码提示
 * @access public
 * @param string $prompt; 密码提示
 * @param int $min_num; 最小长度
 * @param int $max_num; 最大长度
 * return string $pwd; 返回转以后的字符串
 */
	function check_prompt($prompt, $min_num, $max_num)
	{
		//除去多余的空格
		$prompt = trim($prompt);
		//限制长度
		if(mb_strlen($prompt, 'utf-8') < $min_num || mb_strlen($prompt, 'utf-8') > $max_num)
			alert_back('密码提示长度不得小于'.$min_num.'位或者大于'.$max_num.'位');		
		return mysql_string($prompt);
	}
	
/* check_ans(); 用来检查密码回答
 * @access public
 * @param string $prompt; 密码提示
 * @param stirng $ans; 密码回答
 * @param int $min_num; 最小长度
 * @param int $max_num; 最大长度
 * return string $ans; 返回加密后的密码回答
 */
	function check_ans($prompt, $ans, $min_num, $max_num)
	{
		//限制长度
		if(mb_strlen($ans, 'utf-8') < $min_num || mb_strlen($ans, 'utf-8') > $max_num)
			alert_back('密码回答长度不得小于'.$min_num.'位或者大于'.$max_num.'位');
		//密码提示与密码回答不得一致
		if($prompt == $ans)
			alert_back('密码提示不能与密码回答一致!');
		//返回加密后的密码回答
		return mysql_string(sha1($ans));
	}
	
/* check_email(); 用来检测邮件格式是否合格； 若邮件为空，直接返回null
 * @access public
 * @param string $email 邮箱
 * @param int $min_num;
 * @param int $max_num;
 * return string $email 返回验证后的邮箱，若邮箱为空则返回null
 */
	function check_email($email, $min_num, $max_num)
	{	
		if(mb_strlen($email, 'utf-8') < $min_num || mb_strlen($email, 'utf-8') > $max_num)
			alert_back('邮箱长度不得小于'.$min_num.'位或者大于'.$max_num.'位');
		//正则表达式判断是否匹配成功
		$pattern = '/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/';
		if(!preg_match($pattern, $email))
			alert_back('邮箱格式不合格!');
	
		return mysql_string($email);
	}
	
/* check_sex(); 用来转义性别
 * @access public
 * @param string $str;
 * return string $str; 
 */
	function check_sex($str)
	{
		return mysql_string($str);
	}

/* check_face(); 用来转义头像地址
 * @access public
 * @param stirng $str;
 * return stirng $str;
 */ 
	function check_face($str)
	{
		return mysql_string($str);
	} 
	
/* check_qq(); 用来验证qq
 * @access public
 * @param int $qq; 
 * return int $qq; 返回验证后的qq号，若为空则返回空
 */
	function check_qq($qq)
	{
		if(!empty($qq))
		{
			//qq 5-10位 且第一位不能为0 本课程默认
			$pattern = '/^[1-9]{1}[0-9]{4,14}$/';
			if(!preg_match($pattern, $qq))
				alert_back('qq格式不正确!');
		}
		else 
			$qq = null;
		return $qq;
	}
	
/* check_url(); 用来验证网址
 * @access public
 * @param string $url; 网址
 * return string $url; 验证后的网址 
 */
	function check_url($url, $max_num)
	{		
		if(!(empty($url) || $url == 'http://'))
		{
			if(strlen($url) > $max_num)
				alert_back('网址长度不得超过'.$max_num);
			$pattern = '/^https?:\/\/(\w\.)?[\w\-\.]+(\.\w+)+$/';
			if(!preg_match($pattern, $url))
				alert_back('网址格式不正确!');	
		}
		else 
			$url = null;
		return mysql_string($url);
	}

/* check_uniqid(); 用来判断唯一标示符
 * @access public
 * @param string $post_uniqid;
 * @param string $uniqid;
 * return string $uniqid;
 */
	function check_uniqid($post_uniqid, $uniqid)
	{		
		if(($post_uniqid != $uniqid) || (strlen($post_uniqid) != 40))
			alert_back('唯一标示符异常!');
		return mysql_string($uniqid);
	}
/*
 * 
 */
	function check_content($str)
	{
		if(mb_strlen($str, 'utf-8') >= 200 || mb_strlen($str, 'utf-8') == 0)
			alert_back("内容长度不得大于200位且不得为空！");
		return trim($str);
	}
/*
 * 
 */
	function check_title($str, $min, $max)
	{
		if(mb_strlen($str, 'utf-8') > $max || mb_strlen($str, 'utf-8') < $min)
			alert_back('标题长度不得小于'.$min.'且不得大于'.$max.'!');
		return $str;
	}
/*
 * 
 */
	function check_post_content($str, $min)
	{
		if(mb_strlen($str, 'utf-8') < $min)
			alert_back('内容长度不得小于'.$min.'位!');
		return $str;
	}
	
	function check_autograph($str, $max)
	{
		if(mb_strlen($str, 'utf-8') > $max)
			alert_back('签名长度不得超过'.$max.'位!');
		
		return $str;
	}
	
	function check_switch($str)
	{
		if($str != 0 && $str != 1)
			$str = 0;
		return $str;
	}
	
?>