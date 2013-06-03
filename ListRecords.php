<?php

/**
 * List records class
 */
 require_once 'Verb.php';
 
 class ListRecords 
		extends VerbBase
		implements Verb{
		
	private $rawSet;
	private $set;
	private $setDir = 'sets/';
	
	public function ListRecords($set = null){
		$this->rawSet = $set;
		$this->set = $this->setDir . ((empty($set)) ? 'badset' : $set);
	}
	
	public function doVerb($resumption = null){
		//set doesnt exist
		if(!is_dir($this->set))
			return $this->doBadSet();
		
		
		$loadFiles = array();
		$files = scandir($this->set);
		
		foreach($files as $file){
			if($file[0] !== '.' && substr($file,-3) === 'xml'){
				array_push($loadFiles, $file);
			}
		}
		
		//no xml to load
		if(count($loadFiles) <= 0)
			return $this->doBadSet();
		
		$key = 0;
		if(!empty($resumption)){
			$parts = explode(':',$resumption);
			$key = $parts[1];
		}
		
		$content = file_get_contents($this->set . '/' . $loadFiles[$key]);
		
		//for convienence we remove any resumption tokens
		$content = preg_replace('/\<resumptionToken\>.*\<\/resumptionToken\>/i','',$content);
		$content = preg_replace('/\<resumptionToken[\s+]?\/\>/i','',$content);
		
		if(count($loadFiles) > 1){		
			$key++;

			//remove all end stuff
			$content = str_replace('</ListRecords>','',$content);
			$content = str_replace('</OAI-PMH>','',$content);
			
			$content .= ($key < count($loadFiles)) ? "<resumptionToken>{$this->rawSet}:$key</resumptionToken>" : '<resumptionToken />';
			
			$content .= "</ListRecords></OAI-PMH>";
			
		}
		
		return $content;
	}
	
	private function doBadSet(){
		$url = $this->getRequest();
		return $this->sprintf('sets/defaults/badset/badset.xml', date(DATE_ATOM),$this->set,$url);
	}
	
 }

?>