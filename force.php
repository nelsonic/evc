<?php
/**
 * This file is a simple testing ground for checking data is bing inserted into SF. :-)
 * Uses the PHP SOAP API for Salesforce.
 * see: http://wiki.developerforce.com/page/Getting_Started_with_the_Force.com_Toolkit_for_PHP
 * @author: nelson.correia@groupon.com
 */
require_once('./config.php'); // Contains USERNAME, PASSWORD & SECURITY_TOKEN
require_once('./Force.com-Toolkit-for-PHP/soapclient/SforceEnterpriseClient.php');

$mySforceConnection = new SforceEnterpriseClient();
$wsdl = './test.enterprise.wsdl.xml';
//$wsdl = './enterprise.wsdl.xml';
$mySforceConnection->createConnection($wsdl);
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);


/************************** SELECT CDA_id **************************/
echo "<pre>";
$query = "SELECT Id FROM ContractDealAttribute__c LIMIT 1";
$response = $mySforceConnection->query($query);

//echo "Results of query '$query'<br/><br/>\n";
foreach ($response->records as $record) {
//    echo $record->Id ." - "  ."<br/>\n";
    $cda = $record->Id;
}

/************************** GENERATE SOME CODES **************************/

require_once('./codes.php');
$C = new Codes();
// $C->format = $_GET['format'];
$C->prefix = $C->getPrefix($C->format);
$C->__construct();
$C->num_random_chars = strlen($C->format) - strlen($C->prefix);

$codes = $C->generateMany($number_of_codes);
//print_r($codes);


/************************** INSERT DATA INTO SF **************************/
$records = array();

$records[0] = new stdclass();
$records[0]->Contract_Deal_Attribute__c = $cda;
$records[0]->Voucher_Code__c = implode(",", $codes);;

$response = $mySforceConnection->create($records, 'External_Voucher_Code__c');

//print_r($response);

//$ids = array();
//foreach ($response as $i => $result) {
//    echo $records[$i]->Contract_Deal_Attribute__c . " " . $records[$i]->Voucher_Code__c . " "
//            . "<br/>\n";
//    array_push($ids, $result->id);
//}

/************************** Confirm External_Voucher_Code__c inserted : **************************/

$query = "SELECT Id, Contract_Deal_Attribute__c, Voucher_Code__c from External_Voucher_Code__c where Contract_Deal_Attribute__c='" .$cda ."' ORDER BY CreatedDate DESC";
$response = $mySforceConnection->query($query);

//echo "Results of query '$query'<br/><br/>\n";
foreach ($response->records as $record) {
    echo "Id: " .$record->Id ."<br />";
    if(isset($record->Contract_Deal_Attribute__c)) { 
    	echo "CDA: " .$record->Contract_Deal_Attribute__c ." <br />";
    }	
    echo "Codes: " .$record->Voucher_Code__c ."<br/>\n";
}

// To DELETE the data you just inserted run:
// DELETE FROM External_Voucher_Code__c WHERE Contract_Deal_Attribute__c='a1G20000000PigOEAS'
/*
$query = "SELECT Id FROM External_Voucher_Code__c WHERE Contract_Deal_Attribute__c='a1G20000000PigOEAS'";
$response = $mySforceConnection->query($query);
//print_r($response->records);
$ids = array();
$i=0;
foreach ($response->records as $record) { 
// 	if(isset($record->id)) {
// 		echo 'record : ' .$record->Id .'<br/>';	
		array_push($ids, $record->Id); 
//	}
}
echo 'Query : ' .$query .'<br />';
//print_r($ids);

// $ids is an array of record ids built in a previous step
$response = $mySforceConnection->delete($ids);
foreach ($response as $result) {
    echo $result->id . " deleted<br/>\n";
}
*/

?>