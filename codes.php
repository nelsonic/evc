<?php
require(dirname(__FILE__) .'/config.php'); // contains the DB credentials and connection

// Defaults:
define('DEFAULT_VOUCHER_CODE_LENGTH', 10);
define('DEFAULT_SECURITY_CODE_LENGTH', 3);
define('DEFAULT_NUMBER_OF_CODES_TO_CREATE', 20);
define('CHARACTERS_FOR_VOUCHER_CODES', 'A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,T,U,V,W,X,Y,Z,2,3,4,5,6,7,8,9,2,3,4,5,6,7,8,9');

// variables to be used when generating codes
$voucher_code_length = (isset($_GET['codelength']) ? mysql_real_escape_string($_GET['codelength']) : DEFAULT_VOUCHER_CODE_LENGTH);
$security_code_length = (isset($_GET['seclength']) ? mysql_real_escape_string($_GET['seclength']) : DEFAULT_SECURITY_CODE_LENGTH);
$number_of_codes = (isset($_GET['numcodes']) ? mysql_real_escape_string($_GET['numcodes']) : DEFAULT_NUMBER_OF_CODES_TO_CREATE);

function generateCode($length) {
  $code = '';
  while(strlen($code) < $length) {
      $char = randomCharacter();
      if($char != substr($code, -1) && !strpos($code, $char) ) { $code .= $char; }
  }
  return $code;  
}

function randomCharacter() {
  $numchars = (strlen(CHARACTERS_FOR_VOUCHER_CODES)+1)/2;
  $characters = explode(",", CHARACTERS_FOR_VOUCHER_CODES);
  $rand = rand(0,$numchars-1);
  $randomchar = $characters[$rand];
  return $randomchar;
}

function generateMany($number_of_codes, $voucher_code_length, $export_file) {
  $handle = fopen($export_file, 'w') or die("can't open file" .$export_file);
  fwrite($handle, "VoucherCode,SecurityCode \n");
  $code_list = '<table><tr><th>Voucher Code</th><th>Security Code </th></tr>';
  $i = 0;
  while($i < $number_of_codes) {
    $code     = generateCode($voucher_code_length);
    $security = rand(101,999); // $code != substr($code_list) && !strpos($code, $char) 
    if(codeIsNew($code, $security)) { 
      $code_list .= '<tr><td>' .$code .'</td><td>' .$security .'</td></tr>'; 
      fwrite($handle, $code.',' .$security ."\n");
    }
    $i++;
  }
  fclose($handle);
  return $code_list .'</table>';
}

function codeIsNew($code, $security) {
  $sql = "SELECT COUNT(*) FROM ".DBTABLE ." WHERE voucher_code = '".mysql_real_escape_string($code)."'";
  if (mysql_query($sql)) {
//     echo "<p> $code not present </p>";
    $insert = "INSERT INTO ".DBTABLE ." (voucher_code, security_code) "
	     ."VALUES ('".mysql_real_escape_string($code)."', '".mysql_real_escape_string($security)."'  )";
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

echo "<pre><h3> Need Random Voucher Codes? You're in the right place. </h3>";
$export_file = './csv/'.md5(time()).'.csv';


$codes = generateMany($number_of_codes, $voucher_code_length, $export_file);
echo "<a href='$export_file'>Download the csv file </a> </br>";
echo  $codes."<br />";

try {

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
} // END try catch


try {
// the target class

class Random
{


    public function generateCode($length) {
//       return $json_string = file_get_contents($file_name);
    }
    
    public function checkCodeIsNew($code) {
      $json = json_decode($json_string, true);   
      return $json;
    }
}

// use the Random Class
$Random = 'Random';



// echo "<h2>" .$voucher_code_length ."</h2>";
// $json_string = $Random::generateCode($voucher_code_length);

} catch (Exception $e) { echo $e->getMessage(); } //

class Codes
{
  public function connectToServer($serverName=null)
  {
    if($serverName==null){
      throw new Exception("That's not a server name!");
    }
    $fp = fsockopen($serverName,80);
    return ($fp) ? true : false;
  }
  public function returnSampleObject()
  {
    return $this;
  }
}

?>