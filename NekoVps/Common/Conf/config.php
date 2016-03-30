<?php
$config =  array(
	'DEFAULT_MODULE'=>'Vps',
);
return array_merge($config,require 'database.php',
  require 'vultr.php',require 'chinese.php');
