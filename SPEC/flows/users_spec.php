<?php
/***********************************************
DAVE PHP API
https://github.com/evantahler/PHP-DAVE-API
Evan Tahler | 2011

I check a combination of API actions
***********************************************/
require_once("../spec_helper.php");
$T = new DaveTest("Cache Test");

$T->context("I should be able to create a user");
	$PostArray = array(
		"OutputType" => "PHP",
		"Action" => "UserAdd",
		"LimitLockPass" => $CONFIG['CorrectLimitLockPass'],
		"Password" => "password",
		"FirstName" => "DEMO",
		"LastName" => "TESTMAN",
		"ScreenName" => "DemoTestMan",
		"EMail" => "fake@fake.com"
	);

	$APIRequest = new APIRequest($TestURL, $PostArray);
	$APIDATA = $APIRequest->DoRequest();
	$T->assert("==",$APIDATA["ERROR"],"OK");
	$T->assert(">",$APIDATA["UserID"],0);
	
$UserID = $APIDATA["UserID"];

$T->context("I should be able to View a user publicly");
	$PostArray = array(
		"OutputType" => "PHP",
		"Action" => "UserView",
		"LimitLockPass" => $CONFIG['CorrectLimitLockPass'],
		"ScreenName" => "DemoTestMan",
	);

	$APIRequest = new APIRequest($TestURL, $PostArray);
	$APIDATA = $APIRequest->DoRequest();
	$T->assert("==",$APIDATA["ERROR"],"OK");
	$T->assert("==",$APIDATA["User"]["InformationType"],"Public");
	$T->assert("==",$APIDATA["User"]["ScreenName"],"DemoTestMan");
	$T->assert("==",count($APIDATA["User"]),3);
	
$T->context("I should be able to View a user privately");
	$PostArray = array(
		"OutputType" => "PHP",
		"Action" => "UserView",
		"LimitLockPass" => $CONFIG['CorrectLimitLockPass'],
		"UserID" => $UserID,
		"Password" => "password",
	);

	$APIRequest = new APIRequest($TestURL, $PostArray);
	$APIDATA = $APIRequest->DoRequest();
	$T->assert("==",$APIDATA["ERROR"],"OK");
	$T->assert("==",$APIDATA["User"]["InformationType"],"Private");
	$T->assert("==",$APIDATA["User"]["ScreenName"],"DemoTestMan");
	$T->assert("==",$APIDATA["User"]["FirstName"],"DEMO");
	$T->assert("==",$APIDATA["User"]["LastName"],"TESTMAN");
	$T->assert("==",$APIDATA["User"]["EMail"],"fake@fake.com");
	$T->assert("==",count($APIDATA["User"]),12);
	
$T->context("I should be able to Log In");
	$PostArray = array(
		"OutputType" => "PHP",
		"Action" => "LogIn",
		"LimitLockPass" => $CONFIG['CorrectLimitLockPass'],
		"UserID" => $UserID,
		"Password" => "password",
	);

	$APIRequest = new APIRequest($TestURL, $PostArray);
	$APIDATA = $APIRequest->DoRequest();
	$T->assert("==",$APIDATA["ERROR"],"OK");
	$T->assert("==",$APIDATA["LOGIN"],"TRUE");
	$T->assert(">",strlen($APIDATA["SessionKey"]),0);
	$T->assert(">",$APIDATA["SESSION"]["login_time"],0);
	
$T->context("I should be able to Edit a user");
	//It's importnat to use UserID here (META KEY) so you can change other values
	$PostArray = array(
		"OutputType" => "PHP",
		"Action" => "UserEdit",
		"LimitLockPass" => $CONFIG['CorrectLimitLockPass'],
		"UserID" => $UserID,
		"Password" => "password",
		"EMail" => "NewEmail@fake.com"
	);

	$APIRequest = new APIRequest($TestURL, $PostArray);
	$APIDATA = $APIRequest->DoRequest();
	$T->assert("==",$APIDATA["ERROR"],"OK");
	$T->assert("==",$APIDATA["User"]["EMail"],"NewEmail@fake.com");

$T->context("I should be able to Delete a user");
	$PostArray = array(
		"OutputType" => "PHP",
		"Action" => "UserDelete",
		"LimitLockPass" => $CONFIG['CorrectLimitLockPass'],
		"UserID" => $UserID,
		"Password" => "password"
	);

	$APIRequest = new APIRequest($TestURL, $PostArray);
	$APIDATA = $APIRequest->DoRequest();
	$T->assert("==",$APIDATA["ERROR"],"OK");
	
$T->context("Deleted users should not be found");
	$PostArray = array(
		"OutputType" => "PHP",
		"Action" => "UserView",
		"LimitLockPass" => $CONFIG['CorrectLimitLockPass'],
		"ScreenName" => "DemoTestMan",
	);

	$APIRequest = new APIRequest($TestURL, $PostArray);
	$APIDATA = $APIRequest->DoRequest();
	$T->assert("==",$APIDATA["ERROR"],"That User cannot be found");

$T->end();
?>