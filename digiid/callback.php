<?php
/*
Copyright 2014 Daniel Esteban

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
require_once(dirname(__DIR__).'/includes/autoload.php');
use DigiByte\DigiID;
use MCTrivia\Database;

$digiid = new DigiID();
$dao = new Database();

$variables = $_POST;
$post_data = json_decode(file_get_contents('php://input'), true);
// SIGNED VIA PHONE WALLET (data is send as payload)
if($post_data!==null) {
    $variables = $post_data;
}

//check if valid signature
if (!$digiid->isMessageSignatureValidSafe(@$variables['address'], @$variables['signature'], @$variables['uri'])) {
	throw new \Error("Addresses Don't Match");
}

//check if nounce is in database
$nonce = $digiid->extractNonce($variables['uri']);
$secret= $digiid->extractSecret($variables['uri']);
while (strlen($secret)<40) $secret.=$secret;

//hash address
$hash=hash("sha256",$variables['address']);
	
//decode address to 20byte hexdec
$address=bin2hex(substr($digiid->base58check_decode($variables['address']),1));
$wallet='';
for ($i=0;$i<40;$i++) {
	$wallet.=dechex(hexdec($secret[$i])^hexdec($address[$i]));
}
	
//update database
$query='INSERT INTO `users` (`hash`, `payout`, `nounce`, `wallet`) VALUES (unhex(?),"",unhex(?),unhex(?)) ON DUPLICATE KEY UPDATE `nounce`=unhex(?),`wallet`=unhex(?)';
$stmt=$dao->prepare($query);
$stmt->bind_param("sssss",$hash,$nonce,$wallet,$nonce,$wallet);
$stmt->execute();

//return data to phone
$data = [ 'address' => $variables['address'], 'nonce' => $nonce ];
header('Content-Type: application/json');
echo json_encode($data);