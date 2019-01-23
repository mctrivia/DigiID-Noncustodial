<?php
require_once(__DIR__.'/includes/autoload.php');
//session_start();

use DigiByte\DigiID;


$digiid=new DigiID();
$nonce = $digiid->generateNonce();
$digiid_uri = $digiid->buildURI('https://your_domain/digiid/callback.php', $nonce);

?>	
<html>
<body>
	<div id="login">
		<img id="login_digiid"></img>
	</div>
	

	<div id="user" style="display:none">
		Logged in with psuodo random code: <span id="code"></span><br>
		This only works for sites that can be safely run with a client side code.<br>
		For example a wallet app code use to generate a private key and store funds in it.<br>
		Does not work for a list based access system since hacker could just add themself to list or bypass code entirely.
	</div>
	
	
	
	<script src="js/xmr.min.js"></script>
	<script src="js/digiQR.min.js"></script>
	<script src="js/main.js"></script>
	<script>
	main(<?php echo json_encode(array(
		"digiid"=>	$digiid_uri,
		"nonce"=>	$nonce
		//safe to add more config variables here
	));?>,document,window);
	</script>
</body>
</html>