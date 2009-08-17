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

//require_once(t3lib_extMgm::extPath('ldap_macmade') . 'sv1/class.tx_ldapmacmade_sv1.php');
require_once( 'class.my_ldap.php');

/**
 * Plugin '' for the 'user_feuser_hook' extension.
 *
 * @author	Antoine CATHELIN <anc@wcc-coe.org>
 * @package	TYPO3
 * @subpackage	user_feuserhook
 */
class user_feuserhook_pi1 {
	
	function main_hook(&$recordArray, &$pid) {
		
		//t3lib_div::debug('MainHook');
		//t3lib_div::debug($recordArray);
		
		// get parameters of the extesion
		
		$sysconf=unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['user_feuser_hook']);
		
		$OpenLDAP = new myLDAP($sysconf['ldap_table'], $pid, $sysconf['sysfolder']);
		//$OpenLDAP->debug();
		
		$data='';
		$data = $OpenLDAP->search('','mail='.$recordArray['email']);
		//t3lib_div::debug($data);
		
		if ($data['count']==0) { // if the user does'nt exist
			
			$name = split(' ', $recordArray['name']);
			$info['givenName']=$recordArray['first_name'];
			$info['sn'] = $recordArray['last_name'];
			$info['mail'] = $recordArray['email'];
			$info['userpassword'] = $recordArray['password'];
			
			$info['objectClass'] = 'inetOrgPerson';
			$info['groupMembership'] = 'cn=presse,ou=groups,ou=public,o=ecucenter';
			$info['securityEquals'] = 'cn=presse,ou=groups,ou=public,o=ecucenter';
			$dn='cn='.$recordArray['email'].',ou=users,ou=Public,o=ecucenter';
			$OpenLDAP->add($dn, $info);
			//t3lib_div::debug($info);
			
			$info2['member'] = 'cn='.$recordArray['email'].',ou=users,ou=public,o=ecucenter';
			$dn='cn=presse,ou=groups,ou=public,o=ecucenter';
			$OpenLDAP->modify($dn, $info2);
			//t3lib_div::debug($info2);
			
		} else {
			//t3lib_div::debug('else');
			//$this->appenews($OpenLDAP, $recordArray[uid], $data);
		}
		
		// delete the password, because it was store only in ldap server
		$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, 'UPDATE fe_users SET password="" WHERE uid='.$recordArray['uid']);

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