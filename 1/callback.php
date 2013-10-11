<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

error_reporting(1);
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );


if (isset($_REQUEST['code'])) {
	$keys = array();
	$keys['code'] = $_REQUEST['code'];
	$keys['redirect_uri'] = WB_CALLBACK_URL;
	try {
		$token = $o->getAccessToken( 'code', $keys ) ;
	} catch (OAuthException $e) {
	}
}

if ($token) {
	$_SESSION['token'] = $token;
	setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
	//var_dump($_SESSION);
	//var_dump($_COOKIE);
?>

授权完成,<a href="login.php">进入应用</a><br />
<?php
} else {
?>
授权失败。
<?php
}
?>
