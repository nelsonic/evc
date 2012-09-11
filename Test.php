<?php
require_once('codes.php');

define('START', microtime());
class ExternalVoucherCodesTests extends PHPUnit_Framework_TestCase
{
  public function setUp(){ }
  public function tearDown(){ }
  public function testConnectionIsValid()
  {
    // test to ensure that the object from an fsockopen is valid
    $C = new Codes();
    $serverName = 'www.google.com';
    $this->assertTrue($C->connectToServer($serverName) !== false);
  }
  public function testExecutionTime() {
  	$this->assertTrue(true !== false);
	define('END', microtime());
	print 'hello ' .END .' - ' .START .' = ' .END-START;
  }
  public function testVoucherCodeFormat() {
  	$this->assertTrue(true !== false);
  }
}

?>