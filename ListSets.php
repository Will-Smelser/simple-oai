<?php

/**
 * List Sets class
 */
 require_once 'Verb.php';
 
 class ListSets 
		extends VerbBase
		implements Verb{
		
	public function ListSets(){
		//empty
	}	
	
	public function doVerb(){
		$sets = "";
		foreach(scandir('sets') as $file)
			if($file[0] !== '.' && $file !== 'defaults')
				$sets .= "\n\t<set>\n\t\t<setSpec>$file</setSpec>\n\t\t<setName>Set $file</setName>\n\t</set>";
				
		return $this->sprintf('sets/defaults/listsets/listsets.xml', date(DATE_ATOM), $sets);
	}
 }

?>