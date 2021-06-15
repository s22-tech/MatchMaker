<?php

// Make changes below to match your setup.

//////////////////////////
/// If you want to place a secure config file above your root directory.
/// You can delete this section if you wish.
//////////////////////////
$user = get_current_user();
$basepath = '/home/'.$user.'/projects';  // Change this path to match your server.
$ini = parse_ini_file($basepath.'/conf/config.ini', true);

$settings = include 'db.inc.php';

$config = array_merge($ini, $settings);  // The $config array is used in other scripts in this app.

date_default_timezone_set($config['tz']);



//////////////////////////
/// Paths / File Names
//////////////////////////

$logo_filename = 'logo.png';  // The name of your logo image.

$config['app_name']  = 'MatchMaker';
$config['site_name'] = 'by s22 Tech';

// If your install is in a sub-directory, enter it here with a preceeding slash:
$config['rewrite_base'] = '';  // Leave blank ('') if there's no sub-directory.

$config['public_message_interval'] = 30;  // How many days to show public messages on messages.php

// Does your site use www?  'yes' or 'no'
$config['use_www'] = 'yes';


//////////////////////////
/// Email
//////////////////////////
$config['email'] = 'your@email.com';


//////////////////////////
/// No Changes To Be Made Below.
//////////////////////////

$config['rewrite_base'] = rtrim($config['rewrite_base'], '/');

$www = $config['use_www'] === 'yes' ? 'www.' : '';

// Used in the <head> section and allows relative paths when installed in a sub-folder.
$config['base_url'] = 'https://'. $www . $config['tld'] . $config['rewrite_base'];

$config['logo'] = $config['rewrite_base'] .'/images/'. $logo_filename;

$config['image_dir'] = $basepath .'/images'. $config['rewrite_base'];
