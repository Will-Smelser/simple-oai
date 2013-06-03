<?php

interface Verb{
	public function doVerb();
}

class Base{
	public function getRequest(){
		$prot = ($_SERVER['SERVER_PORT'] === '443') ? 'https://' : 'http://';
		return $prot . $_SERVER["SERVER_NAME"] . htmlentities($_SERVER['REQUEST_URI']);
	}
}

class VerbBase extends Base{
	

	public function sprintf($args){
	
		$args = func_get_args();
		$file = array_shift($args);
		
		$str = file_get_contents($file);
		
		if(empty($str) || $str < 0)
			throw new Exception('Failed to read file ('.$file.').');
			
		array_unshift($args, $str);
		
		return call_user_func_array('sprintf', $args);
	}
}
?>