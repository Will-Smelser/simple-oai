<?php
header("Content-type: text/xml; charset=utf-8");

define("LOGGING",false);

require_once 'Utility.php';

//handle the verb
$verb = Utility::myGet('verb');
if(isset($_GET['resumptionToken']))
	$verb = 'resumptiontoken';

$set = Utility::myGet('set');


Utility::log('oai.log', "GET-" . Utility::toStr($_GET));
Utility::log('oai.log', "POST-" . Utility::toStr($_POST));

	
/**
 * Handle the verbs
 */
switch($verb){
	case 'resumptiontoken':
		$parts = explode(':', Utility::myGet('resumptionToken'));
		$set = $parts[0];
		
		Utility::log('oai.log', "set = " . $set);
		Utility::log('oai.log', "token parts = " . Utility::toStr($parts));
		
		
	case 'ListRecords':
		require_once 'ListRecords.php';
		
		$verb = new ListRecords($set);
		echo $verb->doVerb(Utility::myGet('resumptionToken'));
		break;
		
	case 'Identify':
		require_once 'ListRecords.php';
		
		$verb = new ListRecords('defaults/identify');
		echo $verb->doVerb();
		break;
		
	case 'ListMetadataFormats':
		require_once 'ListRecords.php';
		
		$verb = new ListRecords('defaults/listmetadataformats');
		echo $verb->doVerb();
		break;
		
	case 'ListSets':
		require_once 'ListSets.php';
		
		$verb = new ListSets();
		echo $verb->doVerb();
		break;
		
	default:
		require_once 'Verb.php';
		
		$verb = new VerbBase();
		$url = $verb->getRequest();
		echo $verb->sprintf('sets/defaults/badrequest/badrequest.xml', date(DATE_ATOM), $url);
		break;
		
}

?>