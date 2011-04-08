<?php
/***********************************************
DAVE PHP API
https://github.com/evantahler/PHP-DAVE-API
Evan Tahler | 2011

I ensure that the various output types of the daveAPI work
***********************************************/
require_once("../spec_helper.php");
	$T = new DaveTest("Output Types");
	$T->context("The API should return various OutputTypes");

	$T->context("PHP",2);
		$PostArray = array("OutputType" => "PHP", "LimitLockPass" => $CONFIG['CorrectLimitLockPass']);
		$APIRequest = new APIRequest($TestURL, $PostArray);
		$APIDATA = $APIRequest->DoRequest();
		$T->assert(">",count($APIDATA),0);
		$T->assert("==",$APIDATA["ERROR"],"That Action cannot be found.  Did you send the 'Action' parameter?");

	$T->context("JSON",2);
		$PostArray = array("OutputType" => "JSON", "LimitLockPass" => $CONFIG['CorrectLimitLockPass']);
		$APIRequest = new APIRequest($TestURL, $PostArray);
		$APIRequest->DoRequest();
		$JSON_resp = json_decode($APIRequest->ShowRawResponse(), true);
		$T->assert(">",count($JSON_resp),0);
		$T->assert("==",$JSON_resp["ERROR"],"That Action cannot be found.  Did you send the 'Action' parameter?");

	$T->context("XML",2);
		$PostArray = array("OutputType" => "XML", "LimitLockPass" => $CONFIG['CorrectLimitLockPass']);
		$APIRequest = new APIRequest($TestURL, $PostArray);
		$APIRequest->DoRequest();
		$XML_resp = simplexml_load_string($APIRequest->ShowRawResponse());
		$T->assert(">",count($XML_resp),0);
		$T->assert("==",$XML_resp->ERROR,"That Action cannot be found.  Did you send the 'Action' parameter?");

$T->end();

?>