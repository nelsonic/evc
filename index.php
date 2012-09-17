<?php
	require('./codes.php'); // contains the DB credentials and connection
	require_once('./Force.com-Toolkit-for-PHP/soapclient/SforceEnterpriseClient.php');

	$mySforceConnection = new SforceEnterpriseClient();
	$wsdl = './test.enterprise.wsdl.xml';
	//$wsdl = './enterprise.wsdl.xml';
	$mySforceConnection->createConnection($wsdl);
	$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
	
	$C = new Codes();
	$codes = $C->generateMany($number_of_codes);
	$prep  = $C->prepareCodesForInsert($codes);
//	echo 'Prefix : ' .$C->prefix .'<br />';
//	echo '<pre>';
//	print_r($codes);
//	echo '</pre>';
	/************************** INSERT DATA INTO SF **************************/
	$records = array();
	$i = 0;
	
	foreach ($prep as $code) {
		$records[$i] = new stdclass();
		$records[$i]->Contract_Deal_Attribute__c = $cda;
		$records[$i]->Voucher_Code__c = $code;
		$i++;
	}
	$response = $mySforceConnection->create($records, 'External_Voucher_Code__c');
	
	print_r(json_encode($response));

?>