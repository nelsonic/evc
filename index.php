<?php
	require(dirname(__FILE__) .'/codes.php'); // contains the DB credentials and connection
	
	$C = new Codes();
	echo 'Prefix : ' .$C->prefix .'<br />';
	$codes = $C->generateMany($number_of_codes);
	echo '<pre>';
	print_r($codes);
	echo '</pre>';
?>