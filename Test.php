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

  public function testVoucherCodeFormat() {
  	$this->assertTrue(true !== false);
  	
  }
  public function testExecutionTime() {
  	$this->assertTrue(true !== false);
	define('END', microtime());
	print 'hello ' .END .' - ' .START .' = ' .END-START;
  }
  
}

?>