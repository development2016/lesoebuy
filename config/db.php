<?php
//$cert = 'C:\xampp\htdocs\procument\compose.crt';

$cert = '/var/www/vhosts/lesoebuy.com/httpdocs/compose.crt';

$ctx = stream_context_create(array(
    "ssl" => array(
        "cafile"            => $cert,
        "allow_self_signed" => false,
        "verify_peer"       => true, 
        "verify_peer_name"  => true,
        "verify_expiry"     => true, 
    ),
));

return [

      
	'class' => '\yii\mongodb\Connection',
    'dsn' => 'mongodb://procuments:Amtujpino.2017@aws-ap-southeast-1-portal.2.dblayer.com:15429/procument',
	'options' => [
		'ssl' => true
	],
	'driverOptions' => [
		'context' => $ctx
	]

];



