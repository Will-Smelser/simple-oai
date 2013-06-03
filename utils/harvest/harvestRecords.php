<?php
set_time_limit(120*60); //30 minutes

error_reporting(E_ALL);

$SAVEDIR = 'harvested/';

$URL = 'http://arno.uvt.nl/oai/wo.uvt.nl.cgi?verb=ListRecords';
$METAPREFIX = "metadataPrefix=oai_dc";
$SETSPEC = "set=wcl-ir-all";

//max number of resumption tokens to follow...basically OAI pagination
$MAXPAGES = 100;


function harvest($outputFile, $resumption=null){
	global $URL, $METAPREFIX, $SETSPEC, $SAVEDIR;
	
	echo "<hr>";
	$url = $URL;
	
	if(!empty($resumption)){
		$url .= '&resumptionToken='.$resumption;
	}else{
		$url .= '&' . $SETSPEC . '&'.$METAPREFIX;
	}

	$result = '';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	
	echo "Request: $url<br>Save File: $outputFile<br/>resumptionToken=".$resumption;
	file_put_contents($SAVEDIR . $outputFile, $result);
	
	//find the resumption token
	$start = strpos($result, '<resumptionToken>') + strlen('<resumptionToken>');
	$end   = strpos($result, '</resumptionToken>');
	
	echo "<hr/>";
	
	if($start > 0 && $end > 0){
		return substr($result, $start, $end-$start);
	}
	return null;
}

//start the harvest
$i = 0;
$file = "harvest_" . $i . '.xml';
$token = harvest($file);
$i++;

//if this is a multi page havest do it
while($i < $MAXPAGES && $token !== null){
	echo $token;
	$file = "harvest_" . $i . '.xml';
	$token = harvest($file, $token);
	$i++;
}

echo "<hr>COMPLETE";
?>