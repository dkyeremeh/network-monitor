<?php

function apc_add() { return call_user_func_array("apcu_add", func_get_args());}
function apc_exists() { return call_user_func_array("apcu_exists", func_get_args());}
function apc_fetch() { return call_user_func_array("apcu_fetch", func_get_args());}
function apc_clear_cache() { return call_user_func_array("apcu_clear_cache", func_get_args());}
function apc_delete() { return call_user_func_array("apcu_delete", func_get_args());}
function apc_store() { return call_user_func_array("apcu_store", func_get_args());}