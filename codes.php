<?php
require(dirname(__FILE__) .'/config.php'); // contains the DB credentials and connection

// Defaults:
define('DEFAULT_VOUCHER_CODE_LENGTH', 10);
define('DEFAULT_SECURITY_CODE_LENGTH', 3);
define('DEFAULT_NUMBER_OF_CODES_TO_CREATE', 20);
define('CHARACTERS_FOR_VOUCHER_CODES', 'A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,T,U,V,W,X,Y,Z,2,3,4,5,6,7,8,9,2,3,4,5,6,7,8,9');
define('NUM_CHARS', (strlen(CHARACTERS_FOR_VOUCHER_CODES)+1)/2);

// get variables
$format = (isset($_GET['format']) ? mysql_real_escape_string($_GET['format']) : '********');
define('FORMAT', $format);
$number_of_codes = (isset($_GET['numcodes']) ? mysql_real_escape_string($_GET['numcodes']) : DEFAULT_NUMBER_OF_CODES_TO_CREATE);
$cda = (isset($_GET['cda']) ? mysql_real_escape_string($_GET['cda']) : 'null');
define('CDA', $cda);


class Codes
{	
  public $characters;
  public $num_random_chars = 8;
  public $prefix = '';

  public function setCharsArray(){
  	$this->characters = explode(",", CHARACTERS_FOR_VOUCHER_CODES);	
  }
  public function setNumRandomChars() {
  	$this->num_random_chars = strlen(FORMAT) - strlen($this->prefix);
  }
  public function setPrefix($format) {
  	$this->prefix = $this->getPrefix($format);
  	return $this->prefix;
  }
  public function getPrefix($format) {
  	$parts = preg_split("/\*/", $format);
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
	  }
	  $code = $this->prefix.$code;
	  return $code;
  }
  
	public function generateMany($number_of_codes) {
	  $codes = Array();
	  $i = 0;
	  while($i < $number_of_codes) {
	    $code     = $this->generateCode();
	//    $security = rand(101,999); // $code != substr($code_list) && !strpos($code, $char) 
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
	  if (mysql_query($sql)) {
	//     echo "<p> $code not present </p>";
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
  
}
$C = new Codes();

$C->setPrefix($format);
$C->setNumRandomChars();
$C->setCharsArray();

echo 'Prefix : ' .$C->prefix .'<br />';
$codes = $C->generateMany($number_of_codes);
echo '<pre>';
print_r($codes);
echo '</pre>';


?>