<?php

$from ='connorli@purple.enkel.hosting';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
// Create email headers
$headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();

$data = json_decode( gzdecode( file_get_contents('php://input') ) );

ob_start();

print_r( $data );

$body = ob_get_clean();

if( empty( $data ) ) {
	exit();
}

mail( 'connor@enkel.hosting', 'MC Stats Test', $body, $headers );