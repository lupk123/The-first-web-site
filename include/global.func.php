<?php
//删除非空目录函数
function removeDir($dirName)
{
    if(!is_dir($dirName))
    {
        return false;
    }
    $handle = @opendir($dirName);
    while(($file = @readdir($handle)) !== false)
    {
        if($file != '.' && $file != '..')
        {
            $dir = $dirName . '/' . $file;
            is_dir($dir) ? removeDir($dir) : @unlink($dir);
        }
    }
    closedir($handle);    
    return rmdir($dirName) ;
}


/*
 * runtime()用来获取程序执行耗时；
 * @access public 表示函数对外公开
 * @return float 函数返回值是浮点型
 * mircotime() 用来获取当前时间戳 和 微秒数 空格前面是微秒数 空格后面是时间戳
 * explode() 用来将字符串分离
 */
	function runtime()
	{
		$exp = explode(' ', microtime());
		return $exp[1] + $exp[0];
	}
/* sha1_uniqid(); 用来产生唯一标示符
 * @access public
 * return string $uniqid; 返回唯一标示符
 */
	function sha1_uniqid()
	{
		return mysql_string(sha1(uniqid(rand(), true)));
	}
	
/* thumb(); 用来创建缩略图
 * @access public
 */
	function thumb($filename, $new_width, $new_height)
	{
		$n = explode('.', $filename);
		//生成标头文件
		header("Content-type: image/png");
		//获取文件的长和高
		list($width, $height) = getimagesize($filename);
// 		//生成微缩长度
// 		$new_width = $width;
// 		$new_height = $heigh;
		//创建一个以0.3百分比为新长度的画布
		$new_img = imagecreatetruecolor($new_width, $new_height);
		//按照已有的图片创建一个画布
		//	$image = imagecreatefrompng('photo/1429530196/1429710223.png');
		switch($n[1])
		{
			case 'jpg': $image = imagecreatefromjpeg($filename); break;
			case 'gif': $image = imagecreatefromgif($filename); break;
			case 'png': $image = imagecreatefrompng($filename); break;
		}
		//将原图采样后放入新绘制的画布上
		imagecopyresampled($new_img, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		//生成png图片
		imagepng($new_img);
		//销毁
		imagedestroy($new_img);
		imagedestroy($image);
	}	

/*
 * alert_back()是js弹窗
 * @access public
 * @return void
 * @param string $str 表示要弹出的提示
 */
	function alert_back($str)
	{
		echo "<script type='text/javascript'>alert('".$str."');history.back();</script>";
		exit();	
	}
/* alert_close(); js弹窗后关闭窗口
 * @access public
 * @param string $str;
 * return void;
 * 
 */
	function alert_close($str)
	{
		echo "<script type='text/javascript'>alert('".$str."');window.close();</script>";
		exit();
	}
	
/*
 * check_code(); 用来检查验证码是否错误
 * @access public
 * @param string $post_code;
 * @param string $session_code;
 * return void;
 */
	function check_code($post_code, $session_code)
	{
		if($post_code != $session_code)
			alert_back('验证码错误!');
	}
	
/* code()是验证码函数
 * @access public
 * @param int $width 表示验证码的长度
 * @param int $height 表示验证码的高度
 * @param int $num 表示验证码的个数
 * @param int $flag 表示是否要验证码的边框 0表示不要 1表示要
 * @return void 函数执行后产生一个验证码
 * 
 */
	function code($width = 75, $height = 25, $num = 4, $flag = 0)
	{		
		for($i = 0; $i < $num; $i++)
			//累积到后面
			$nn .= dechex(mt_rand(0, 15));
		$_SESSION['code'] = $nn;		
		//创建图片
		$img = imagecreatetruecolor($width, $height);
		//给图片分配颜色并填充颜色
		$color = imagecolorallocate($img, 255, 255, 255);
		imagefill($img, 0, 0, $color);
		if($flag)
		{
		//创建黑色边框
			$black = imagecolorallocate($img, 0, 0, 0);
			imagerectangle($img, 0, 0, $width-1, $height-1, $black);
		}		
		//随即创建六条线条
		for($i = 0; $i < 6; $i++)
		{
			$rand_color = imagecolorallocate($img, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));
			imageline($img, mt_rand(0, $width-1), mt_rand(0, $height-1), mt_rand(0, $width-1), mt_rand(0, $height-1), $rand_color);
		}
		//随机打雪花
		for($i = 0; $i < 100; $i++)
		{		
			$rand_color = imagecolorallocate($img, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));
			imagestring($img, 2, mt_rand(1, $width-1), mt_rand(1, $height-1), '*', $rand_color);
		}
		//输出验证码	
		for($i = 0; $i < $num; $i++)
		{
			imagestring($img, mt_rand(3, 5), $width / $num * $i + mt_rand(1, 10),
			mt_rand(1, $height / 2), $_SESSION['code'][$i],
			imagecolorallocate($img, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200)));
		}
		
		//输出图片
		header('Content-Type: image/png');
		imagepng($img);
		imagedestroy($img);
	}

/* location(); 注册成功提示
 * @access public
 * @param string $info;
 * return void 
 */
	function _location($info, $url)
	{
		if($info)
		{
			echo "<script type = 'text/javascript'>alert('$info'); location.href = '$url';</script>";
			exit();
		}
		header('Location:'.$url);
	}
/* session_des(); 删除session
 * @access public
 * return void;
 */
	function session_des()
	{
		@session_destroy();
	}
	
/* unsetcookie(); 删除cookie;
 * @access public
 * return void;
 */
	function unsetcookie()
	{
		setcookie('username', '', time() - 1);
		setcookie('uniqid', '', time() - 1);
		session_des();
		_location(null, 'index.php');
	}
	
/* login_state(); 防止登录状态仍然可以注册
 * @access public
 * return voidl
 */
	function login_state()
	{
		if(isset($_COOKIE['username']))
			alert_back('登录状态无法进行此操作!');
	}
	
/* paging(); 分页函数
 * @access public
 * @param int $type; 分页类型 1是数字分页形式 2是文字分页形式
 * @param int $num; 会员总数
 * @param int $page; 总页数
 * @param int $c; 现在处于第几页
 * return void;
 */
	function paging($type, $str)
	{
		global $num, $page, $c, $id;
		if($type == 1)
		{//以数字分页形式
			echo '<div id = "page">';
			echo '<ul>';
				for($i = 1; $i <= $page; $i++)
				{				
					if($c == $i)
						echo "<li><a href = '".SCRIPT.".php?".$id."page="."$i "."' class = 'selected'>"."$i"."</a></li>";
					else
						echo "<li><a href = '".SCRIPT.".php?".$id."page="."$i"."' >"."$i"."</a></li>";
				}
			echo '</ul>';
			echo '</div>';
		}
		else if($type >= 2)
		{//以文字分页的形式
			echo '<div id = "page_text">';
			echo '<ul>';
			echo "<li>"."$c"."/"."$page"."页 |</li>";
			echo "<li> 共<strong>".$num."</strong>".$str." |</li>";			
				$temp = $c;
				if($temp != 1)
				{
					$temp--;
					echo "<li><a href = '".SCRIPT.".php?".$id."page=1'>首页 |</a></li>";
					echo "<li><a href = '".SCRIPT.".php?".$id."page=".$temp."'>上一页 |</a></li>";
				}
				else
				{
					echo "<li>首页 |</li>";
					echo "<li>上一页 |</li>";
				}
				$temp = $c;
				if($temp != $page)
				{
					$temp++;
					echo "<li><a href = '".SCRIPT.".php?".$id."page=".$temp."'>下一页 |</a></li>";
					echo "<li><a href = '".SCRIPT.".php?".$id."page=".$page."'>尾页</a></li>";
				}	
				else 	
				{
					echo "<li>下一页 |</li>";
					echo "<li>尾页</li>";
				}
											
				echo '</ul>';
			echo '</div>';
		}
	}

/* page(); 分页函数 计算分页所需的参数等
 * @access public;
 * @param int $page_num; 每页显示个数
 * @param int $result; 查询结果集
 * return void;
 */
	function page($page_num, $result)
	{
		global $num, $page, $c, $page_start;
		//取得数据库所有元素的行数
		$num = num_rows($result);
		//页数
		$page = ceil($num / $page_num);
		//如果数据库为空的情况下默认显示一页
		if($num == 0)
			$page = 1;
		if(isset($_GET['page']))
		{
			$c = $_GET['page'];
			//如果传过来的参数大于应该显示的页数则默认显示最后一页
			if($c > $page)
				$c = $page;
			//如果传过来的参数为空，小于0 或者不是数字 则默认第一页
			if(empty($c) || $c <= 0 || !is_numeric($c))
				$c = 1;
			else
				$c = intval($c);
			$page_start = ($c - 1) * $page_num;
		}
		else
		{
			$page_start = 0;
			$c = 1;
		}
		
	}
		
/* html() 用来过滤危险字符
 * @access public
 * @param string $str || array $str; 
 * return string || array;
 */
	function html($str)
	{
		if(is_array($str))
			foreach ($str as $key => $value)
				$str[$key] = html($value);
		else
			$str = htmlspecialchars($str);
		return $str;
	}
	

/* mysql_string(); 用来转义字符串
 * @access public
* @param string $str; 需要转义的字符串
* return string $str;
*/
	function mysql_string($str)
	{
		//PHP5自带了转义功能，若该功能开启了则get_magic_quotes_gpc()为1，否则为0
		if(!GPG)
		{
			if(is_array($str))
				foreach ($str as $key => $value)
					$str[$key] = mysql_string($value);
			else
				$str = mysql_real_escape_string($str);
		}
		
		return $str;
	}
/* check_uni() 验证唯一标示符是否正确 防止伪造cookie登陆
 * @access public
 * @param string $mysql_uniqid
 * @param string $cookie_uniqid
 * return void;
 */
	function check_uni($mysql_uniqid, $cookie_uniqid)
	{
		if($mysql_uniqid != $cookie_uniqid)
			alert_back('唯一标示符不合法!');
	}	
/* title();截取长度为14的字符串
 * @access public
 * @param string $str;
 * return string $str;
 */
	function title($str, $num)
	{
		if(mb_strlen($str, 'utf-8') > $num)
		{
			$str = mb_substr($str, 0, $num - 2, 'utf-8');
			$str .= '..';
		}
		return $str;
	}
/*
 * 
 */
	function set_xml($fp, $clean)
	{
		$fp = @fopen('new.xml', 'w');
		if(!$fp)
			exit('系统错误， 文件不存在');
		flock($fp, LOCK_EX);
		$string = "<?xml version = \"1.0\" encoding = \"utf-8\"?>\r\n";
		fwrite($fp, $string, strlen($string));
		$string = "<vip>\r\n";
		fwrite($fp, $string, strlen($string));
		$string = "\t<id>{$clean['id']}</id>\r\n";
		fwrite($fp, $string, strlen($string));
		$string = "\t<username>{$clean['username']}</username>\r\n";
		fwrite($fp, $string, strlen($string));
		$string = "\t<sex>{$clean['sex']}</sex>\r\n";
		fwrite($fp, $string, strlen($string));
		$string = "\t<face>{$clean['face']}</face>\r\n";
		fwrite($fp, $string, strlen($string));
		$string = "\t<email>{$clean['email']}</email>\r\n";
		fwrite($fp, $string, strlen($string));
		$string = "\t<url>{$clean['url']}</url>\r\n";
		fwrite($fp, $string, strlen($string));
		$string = "</vip>\r\n";
		fwrite($fp, $string, strlen($string));
		flock($fp, LOCK_UN);
	}
	/*
	 * 
	 */
	function get_xml($xmlfile)
	{
		//读取xml文件
		if(!file_exists($xmlfile))
			exit('文件不存在');
		$xml = file_get_contents($xmlfile);
		preg_match_all('/<vip>(.*)<\/vip>/s', $xml, $dom);
		foreach($dom[1] as $value)
		{
			preg_match_all('/<id>(.*)<\/id>/s', $value, $id);
			preg_match_all('/<username>(.*)<\/username>/s', $value, $username);
			preg_match_all('/<sex>(.*)<\/sex>/s', $value, $sex);
			preg_match_all('/<face>(.*)<\/face>/s', $value, $face);
			preg_match_all('/<email>(.*)<\/email>/s', $value, $email);
			preg_match_all('/<url>(.*)<\/url>/s', $value, $url);
			$clean['id'] = $id[1][0];
			$clean['username'] = $username[1][0];
			$clean['sex'] = $sex[1][0];
			$clean['face'] = $face[1][0];
			$clean['email'] = $email[1][0];
			$clean['url'] = $url[1][0];
		}
		return $clean;
	}
/* _ubb(); 解析ubb
 * 
 */
	function _ubb($string)
	{
		$string = nl2br($string);
		$string = preg_replace('/\[b\](.*)\[\/b\]/U', '<strong>\1</strong>', $string);
		$string = preg_replace('/\[i\](.*)\[\/i\]/U', '<em>\1</em>', $string);
		$string = preg_replace('/\[u\](.*)\[\/u\]/U', '<span style = "text-decoration: underline";>\1</span>', $string);
		$string = preg_replace('/\[s\](.*)\[\/s\]/U', '<span style = "text-decoration: line-through";>\1</span>', $string);
		$string = preg_replace('/\[color=(.*)\](.*)\[\/color\]/U', '<span style = "color: \1">\2</span>', $string);
		$string = preg_replace('/\[size=(.*)\](.*)\[\/size\]/U', '<span style = "font-size: \1px">\2</span>', $string);
		$string = preg_replace('/\[url\](.*)\[\/url\]/U', '<a href = "\1" target = "_blank">\1</a>', $string);
		$string = preg_replace('/\[email\](.*)\[\/email\]/U', '<a href = "mailto: \1">\1</a>', $string);
		$string = preg_replace('/\[img\](.*)\[\/img\]/U', '<img src = "\1"/>', $string);
		return $string;
	}
/*
 * 
 */
	function _timed($now, $pass, $med, $str)
	{
			$time_sub = $now - $pass;
			if($time_sub < $med)
				alert_back($str);
	}
?>
