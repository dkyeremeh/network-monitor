<?php

function route( $ROUTES, $data ){
	if( method_exists(User,$ROUTES[0]) ){
		$fn = $ROUTES[0];
		array_splice( $ROUTES ,0, 1);
		User::{$fn}($data) ;
	}

	else
		DF\Response::printFailure("No candidate exists for this request");
	
}
return route;