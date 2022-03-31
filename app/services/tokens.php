<?php
function generate_jwt( $payload, $secret = 'secret') {
	
	
	
	$payload_encoded = base64url_encode(json_encode($payload));
	
	$signature = hash_hmac('SHA256', $payload_encoded, $secret, true);
	$signature_encoded = base64url_encode($signature);
	
	$jwt = "$payload_encoded.$signature_encoded";
	
	return $jwt;
}

function base64url_encode($str) {
    return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
}

function is_jwt_valid($jwt, $secret = 'secret') {
	// split the jwt
	$tokenParts = explode('.', $jwt);
	
	$payload = base64_decode($tokenParts[0]);
	$signature_provided = $tokenParts[1];

	// check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
	$expiration = json_decode($payload)->exp;
	$is_token_expired = ($expiration - time()) < 0;

	// build a signature based on the header and payload using the secret
	
	$base64_url_payload = base64url_encode($payload);
	$signature = hash_hmac('SHA256',$base64_url_payload, $secret, true);
	$base64_url_signature = base64url_encode($signature);

	// verify it matches the signature provided in the jwt
	$is_signature_valid = ($base64_url_signature === $signature_provided);
	
	if ($is_token_expired || !$is_signature_valid) {
		return FALSE;
	} else {
		return TRUE;
	}
}