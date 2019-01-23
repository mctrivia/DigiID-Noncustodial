<?php
/*
Copyright 2019 Matthew Cornelisse
*/
//validate needed inputs exist
if (!isset($_GET['nonce'])) {
	throw \Error("Bad Request");
	die();
}
$nonce=$_GET['nonce'];

//refence used libraries and classes
require_once(dirname(__DIR__).'/includes/autoload.php');
use MCTrivia\Database;

//get log in data if it exists
$dao = new Database();
$query='SELECT hex(`wallet`),`payout` FROM `users` WHERE `nounce`=unhex(?) limit 1';
$stmt=$dao->prepare($query);
$stmt->bind_param("s",$nonce);
$stmt->bind_result($wallet,$payout);
$stmt->execute();
if ($stmt->fetch()) {
	echo json_encode(array(
		"wallet"=>$wallet,
		"payout"=>($payout=='')?'ACCOUNT':$payout
	));
} else {
	echo json_encode(false);
}