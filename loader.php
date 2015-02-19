<?php
define('EW_PATH', plugin_dir_path(__FILE__));
define('EW_URL', plugins_url('', __FILE__));

//Load Core Classes
require_once(EW_PATH . '/lib/Sync.class.php');
require_once(EW_PATH . '/lib/Settings.class.php');
require_once(EW_PATH . '/lib/Ajax.class.php');
