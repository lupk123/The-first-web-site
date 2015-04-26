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
		$str = trim($str);
		//限制长度
		if(mb_strlen($str, 'utf-8') < $min_num || mb_strlen($str, 'utf-8') > $max_num)
			alert_back('用户名长度不得小于'.$min_num.'位或者大于'.$max_num.'位');
		//限制敏感字符
		$pattern = '/[<>\'\"\ \	]/';
		if(preg_match($pattern, $str))
			alert_back('用户名不得包含敏感字符!');
		//将用户名转义输入
		return mysql_string($str);
	}
	
	/* check_pwd(); 用来检查密码并加密
	 * @access public
	* @param string $pwd;
	* @param ing $num; 密码的最小长度
	* return string $pwd; 返回加密后的密码
	*/
	function check_pwd($pwd, $num)
	{
		//密码长度限制
		if(strlen($pwd) < $num)
			alert_back('密码长度不得小于'.$num.'位!');
		return mysql_string(sha1($pwd));
	}
	
	/* check_time(); 转义保留的时间
	 * @access publid
	 * return string $str; 返回转以后的字符串
	 */
	function check_time($str)
	{
		$time = array(0, 1, 2, 3);
		if(!in_array($str, $time))
			alert_back('保留方式出错');
		return mysql_string($str);
	}

	/* set_coolie(); 设置用户的cookie
	 * @access public
	 * @param string $username; 用户名cookie
	 * @param string $uniqid; 用户唯一标示符 安全操作使用
	 * return void;
	 */
	function set_cookie($username, $uniqid, $time)
	{
		setcookie('username', $username);
		setcookie('uniqid', $uniqid);	
		switch($time)
		{
			case 0: //不保留
				setcookie('username', $username);
				setcookie('uniqid', $uniqid);
				break;
			case 1: //一天
				setcookie('username', $username, time() + 86400);
				setcookie('uniqid', $uniqid, time() + 86400);
				break;
			case 2: //一周
				setcookie('username', $username, time() + 604800);
				setcookie('uniqid', $uniqid, time() + 604800);
				break;
			case 3: //一月
				setcookie('username', $username, time() + 2592000);
				setcookie('uniqid', $uniqid, time() + 2592000);
				break;
		}
	}
?>