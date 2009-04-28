<?php

########################################################################
# Extension Manager/Repository config file for ext: "user_feuser_hook"
#
# Auto generated 06-03-2009 09:38
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Frontend User Hook',
	'description' => 'Hook of Frontend User Registration',
	'category' => 'plugin',
	'author' => 'Antoine CATHELIN',
	'author_email' => 'anc@wcc-coe.org',
	'shy' => '',
	'dependencies' => 'sr_feuser_register',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.2',
	'constraints' => array(
		'depends' => array(
			'sr_feuser_register' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:9:{s:9:"ChangeLog";s:4:"5b5e";s:10:"README.txt";s:4:"ee2d";s:21:"ext_conf_template.txt";s:4:"f6d7";s:12:"ext_icon.gif";s:4:"346e";s:17:"ext_localconf.php";s:4:"c025";s:19:"doc/wizard_form.dat";s:4:"6c9b";s:20:"doc/wizard_form.html";s:4:"6370";s:21:"pi1/class.my_ldap.php";s:4:"c76a";s:33:"pi1/class.user_feuserhook_pi1.php";s:4:"afde";}',
	'suggests' => array(
	),
);

?>