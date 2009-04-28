<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Antoine CATHELIN <anc@wcc-coe.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

//require_once(t3lib_extMgm::extPath($extkey,'sv1/class.tx_ldapmacmade_sv1.php'));
//require_once(t3lib_extMgm::extPath('ldap_macmade') . 'class.tx_ldapmacmade_div.php');
require_once(t3lib_extMgm::extPath('ldap_macmade') . 'sv1/class.tx_ldapmacmade_sv1.php');
require_once( 'class.my_ldap.php');

/**
 * Plugin '' for the 'user_feuser_hook' extension.
 *
 * @author	Antoine CATHELIN <anc@wcc-coe.org>
 * @package	TYPO3
 * @subpackage	user_feuserhook
 */
class user_feuserhook_pi1 {
	
	function appenews(&$OpenLDAP, $uid, $findData) {
		global $TYPO3_DB;
		
		$res = $TYPO3_DB->exec_SELECTquery('*', 'sys_dmail_feuser_category_mm', 'uid_local='.$uid, '', ''); //collumns, table, where, ?, sorting
		while ($row = $TYPO3_DB->sql_fetch_assoc($res)) {
			$info['enews'].=$row[uid_foreign].',';
		}
		if ($findData['count']>0) $OpenLDAP->modify($findData[0]['dn'],$info);
	}
	
	function main_hook(&$recordArray, &$pid) {
		
		//t3lib_div::debug('MainHook');
		
		// get parameters of the extesion
		
		$sysconf=unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['user_feuser_hook']);
		
		$OpenLDAP = new myLDAP($sysconf['ldap_table'], $pid, $sysconf['sysfolder']);
		//$OpenLDAP->setServer('172.18.1.91', '389', '3', 'cn=admin,dc=ecucenter,dc=org', 'helpdesk', 'dc=ecucenter,dc=org', '(objectClass=Person)');
		$OpenLDAP->debug();
		
		$data='';
		$data = $OpenLDAP->search('','email='.$recordArray['email']);
		t3lib_div::debug($recordArray['email']);
		
		
		if ($data['count']==0) { // if the user does'nt exist
			t3lib_div::debug('if');
			$name = split(' ', $recordArray['name']);
			$info['cn'] = $recordArray['name'];
			$info['sn'] = $name[0];
			$info['email'] = $recordArray['email'];
			//$info['userPassword'] = $recordArray['password'];
			
			$info['objectclass'] = $recordArray['person'];
			$dn='cn='.$info['cn'].',o=Public,dc=ecucenter,dc=org';
			$OpenLDAP->add($dn, $info);
		} else {
			t3lib_div::debug('else');
			$this->appenews($OpenLDAP, $recordArray[uid], $data);
		}
		
		
	}
	
	function registrationProcess_afterSaveEdit($recordArray, &$invokingObj) {
		// invoke the main function 
		$this->main_hook($recordArray, $invokingObj->pibase->cObj->data[pid]);
	}

	function registrationProcess_afterSaveCreate($recordArray, &$invokingObj) {
		// invoke the main function 
		$this->main_hook($recordArray, $invokingObj->pibase->cObj->data[pid]);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/user_feuser_hook/pi1/class.user_feuserhook_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/user_feuser_hook/pi1/class.user_feuserhook_pi1.php']);
}

?>