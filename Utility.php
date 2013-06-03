<?php
class Utility{
	/**
	 * Convert associative array to a string.  Only handles
	 * 1 dimmensional arrays
	 */
	public static function toStr($arr){
		$str = '';
		foreach($arr as $key=>$val)
			$str .= $key . ' => ' . $val . ', ';
			
		return rtrim($str,', ');
	}

	/**
	 * Write to a log file with a time stamp
	 */
	public static function log($file, $msg){
		if(LOGGING)
			file_put_contents($file, date(DATE_ATOM) . "\t$msg\n", FILE_APPEND);
	}

	/**
	 * Wrapper for getting a URL variable.  Gurantees a string is returned.  Either
	 * the variable requested or '' if the URL variable doesnt exist.
	 */
	public static function myGet($index){
		return (isset($_GET[$index])) ? $_GET[$index] : '';
	}
}
?>