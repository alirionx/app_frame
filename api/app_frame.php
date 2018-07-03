<?php

//---MySQL Connect-----------------------------------------------
	
/*
	$conf_file = file_get_contents("../config/database.json");
	$conf_obj  = json_decode($conf_file, true);
		
	$my_con = mysqli_connect(
		$conf_obj["host"] , 
		$conf_obj["user"] , 
		$conf_obj["password"] , 
		$conf_obj["schema"]
	);
*/

//--MySQL Connection Test---------------------------

	function my_con_test(){
		
		$my_con = $GLOBALS['my_con'];
		
		$sql_qry = mysqli_query( $my_con, " SHOW TABLES " )or die("Error in Script Line ". __LINE__ .": ".mysqli_error($my_con) ) ;
		while( $sql_row = mysqli_fetch_array($sql_qry) ){
			
			echo $sql_row[0] . '<br>';
			//print_r($sql_row);
		}
	}

//-----------------------------------------------------------------------------



//--------Establish LDAP Connection--------------------------------------------

/*
	$conf_file = file_get_contents("../config/ldap.json");
	$conf_obj  = json_decode($conf_file, true);
			
	$ldap_host 		= $conf_obj["host"];
	$ldap_port 		= $conf_obj["port"];
	$ldap_user 		= $conf_obj["user"];
	$ldap_password 	= $conf_obj["password"];
	$ldap_base_dn 	= $conf_obj["base_dn"];
*/
	
//-------------------------
	
	function ldap_con_do(){
		
		$ldap_host 		= $GLOBALS['ldap_host'];
		$ldap_port 		= $GLOBALS['ldap_port'];
		$ldap_user 		= $GLOBALS['ldap_user'];
		$ldap_password 	= $GLOBALS['ldap_password'];
		$ldap_base_dn 	= $GLOBALS['ldap_base_dn'];
				
		$ldap_con = ldap_connect( $ldap_host , $ldap_port );

		ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3) or die("Error in Script Line ". __LINE__ .": ".ldap_error($ldap_con) ) ;
		ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0); 

		if (TRUE === ldap_bind($ldap_con, $ldap_user, $ldap_password)){
			return $ldap_con;
		}
		else{
			return FALSE;
		}
	}
	
//--LDAP Connection Test---------------------------

	function ldap_con_test(){
		
		$ldap_base_dn 	= $GLOBALS['ldap_base_dn'];
		
		$ldap_con = ldap_con_do();
		
		$search_filter = '(&(objectClass=inetOrgPerson)(uid=*))';
		$justthese = array( "uid", "cn", "sn", "givenname", "mail");
		
		$result  = ldap_search($ldap_con, $ldap_base_dn, $search_filter , $justthese);				
		$entries = ldap_get_entries($ldap_con, $result);
		
		echo '<pre>';
			print_r($entries);
		echo '</pre>';
	}
	
//-----------------------------------------------------------------------------




//--------Function Caller------------------------------------------------------
		
	if( isset( $_POST["function"] ) ) {
		
		$_POST["function"]( $_POST );	
	}	
	
	if( isset( $_GET["function"] ) ) {
		
		$_GET["function"]( $_GET );	
	}	
	
//-----------------------------------------------------------------------------

















?>