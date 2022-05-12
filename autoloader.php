<?php
/**
 * we'll worry about only our classes for now
 */
spl_autoload_register(function ($class_name) 
{
    if(strlen($class_name) < 6 || !in_array(substr( $class_name, 0, 6 ),['Classe', 'Models'])) {
        return;
    }
    
    return require_once('./' . str_replace('\\', '/', $class_name) . '.php');
});