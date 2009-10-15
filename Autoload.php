<?php

//todo: fill this in thoroughly
spl_autoload_extensions('.php');
spl_autoload_register(function($className) {
    require_once $className . ".php";
});
