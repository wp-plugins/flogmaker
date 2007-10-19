<?php
/*
Plugin Name: Flog maker
Plugin URI: http://www.lutincapuche.com
Description: A full flash template for wordpress
Author: Celine Mornet
Version: 1.0.1
Author URI: http://www.lutincapuche.com
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

define('FLOG_MAKER_VERSION', '0.1.2');
define ('FLOG_MAKER_LANG', 'fr_FR');

include_once "admin/admin.php";
require_once "class.php";

// i18n support testing
load_textdomain('flog-maker', ABSPATH . 'wp-content/plugins/flog-maker/languages/flog-maker-'.FLOG_MAKER_LANG.'.mo');

/**************************************************************************/

add_action('plugins_loaded', 'widget_flog_maker_init');

function widget_flog_maker_init()
{

}

?>