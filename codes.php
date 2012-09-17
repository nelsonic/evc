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
$db = mysql_connect(DBHOST,DBUSER,DBPASS); // need a connection for mysql_real_escape_string !! :-(

// Defaults:
define('DEFAULT_NUMCODES', 20);
define('CHARACTERS_FOR_VOUCHER_CODES', 'A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,T,U,V,W,X,Y,Z,2,3,4,5,6,7,8,9,2,3,4,5,6,7,8,9');
define('NUM_CHARS', (strlen(CHARACTERS_FOR_VOUCHER_CODES)+1)/2);

// get variables
// $format = (isset($_GET['format']) ? mysql_real_escape_string($_GET['format']) : '********');
// define('FORMAT', $format);
$number_of_codes = (isset($_GET['numcodes']) ? mysql_real_escape_string($_GET['numcodes']) : DEFAULT_NUMCODES);
$cda = (isset($_GET['cda']) ? mysql_real_escape_string($_GET['cda']) : 'a1G20000000PigOEAS');
define('CDA', $cda);

class Codes
{	
	public $characters;
	public $num_random_chars;
	public $format;
	public $prefix;
	public $db;
	public $fieldlength;

	function __construct() { // sets the above vars
  		$this->characters = explode(",", CHARACTERS_FOR_VOUCHER_CODES);	
  		$this->format = (isset($_GET['format']) ? mysql_real_escape_string($_GET['format']) : '********');
  		$num_rand = strlen($this->format) - strlen($this->prefix);
  		if($num_rand < 4) { $num_rand = 4;  }
  		$this->num_random_chars = $num_rand;
  		$this->prefix = $this->setPrefix($this->format);
  		$this->db = $this->connectMySQL();
  		$this->fieldlength = 32768;
	}

	public function getPrefix($format) {
		$parts = preg_split("/\*/", $format);
		return $parts[0]; // returns only the string before the first '*'
	}
	public function setPrefix($format) {
		$this->prefix = $this->getPrefix($format);
		return $this->prefix;	
	}
  
	public function randomCharacter() {
		$rand = rand(0,NUM_CHARS-1);
		return $this->characters[$rand];
	}	
	public function connectMySQL() {
		$db = mysql_connect(DBHOST,DBUSER,DBPASS);
		if(mysql_select_db(DBNAME, $db)) { return $db; }
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
	    $code = $this->generateCode();
	    if($this->codeIsNew($code)) { 
	      	array_push($codes, $code);
	      }
	    $i++;
	  }
	  return $codes;
	}
  
	public function codeIsNew($code) {
		$code = mysql_real_escape_string($code);
		$num_rows = mysql_num_rows( mysql_query("SELECT * FROM ".DBTABLE." WHERE voucher_code = '$code'") );
		if ($num_rows < 1) {
			$insert = "INSERT INTO ".DBTABLE ." SET voucher_code ='$code', cda='".CDA ."';";
			if(mysql_query($insert)) {
				return true;
			} else { return false; }
		} else     { return false; }
	}  
	
	// because the maximum length for a salesforce string is   Long Text Area(32768)
	// we can only insert this many characters
	public function prepareCodesForInsert($codes) {
		$num_codes = count($codes);
		$code_length = strlen($codes[0]);
		$c = ceil ( $num_codes * ( $code_length + 1 ) / $this->fieldlength );
		$prep = Array();
		$i = $num_codes-1;
		while($c >= 0) {
			$codestring = ''; // reset
			while($i >= 0 && strlen($codestring) <= $this->fieldlength-$code_length-1) {
				$codestring .= $codes[$i] .',';
				$i--;
			}
			$codestring = substr($codestring, 0, -1); // remove last ','
			if(strlen($codestring)>0) { array_push($prep, $codestring); }
			$c--;
		}
		return $prep;
	}
	
} // END class Codes
?>