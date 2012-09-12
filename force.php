<?php
define("USERNAME", "nelson.correia@groupon.co.uk");
define("PASSWORD", "password");
define("SECURITY_TOKEN", "sdfhkjwrhgfwrgergp");

require_once ('./Force.com-Toolkit-for-PHP/soapclient/SforceEnterpriseClient.php');

$mySforceConnection = new SforceEnterpriseClient();
$mySforceConnection->createConnection("enterprise.wsdl.xml");
$mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
?>