<?php

// it's possible that tested code 
// will use __autoload() instead of
// the spl_autoload functions. so
// put __autoload at the top of the
// spl_autoload stack. great suggestion from pro php.
if (false === spl_autoload_functions()) {
    if (function_exists('__autoload')) {
        spl_autoload_register('__autoload', false);
    }
}

// and add the default bits for JrMnt 
// lower in the stack
spl_autoload_register(function($className) {
    require_once $className . ".php";
});
