<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.user_feuserhook_pi1.php','_pi1','',1);
$TYPO3_CONF_VARS['EXTCONF']['user_feuser_extension']['user_feuserextension_pi2']['registrationProcess'][] = 'EXT:user_feuser_hook/pi1/class.user_feuserhook_pi1.php:&user_feuserhook_pi1';
$TYPO3_CONF_VARS['EXTCONF']['user_feuser_extension']['user_feuserextension_pi']['confirmRegistrationClass'][] = 'EXT:user_feuser_hook/pi1/class.user_feuserhook_pi1.php:&user_feuserhook_pi1';
?>