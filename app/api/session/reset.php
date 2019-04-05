<?php
global $SITE_INFO;

//extract variable
extract($_POST);

switch ($module) {
	case 'customer':
		$user = DF\DB::prepared_query(
			"SELECT * FROM customers WHERE phone = :phone_email OR email = :phone_email LIMIT 1", 
			[":phone_email"=>$phone_email], 
			true
		)[0];
	break;
	case 'merchant':
		$user = DF\DB::prepared_query(
			"SELECT * FROM shops WHERE id = :username_email OR email = :username_email LIMIT 1", 
			[":username_email"=>$username_email], 
			true
		)[0];

		$msg_extra = "Username: $user[id]";
	break;
	case 'system':
	case 'delivery':
		$user = DF\DB::prepared_query(
			"SELECT * FROM users WHERE (username = :username_email OR email = :username_email) AND module = :module LIMIT 1", 
			[":username_email"=>$username_email, ":module"=>$module], 
			true
		)[0];

		$msg_extra = "Username: $user[username]";
		
	break;
}
//Check database for account

if(!$user)
	DF\Response::printFailure("No account matches the input specified", 1);

//Send request
//request_pwd_reset($user);

DF\Response::printSuccess("A password reset link has been sent to your email");


//Generate token
$token = md5( base64_encode( mcrypt_create_iv(12) ) . $user["email"] );

//Save request info
DF\DB::update("pwd_reset",[
	"email" => $user["email"],
	"module" => $module,
	"token" => $token
]);

//Send Email
$msg = <<<MSG
A password request was made for your account. Use the link below to complete the password reset:

$msg_extra

$SITE_INFO[base]/$module/reset/$token
MSG;
Notification\Email::send($user["email"], "Password Reset | Myshop Ghana", Parsedown::instance()->text($msg) );
