<?php

Config::set('site_name','NEWS');
Config::set(
    'routes', array (
        'default'   => '',
        'admin'     => 'admin_',
        'user'      => 'user_'
        )
);

Config::set('default_router'    ,'default');
Config::set('default_controller','home');
Config::set('default_action'    ,'index'); 


Config::set('db.host'    ,'localhost');
Config::set('db.user'    ,'root');
Config::set('db.password','ironman98');
Config::set('db.name'    ,'mvc');


Config::set('salt', '');
