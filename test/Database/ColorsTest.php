<?php

namespace Anax\Database;
 
/**
 * Tests for base class for database models
 *
 */
class CDatabaseModelTest extends \PHPUnit_Framework_TestCase implements \Anax\DI\IInjectionAware
{
	use \Anax\DI\TInjectable;
	
	static private $colors;
	
	/**
     * setUpBeforeClass, called once for all tests in this class.
     *
     * @return void
     *
     */
    public static function setUpBeforeClass()
    {
		
		self::$colors = new Colors();
		self::$colors->setDI($di);
		self::$color->init();
		
	}
	
	public function testgetSource()
    {
		$res = self::$colors->getSource();
		$exp = "colors";
		
		$this->assertEquals($res, $exp, "Table name missmatch.");
    }
 
}