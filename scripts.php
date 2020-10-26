<?php 
return [
    'uninstall' => function($app){
        $app['config']->remove('discord');
    }
];
?>