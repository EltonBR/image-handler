<?php

spl_autoload_register(function($namespace)
{
	$require_path = "src\\".$namespace.".php";
	$require_path = str_replace("\\", "/", $require_path);

	if (file_exists($require_path))
	{
		require($require_path);
	}

});

?>