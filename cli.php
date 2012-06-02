#!/usr/bin/env php
<?php

define('SUPPRESS_REQUEST', true);

include __DIR__ . "/../../public/index.php";

try{
	if ( ! (php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) )
	{
		throw new Kohana_Exception("This can only be executed from the command line");
	}

	if( ! isset($argv[1]) OR ($argv[1] == 'help' AND ! isset($argv[2])))
	{
		Command::log("Command Line Kohana Tool");
		Command::log("  ".Command::colored("list", "green")." - list commnads");
		Command::log("  ".Command::colored("help {command}", "green")." - get help for command");
		Command::log("  ".Command::colored("{command}", "green")." - run the command");
	}
	elseif( $argv[1] == 'list')
	{
		$files = Kohana::list_files('command');
		foreach($files as $filename => $file)
		{
			$name = pathinfo($filename, PATHINFO_FILENAME);
			require_once $file;
			
			$class = new ReflectionClass('Command_'.ucfirst($name));

			Command::log($name, "brown");

			foreach($class->getMethods() as $method)
			{
				if($method->isPublic() AND ! $method->isConstructor() AND ! $method->isDestructor() AND $method->getDeclaringClass()->getName() == $class->getName())
				{
					Command::log(
						"  ".
						Command::colored(str_pad($name.':'.str_replace('_', ':', $method->getName()), 38, " ", STR_PAD_RIGHT), "green"). 
						$class->getConstant(strtoupper($method->getName()."_brief"))
					);
				}
			}
		}
	}
	elseif($argv[1] == 'help')
	{
		$arguments = explode(':', $argv[2]);

		$class = new ReflectionClass(Command::load_command_file($arguments[0]));

		$method_name = isset($arguments[1]) ? join('_', array_slice($arguments,1)) : 'index';

		$description = $class->getConstant(strtoupper("{$method_name}_desc"));
		if( ! $description)
		{
			$description = $class->getConstant(strtoupper("{$method_name}_brief"));
		}
		if( ! $description)
		{
			$description = "Missing description";
		}
		
		Command::log($argv[2], "brown");
		Command::log($description);
	}
	else
	{
		$options = Command_Options::factory()->populate_from_argv();

		$method_arguments = array($argv[1], $options);
		foreach(array_slice($argv, 2) as $argument)
		{
			if(substr($argument, 0, 2) !== '--')
			{
				$method_arguments[] = $argument;
			}
		}

		call_user_func_array('Command::execute', $method_arguments);
	}
}
catch( Exception $e)
{
	if(Kohana::$config->load('cli.capture_exceptions'))
	{
		Command::log(Kohana_Exception::text($e), Command::ERROR);	
	}
	else
	{
		throw $e;
	}
}
