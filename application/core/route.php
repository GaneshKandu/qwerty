<?php

class Route
{

	static function start()
	{
		$controller_name = 'Main';
		$action_name = 'index';
		
		$port = ($_SERVER['SERVER_PORT'] == 80)?'':':'.$_SERVER['SERVER_PORT'];
		
		$baseurl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$port;
		
		if(isset($_SERVER['PATH_INFO'])){
			$a = $_SERVER['PATH_INFO'];
			$baseurl .= rtrim($_SERVER['REQUEST_URI'],$_SERVER['PATH_INFO']);
		}else{
			$a = urldecode($_GET['_p']);
		}

		$routes = array_filter(array_values(explode("/",$a)));		
		
		if (!empty($routes[0]))
		{
			$controller_name = $routes[0];
		}
		
		
		if ( !empty($routes[1]))
		{
			$action_name = $routes[1];
		}

		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;

		$model_file = strtolower($model_name).'.php';
		$model_path = "application/models/".$model_file;
				
		if(file_exists($model_path))
		{
			include "application/models/".$model_file;
		}

		$controller_file = strtolower($controller_name).'.php';
		
		$controller_path = "application/controllers/".$controller_file;
		
		if(file_exists($controller_path))
		{
			include "application/controllers/".$controller_file;
		}
		else
		{
			self::ErrorPage404();
			include "application/controllers/controller_404.php";
			$controller_name = 'Controller_404';
		}
		
		$controller = new $controller_name;
		$action = $action_name;
		
		if(method_exists($controller, $action))
		{	
			$controller->$action();
		}
		else
		{
			self::ErrorPage404();
			$controller->action_index();
		}
	
	}

	function ErrorPage404()
	{
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
    }
	
}
