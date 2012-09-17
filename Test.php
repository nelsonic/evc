<?php
require_once('codes.php');
define('START', microtime());
echo '- - - - - - - - - - - - - - - - - - - - - - - - - ';
class ExternalVoucherCodesTests extends PHPUnit_Framework_TestCase
{
  public function setUp(){ }
  public function tearDown(){ }
  
  public function testPrefix()
  {
    // test to ensure that the object from an fsockopen is valid
    $C = new Codes();
	$format = "ABC****";
	$prefix = $C->getPrefix($format);
	$this->assertTrue($prefix === 'ABC');
  }
  public function testPrefixNone()
  {
    // test to ensure that the object from an fsockopen is valid
    $C = new Codes();
	$format = "******";
	$prefix = $C->getPrefix($format);
	$this->assertTrue(strlen($C->prefix)==0);
  }
  
  public function testRamdomCharacter()
  {
  	 $C = new Codes();	
  	 $rand = $C->randomCharacter();
  	 $this->assertTrue(in_array($rand, $C->characters));
  }
  public function testGenerateCode()
  {
  	 $C = new Codes();
  	 $format = "ABC****";
	 $prefix = $C->setPrefix($format);	
  	 $code = $C->generateCode();
  	 $this->assertTrue(strpos($code,$C->prefix)>=0);
  }
  public function testGenerateMany_200_Codes()
  {
  	 $C = new Codes();
  	 $number_of_codes = 200;
  	 $codes = $C->generateMany($number_of_codes);
  	 $this->assertTrue(count($codes) == 200);
  }
  public function testVoucherCodeFormat() {
  	 $C = new Codes();
  	 $format = "ABC****";
	 $prefix = $C->setPrefix($format);	
	 $C->num_random_chars = strlen($format) - strlen($C->prefix);
	 $code = $C->generateCode();
     $start = strlen($C->prefix);
     $random_part = substr($code,$start);
     $random_char_count = substr_count($format, '*');
     echo "## ".$code ." | start: " .$start ." | random: " .$random_part;
  	 $this->assertTrue( strlen($random_part) == $random_char_count && ($prefix === 'ABC') );
  }
  public function testCodeIsNew() {
  	 $C = new Codes();
	 $not_new_code = $C->generateCode();
	 $C->codeIsNew($not_new_code);
	 $new_code = $C->generateCode();
	 //  && $C->codeIsNew($new_code) 
	 $existing_code = 'RICK9TQX4H';
  	 $this->assertTrue($C->codeIsNew($existing_code) == false);
  }
  public function testPrepareCodes() {
  	 $C = new Codes();
  	 $C->format = "ABC******"; // 9 characters + ',' will be 10 chars
	 $C->prefix = $C->setPrefix($C->format);	
	 $C->num_random_chars = strlen($C->format) - strlen($C->prefix);
  	 $C->fieldlength = 100; // sets fieldlength artifically low just for test usually 32768
  	 $num_codes = 101;
  	 $codes = $C->generateMany($num_codes);
  	 $code_length = strlen($codes[0]);
  	 $prep  = $C->prepareCodesForInsert($codes);
  	 $c = ceil ( $num_codes * ( $code_length + 1 ) / $C->fieldlength );
  	 echo "\n | first code : $codes[0] ";
  	 echo " | num_codes : $num_codes ";
  	 echo " | code_length : $code_length \n";
  	 print_r($prep);
  	 // we expect the number of strings of codes to be 11
  	 $this->assertTrue(count($prep) == $c);
  }
  
  public function testExecutionTime() {
  	$this->assertTrue(true !== false);
	define('END', microtime());
	print 'hello ' .END .' - ' .START .' = ' .END-START;
  }
  
}

?>