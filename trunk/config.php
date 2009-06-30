<?php
                                // DATABASE SETUP //
// Armory Database settings
$config['armory_DB_user']='user'; // User name for armory database
$config['armory_DB_passwd']='password'; // Password for armory database
$config['armory_DB_host']='host'; // Host of armory database
$config['armory_DB_port']=3306; // Port of armory database
$config['armory_DB_name']='armory'; // Name of armory database

// World Database settings
$config['world_DB_user']='user'; // User name for main world database
$config['world_DB_passwd']='password'; // Password for main world database
$config['world_DB_host']='host'; // Host of  main world database
$config['world_DB_port']=3306; // Port of main world database
$config['world_DB_name']='world'; // Name of main world database

// Realm Database settings
$config['realm_DB_user']='user'; // User name for realm database
$config['realm_DB_passwd']='password'; // Password for realm database
$config['realm_DB_host']='host'; // Host of  realm database
$config['realm_DB_port']=3306; // Port of realm database
$config['realm_DB_name']='realm'; // Name of realm database


                              // REALMLIST SETUP //

// Realm list - ID,'Host',Port,'user','passwd','DBname',isDeafultRealm(true or false)

$config['realms'][] = array(1, 'host',3306,'user','password','char',true);
$config['realms'][] = array(2, 'host',3306,'user','password','char2',false);


                                          // SCRIPT SETTINGS //
$config['server_name'] = 'Test Server';
$config['language_change'] = false; /* true - language changing mode on, false - use default language !PL LANGUAGE FILE IS NOT COMPLATE! (languages/pl.lng) */
$config['language'] = 'en'; /* default language */
$config['cache_status'] = true; // true - use cache system (recommended) 
$config['cache_expire'] = 60*60*12; // cache update time in seconds
$config['mangos_version'] = 2; // 2 - 3.1.3, 1 - 3.0.9, 0 - 2.4.3(work in progress)

// Engine defines

$_DOMAIN = str_replace('ajax/','','http://'.$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['SCRIPT_NAME']),'',$_SERVER['SCRIPT_NAME'])); 
$config['templates_dir'] = 'templates/';

?>