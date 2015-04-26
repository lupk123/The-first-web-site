<?php
	//防止恶意调用
	if(!defined("IN_TG"))
		exit('error access!');
	
/* mysql_conn(); 数据库连接函数
 * @access public
 * return void; 
 */
	function mysql_conn()
	{		
		//全局变量
		global $conn;
		if(!$conn = @mysql_connect(DB_HOST, DB_USER, DB_PWD))
			exit('数据库连接失败!');
	}

/* affected_rows(); 返回影响的记录数
 * @access publid
 * return int; 返回的是操作的记录数
 */
	function affected_rows()
	{
		return mysql_affected_rows();
	}
/* select_db(); 选择一款数据库
 * @access public
* return void;
*/
	function select_db()
	{
		if(!@mysql_select_db(DB_NAME))
			exit('该数据库不存在!');
	}
	
/* query_charset(); 设置字符集
 * @access public
 * return void;
 */
	function query_charset()
	{
		if(!mysql_query('SET NAMES UTF8'))
			exit('设置字符集失败!');
	}

/* query(); 执行SQL语句
 * @access public
 * @param string $str; 
 * return void;
 */
	function query($str)
	{
		if(!$query = mysql_query($str))
			exit('SQL执行失败'.mysql_error());
		return $query;		
	}
	
/* fetch_array(); 先查询然后将查询结果返回到数组中
 * @access public
 * @param $sql; SQL语句
 * return array; 返回的是一个数组
 */
	function fetch_array($sql)
	{
		return mysql_fetch_array(query($sql), MYSQL_ASSOC);			
	}
/* fetch_array_list(); 将查询结果放入数组
 * @access public
 * @param string $result; 查询结果
 * return array(); 返回数组
 */
	function fetch_array_list($result)
	{
		return mysql_fetch_array($result, MYSQL_ASSOC);
	}
	
/* is_repeat(); 判断是否重复
 * @access public;
 * @param string $sql; SQL语句；
 * @param string $info; 提示
 * return void;
 */
	function is_repeat($sql, $info)
	{
		if(fetch_array($sql))
			alert_back($info);
	}
	
/* close(); 关闭数据库
 * @access public;
 * return void;
 */
	function close()
	{
		if(!mysql_close())
			exit('数据库关闭失败');
	}
/*
 * 
 */
	function num_rows($result){
		return mysql_num_rows($result);
	}
/*
 * 
 */
	function free_result($result)
	{
		mysql_free_result($result);
	}
/*
 * 
 */
	function insert_id()
	{
		return mysql_insert_id();	
	}	
?>