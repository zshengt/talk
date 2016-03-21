<?php
class InstallAction extends Action{

	protected function _initialize(){
		if (file_exists(DATA_PATH .'install/install.lock')) {
			$this->error('已经成功安装TalkPiece，请不要重复安装!');
		}
	}
	public  function  index(){
		session_destroy();
		session_start();
		$this->display();
	}

	public function  step1(){
		session('error', false);

		$env  = $this->checkEnv();
		$dir  = $this->checkDir();
		$this->assign('env', $env);
		$this->assign('dir', $dir);

		session('step', 1);
		$this->display();
	}

	public  function  step2(){
		if(IS_POST){
			$dbhost = I('post.dbhost');
			$dbname = I('post.dbname');
			$dbuser = I('post.dbuser');
			$dbpwd  = I('post.dbpwd');
			$dbport  = I('post.dbport');

			$email     = I('post.email');
			$username  = I('post.username');
			$password  = I('post.password');
			empty($dbhost) && $this->error('服务器地址不能为空');
			empty($dbname) && $this->error('数据库名不能为空');
			empty($dbuser) && $this->error('数据库用户名不能为空');

			empty($email) && $this->error('邮箱不能为空');
			empty($username) && $this->error('用户名不能为空');
			empty($password) && $this->error('密码不能为空');

			$db = array(
			        'DB_TYPE' =>'mysql',
			        'DB_PORT' =>$dbport,
			    	'DB_HOST' =>$dbhost,
			    	'DB_NAME' =>$dbname,
			    	'DB_USER' =>$dbuser,
			    	'DB_PWD'  =>$dbpwd
			    );
			$link  =  mysql_connect($dbhost, $dbuser, $dbpwd);
			if (!$link) {
				$this->error('数据库连接失败');
			}

			$database = mysql_select_db($dbname, $link);
			if (!$database) {
				$sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
				if (!mysql_query($sql, $link)) {
					$this->error('数据库创建失败');
				}

				mysql_select_db($dbname, $link);
			}

			$this->createTables($link);
			$this->addUser($link, $username, $password, $email);
			$this->writeConfig($db);
			$this->redirect('complete');

		} else {

			session('error') && $this->error('环境检测没有通过，请调整环境后重试！');

			$step = session('step');
			if($step != 1 && $step != 2){
				$this->redirect('step1');
			}

			session('step', 2);
			$this->display();
		}
	}

	public  function  complete(){
		
		session('step', null);
		session('error', null);
		file_put_contents(DATA_PATH . 'install/install.lock', 'lock');

		$this->display();
	}


	/**
	 * 创建数据表
	 * @param  resource $db 数据库连接资源
	 */
	function createTables($link){

		$sql = file_get_contents(DATA_PATH . 'install/install.sql');
		$sql = str_replace("\r", "\n", $sql);
		$sql = explode(";\n", $sql);

		foreach ($sql as $value) {
			mysql_query($value, $link);
		}
	}

	public  function  addUser($link, $username, $password, $email){
		$salt     = rand_string( 10 );
		$password = sha1( md5( $password ) .  $salt );
		$time     = time();
		$sql =  "INSERT INTO  `talk_user` (`username`, `password`, `email`, `salt`, `create_time`, `update_time`, `is_admin`,`is_active` )  VALUES " .
				"('{$username}', '{$password}', '{$email}', '{$salt}', '{$time}', '{$time}', '1', '1' )";
		if (!mysql_query($sql, $link)) {
			$this->error('管理员添加失败');
		}
	}

	public  function  writeConfig($config){
		$conf = file_get_contents(DATA_PATH . 'install/conf.tpl');
		foreach ($config as $name => $value) {
			$conf = str_replace("[{$name}]", $value, $conf);
		}
		if(!file_put_contents(CONF_PATH.'config.php', $conf)){
			$this->error('配置文件写入失败');
		}
	}

	/**
	 * 目录，文件读写检测
	 * @return array 检测数据
	 */
	function checkDir(){
		$items = array(
			array('dir',  '可写', 'success', './Public/upload/avatar'),
			array('dir',  '可写', 'success', './Public/upload/attach'),
			array('dir',  '可写', 'success', './Runtime')
		);

		return $items;
	}

	function checkEnv(){
		$items = array(
			'os'      => array('操作系统', '不限制', '类Unix', PHP_OS, 'success'),
			'php'     => array('PHP', '5.2', '5.2+', PHP_VERSION, 'success'),
			// 'mysql'   => array('MYSQL', '5.0', '5.0+', mysql_get_server_info(), 'success'), //PHP5.5不支持mysql版本检测
			'upload'  => array('附件上传', '不限制', '2M+', '未知', 'success'),
			'gd'      => array('GD库', '2.0', '2.0+', '未知', 'success'),
			'disk'    => array('磁盘空间', '5M', '不限制', '未知', 'success'),
		);

		if($items['php'][3] < $items['php'][1]){
			$items['php'][4] = 'error';
			session('error', true);
		}

		// if($items['mysql'][3] < $items['mysql'][1]){
		// 	$items['mysql'][4] = 'error';
		// 	session('error', true);
		// }

		//附件上传检测
		if(@ini_get('file_uploads'))
			$items['upload'][3] = ini_get('upload_max_filesize');

		//GD库检测
		$tmp = function_exists('gd_info') ? gd_info() : array();
		if(empty($tmp['GD Version'])){
			$items['gd'][3] = '未安装';
			$items['gd'][4] = 'error';
			session('error', true);
		} else {
			$items['gd'][3] = $tmp['GD Version'];
		}
		unset($tmp);

		//磁盘空间检测
		if(function_exists('disk_free_space')) {
			$items['disk'][3] = floor(disk_free_space( APP_PATH ) / (1024*1024)).'M';
		}

		return $items;
	}

}
?>