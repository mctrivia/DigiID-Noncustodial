var main=function(config,document,window,undefined) {
	const SECRET_LENGTH=20;				//length in nibles(2 to 40)
	const INTERVAL_DIGIID=5000;			//how often should check if id has been signed(ms)

	//Handle requests
	xmr.setMax(5);															//set max number of transactions at a time

	//login digiid
	var loginSecret='';
	for (var i=0;i<SECRET_LENGTH;i++) {
		loginSecret+=(Math.floor(Math.random()*16)).toString(16);						//create a 20 byte decryption key
	}
	var digiQRmessage=config['digiid']+"&secret="+loginSecret;
	if (digiQRmessage.legnth>127) console.error("Need to shorten uri must be less then 127 bytes currently: " +digiQRmessage.legnth);
	var domLoginQR=document.getElementById("login_digiid");
	domLoginQR.src=DigiQR.id(digiQRmessage,300,6,0.5);//(digiid_uri,width,logo style(0-7),radius(0.0-1.0))
	domLoginQR.style.cursor='pointer';
	domLoginQR.addEventListener('click',function() {
		window.open(digiQRmessage, '_blank');
	});
	var digiIDcheck=setInterval(function() {
		xmr.getJSON('digiid/ajax.php?nonce='+config['nonce']).then(function(reqData){
            if(reqData!=false) {
				var phrase='';
				for (var i=0;i<40;i++) {
					phrase+=(parseInt(reqData['wallet'][i],16) ^ parseInt(loginSecret[i%SECRET_LENGTH],16)).toString(16);
				}				
				login(phrase);
            }
        });
    }, INTERVAL_DIGIID);
	

	var login=function(phrase) {
		clearInterval(digiIDcheck);
		
		document.getElementById('code').innerHTML=phrase;
		document.getElementById('user').style.display='block';
		document.getElementById('login').style.display='none';
	}
}