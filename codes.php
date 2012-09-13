<?php

/**
 * The main class for generating Groupon Voucher Codes :-)
 *
 * Includes defaults for all $_GET Variables so you can run it simply by 
 * instanciating the class: 
 * $C = new Codes();
 * and calling:
 * $codes = $C->generateMany($number_of_codes);
 *
 * @param:  string $_GET['format']   - e.g. GG******** for Groupon Goods + 8 random chars
 * @param:  string $_GET['cda']      - The ContractDealAttribute__c.Id to attach the codes in SF
 * @param:  string $_GET['numcodes'] - Number of voucher codes to create
 * @return: array $codes 
 *
 * @author: nelson.correia@groupon.com
 */

require_once('./config.php'); // contains the DB credentials and connection

// Defaults:
define('DEFAULT_NUMCODES', 20);
define('CHARACTERS_FOR_VOUCHER_CODES', 'A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,T,U,V,W,X,Y,Z,2,3,4,5,6,7,8,9,2,3,4,5,6,7,8,9');
define('NUM_CHARS', (strlen(CHARACTERS_FOR_VOUCHER_CODES)+1)/2);

// get variables
$format = (isset($_GET['format']) ? mysql_real_escape_string($_GET['format']) : '********');
define('FORMAT', $format);
$number_of_codes = (isset($_GET['numcodes']) ? mysql_real_escape_string($_GET['numcodes']) : DEFAULT_NUMCODES);
$cda = (isset($_GET['cda']) ? mysql_real_escape_string($_GET['cda']) : 'a1G20000000PigOEAS');
define('CDA', $cda);

class Codes
{	
	public $characters;
	public $num_random_chars;
	public $prefix;

	function __construct() { // sets the above vars
  		$this->characters = explode(",", CHARACTERS_FOR_VOUCHER_CODES);	
  		$this->num_random_chars = strlen(FORMAT) - strlen($this->prefix);
  		$this->prefix = $this->getPrefix(FORMAT);
	}

	public function getPrefix() {
		$parts = preg_split("/\*/", FORMAT);
		return $parts[0]; // returns only the string before the first '*'
	}
  
	public function randomCharacter() {
		$rand = rand(0,NUM_CHARS-1);
		return $this->characters[$rand];
	}	
  
	public function generateCode() {
		$code = '';
		while(strlen($code) < $this->num_random_chars) {
		    $char = $this->randomCharacter();
		    if($char != substr($code, -1) && !strpos($code, $char) ) { $code .= $char; }
		} // END while
		$code = $this->prefix.$code;
		return $code;
	}
  
	public function generateMany($number_of_codes) {
	  $codes = Array();
	  $i = 0;
	  while($i < $number_of_codes) {
	    $code     = $this->generateCode();
	    if($this->codeIsNew($code)) { 
	      	array_push($codes, $code);
	      }
	    $i++;
	  }
	  return $codes;
	}
  
	public function codeIsNew($code) {
		$code = mysql_real_escape_string($code);
		$sql = "SELECT COUNT(*) FROM ".DBTABLE ." WHERE voucher_code = '".$code."'";
		if (mysql_query($sql)) {  //     echo "<p> $code not present </p>";
			$insert = "INSERT INTO ".DBTABLE ." SET voucher_code ='".$code."', cda='".CDA ."';";
			if(mysql_query($insert)) {
				return true;
			} else {
				echo 'Query FAILED :: ' .$sql .'MySQL Error :: ' . mysql_error();
				return false;
			}
		} else {
			echo 'Query FAILED :: ' .$sql .'MySQL Error :: ' . mysql_error();
			return false;
		}
	}  
} // END class Codes
?>