<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Antoine CATHELIN <anc@wcc-coe.org>
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

/**
 * Plugin '' for the 'user_feuser_hook' extension.
 *
 * @author	Antoine CATHELIN <anc@wcc-coe.org>
 * @package	TYPO3
 * @subpackage	user_feuserhook
 * 
 *  function add($dn, $entry)
 *  	ajout d'une entrée dans OpenLDAP 
 * 	$dn, Le nom DN de l'entrée LDAP. 
 *		$entry, est un tableau avec les informations sur la nouvelle entrée. Ces valeurs sont indexées individuellement. En cas de valeurs multiples pour un attribut, elle sont indexées numériquement, à partir de 0.
 *  function modify($dn, $entry)
 * 	Modifie une entrée dans OpenLDAP
 * 	 
 *  search($dn, $filter)
 *  	Recherche une ou plusieurs entrée OpenLDAP
 * 	
 *  function bind()
 * 	Permet de se connecter à un serveur OpenLDAP 
 * 	
 *  function setServer ($address, $port, $version, $user, $password, $basedn, $filter)
 *  	Permet de définir manuellement les paramètre du serveur OpenLDAP
 * 	
 *  function setServerFromDB($tableName, $pid, $sysfolderInfo)
 * 	  Définir les paramètre du serveur OpenLDAP en allant les chercher dans la table typo3 $tableName
 * 	
 *  function myLDAP($tableName, $pid, $sysfolderInfo)
 * 
 */
class myLDAP {

	private $adress='192.168.1.1';
	private $port=389;
	private $version=0;
	private $user='';
	private $password='';
	private $basedn='';
	private $filter='';
	private $ldapServer='';
	private $connexion='';
	private $error='';
	
	
	function debug() {
		//t3lib_div::debug('adress : '.$this->adress);
		//t3lib_div::debug('port : '.$this->port);
		//t3lib_div::debug('version : '.$this->version);
		//t3lib_div::debug('user : '.$this->user);
		//t3lib_div::debug('Password : '.str_repeat('*',strlen($this->password)));
		//t3lib_div::debug('basedn : '.$this->basedn);
		//t3lib_div::debug('filter : '.$this->filter);
		//if ($this->ldapServer)  {t3lib_div::debug('ldap server found ['.$this->adress.']'); } else {  t3lib_div::debug('ldap server not found');};
		//t3lib_div::debug('::end debug function:: ');
	}
	
	function add($dn, $entry) {
		/*
		 * $dn, Le nom DN de l'entrée LDAP. 
		 * $entry, est un tableau avec les informations sur la nouvelle entrée. Ces valeurs sont indexées individuellement. En cas de valeurs multiples pour un attribut, elle sont indexées numériquement, à partir de 0. 
		 */
		$ok=true;
		
		$res = @ldap_add($this->ldapServer, $dn, $entry);
		
		if (strtolower(@ldap_error($this->ldapServer)) <> 'success') {
			$this->error=@ldap_error($this->ldapServer);
			$ok=false;
		}
		return $ok;
	}
	
	function modify($dn, $entry) {
		/*
		 * $dn, Le nom DN de l'entrée LDAP. 
		 * $entry, est un tableau avec les informations sur la nouvelle entrée. Ces valeurs sont indexées individuellement. En cas de valeurs multiples pour un attribut, elle sont indexées numériquement, à partir de 0. 
		 */
		$ok=true;
		
		$res = @ldap_modify($this->ldapServer, $dn, $entry);
		
		if (strtolower(@ldap_error($this->ldapServer)) <> 'success') {
			$this->error=@ldap_error($this->ldapServer);
			$ok=false;
		}
		return $ok;
	}
	
	function  search($dn, $filter) {
		/*
		 *  $dn, Le nom DN de l'entrée LDAP. 
		 *  $filter, le filtre ex: "email=anc@ecucenter.org" ou "(|(sn=$person*)(cn=$person*))"
		 */
		$search_info='';
		
		if (!$dn) $dn =$this->basedn;
		//$filter='email=anc@ecucenter.org';
		//$filtre="(|(sn=$person*)(cn=$person*))";
		//$restriction = array( "cn", "sn");
		//t3lib_div::debug('dn : '.$dn);
		//t3lib_div::debug('filter : '.$filter);
		
		$sr=@ldap_search($this->ldapServer, $dn, $filter);
		$search_info =  @ldap_get_entries($this->ldapServer, $sr);
		if (strtolower(@ldap_error($this->ldapServer)) <> 'success') $search_info['count']=0;
		return $search_info;
	}
	
	
	function bind() {
		/*
		 *  Crée une connexion au serveur OpenLDAP avec les paramètres de la class
		 */
		$ok=true;
		
		$this->ldapServer=ldap_connect($this->adress);
		if ($this->version>0) ldap_set_option($this->ldapServer, LDAP_OPT_PROTOCOL_VERSION, $this->version);
		if ($this->ldapServer) $this->connexion=@ldap_bind($this->ldapServer, $this->user, $this->password);
		
		if (strtolower(@ldap_error($this->ldapServer)) <> 'success') {
			$this->error=@ldap_error($this->ldapServer);
			$ok=false;
		}
		return $ok;
	}
	
	function setServer ($address, $port, $version, $user, $password, $basedn, $filter) {
		/*
		 *  Permet de définir manuellement les paramètre du serveur OpenLDAP
		 */
		$this->adress=$address;
		$this->port=$port;
		$this->version=$version;
		$this->user=$user;
		$this->password=$password;
		$this->basedn=$basedn;
		$this->filter=$filter;
	}
	
	function setServerFromDB($tableName, $pid, $sysfolderInfo) {
		/*
		 *  Définir les paramètre du serveur OpenLDAP en allant les chercher dans la table typo3 $tableName
		 *  $pid, id de la page en cours
		 *  $sysfolderInfo, liste des sysfolder possible
		 */
		global $TYPO3_DB;
		$sysfolders = split(':',$sysfolderInfo);
		// get ldap parameters
		foreach($sysfolders as $values) {
			$tmp = split(',',$values);
			if ($tmp[0]==$pid) {
				$res = $TYPO3_DB->exec_SELECTquery('*', $tableName, 'pid='.$tmp[1],'');
				$ldap_server = $TYPO3_DB->sql_fetch_assoc($res);
				$this->adress=$ldap_server['address'];
				$this->port=$ldap_server['port'];
				$this->version=intval($ldap_server['version']);
				$this->user=$ldap_server['user'];
				$this->password=$ldap_server['password'];
				$this->basedn=$ldap_server['basedn'];
				$this->filter=$ldap_server['filter'];
				break;
			};
		}
	}
	
	function myLDAP($tableName, $pid, $sysfolderInfo) {
		/*
		 * Constructeur, Initialise les paramètres serveur et créer un connexion
		 */
		$this->setServerFromDB($tableName, $pid, $sysfolderInfo);
		$this->bind();
	}
	
	function __destruct() {
		/*
		 *  Destructeur.
		 */
		if ($this->ldapServer) ldap_close($this->ldapServer);
	}
}
?>