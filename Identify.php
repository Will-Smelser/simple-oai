<?php

/**
 * List records class
 */
 require_once 'Verb.php';
 
 class Identify 
		extends VerbBase
		implements Verb{
		
	public function Identify(){
		//empty
	}	
	
	public function doVerb(){
		return file_get_contents('default_responses/identify.xml');
	}
 }

?>