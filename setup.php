<?php
require_once(__DIR__.'/includes/autoload.php');

use MCTrivia\Database;

try {
	$db = new Database();
	$db->query("CREATE TABLE `users` (
		  `hash` binary(32) NOT NULL,
		  `nonce` binary(16) DEFAULT NULL,
		  `wallet` binary(20) DEFAULT NULL,
		  `lastSeen` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	$db->query("ALTER TABLE `users`
		  ADD PRIMARY KEY (`hash`),
		  ADD UNIQUE KEY `hash` (`hash`);");
	echo "Delete setup.php";
} catch (\Exception $e) {
	echo "Must setup /includes/config/MCTrivia_Database.php";
}