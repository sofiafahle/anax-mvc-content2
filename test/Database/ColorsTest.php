<?php

namespace Anax\Database;
 
/**
 * Tests for base class for database models
 *
 * @property $db Database connection
 *
 */
class ColorsTest extends \PHPUnit_Framework_TestCase implements \Anax\DI\IInjectionAware
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
		
		$di = new \Anax\DI\CDIFactoryDefault();
		
		$di->setShared('db', function() {
			$db = new \Mos\Database\CDatabaseBasic();
            $db->setOptions(['dsn' => "sqlite:memory::", 'debug_connect' => true, 'verbose' => false]);
            $db->connect();
            return $db;
		});
		
		self::$colors = new Colors();
		self::$colors->setDI($di);
		self::$colors->init();
		
	}
	

	public function testGetSource()
    {
		$res = self::$colors->getSource();
		$exp = "colors";
		
		$this->assertEquals($exp, $res, "Table name missmatch.");
    }
	

	public function testFind()
	{
		$res = self::$colors->find(1);	
		
		$res2 = array();
		foreach($res as $key => $val){
			$res2[$key] = $val;
		}
		
		$res = $res2['name'];
		$exp =  'Sofia';
		
		$this->assertEquals($exp, $res, "Array result not as expected");
	}
	
	
	/**
	 * Find and return all.
	 *
	 * @return array
	 */
	public function testFindAll()
	{
		$res = self::$colors->findAll();
		$res = count($res);
		$exp = 3;
		
		$this->assertEquals($exp, $res, "Row count missmatch.");
	}
	
	/**
	 * Find and return rows based on condition.
	 *
	 * @param string $column as key for condition
	 * @param string $value as value for condition.
	 *
	 * @return array
	 */
	public function testFindWhere() {
		$res = self::$colors->findWhere('color', 'Red');
		$exp = 'Sofia';
		
		$this->assertEquals($res[0]->name, $exp, "Color missmatch.");
	}
	
	/**
	 * Find and return rows in specific order.
	 *
	 * @param string $column as key for condition
	 * @param string $order as order to sort in, ASC or DESC.
	 *
	 * @return array
	 */
	public function testFindAllOrder() {
		$res = self::$colors->findAllOrder('color', 'ASC');
		$exp = 'Blue';
		
		$this->assertEquals($res[0]->color, $exp, "Color missmatch.");
	}
	
	/**
	 * Get object properties.
	 *
	 * @return array with object properties.
	 */
	public function testGetProperties()
	{
		$array = array('id' => 1, 'name' => 'Sofia', 'color' => 'Red');
		
		self::$colors->setProperties($array);
		$res = self::$colors->getProperties();

		$exp = $array;
		
		
		$this->assertEquals($exp, $res, "Array missmatch.");
	}
	
	/**
	 * Set object properties.
	 *
	 * @param array $properties with properties to set.
	 *
	 * @return void
	 */
	public function testSetProperties()
	{
		$array = array('id' => 2, 'name' => 'Olle', 'color' => 'Blue');
		self::$colors->setProperties($array);
		$res = self::$colors->name;
		$exp = 'Olle';
		
		$this->assertEquals($exp, $res, "Name missmatch.");
	}
	
	/**
	 * Save current object/row.
	 *
	 * @param array $values key/values to save or empty to use object properties.
	 *
	 * @return boolean true or false if saving went okey.
	 */
	public function testSave()
	{
		// Update
		$array = array('id' => 3, 'name' => 'Pia', 'color' => 'Green');
		$res = self::$colors->save($array);
		
		$this->assertTrue($res, "Save/create failed.");
		
		// Create
		$array = array('name' => 'Pia', 'color' => 'Green');
		$res = self::$colors->save($array);
		
		$this->assertTrue($res, "Save/update failed.");
	}
	
	
	/**
	 * Create new row.
	 *
	 * @param array $values key/values to save.
	 *
	 * @return boolean true or false if saving went okey.
	 */
	public function testCreate()
	{
		$array = array('name' => 'Anders', 'color' => 'Black');
		$res = self::$colors->create($array);
	 
		$this->assertTrue($res, "Create failed.");
		
		// Test insertion
		$res = self::$colors->find(4);
		
		$res2 = array();
		foreach($res as $key => $val){
			$res2[$key] = $val;
		}
		
		$res = $res2['name'];
		$exp =  'Anders';
		
		
		$this->assertEquals($exp, $res, "Name missmatch after create.");
	}
	
	/**
	 * Update row.
	 *
	 * @param array $values key/values to save.
	 *
	 * @return boolean true or false if saving went okey.
	 */
	public function testUpdate()
	{
		self::$colors->id = 3;
		
		$array = array('id' => 3, 'name' => 'Pia', 'color' => 'Pink');
		$res = self::$colors->update($array);
	 
		$this->assertTrue($res, "Update failed.");
		
		// Test update
		$res = self::$colors->find(3);
		
		$res2 = array();
		foreach($res as $key => $val){
			$res2[$key] = $val;
		}
		
		$res = $res2['color'];
		$exp =  'Pink';
		
		
		$this->assertEquals($exp, $res, "Color missmatch after update.");
	}
	
	/**
	 * Delete row by id.
	 *
	 * @param integer $id to delete.
	 *
	 * @return boolean true or false if deleting went okey.
	 */
	public function testDelete()
	{
		$res = self::$colors->delete(4);
	 
		$this->assertTrue($res, "Delete failed.");
	}
	
	/**
	 * Delete all rows with where condition.
	 *
	 * @param string $column as key for condition
	 * @param string $value as value for condition.
	 *
	 * @return boolean true or false if deleting went okey.
	 */
	public function testDeleteWhere()
	{
		$res = self::$colors->deleteWhere('color', 'Pink');
	 
		$this->assertTrue($res, "Delete failed.");
	}
	
	/**
	 * Delete all rows.
	 *
	 * @return boolean true or false if deleting went okey.
	 */
	public function testDeleteAll()
	{
		$res = self::$colors->deleteAll();
	 
		$this->assertTrue($res, "Delete failed.");
	}
	
	
	/**
	 * Build a select-query.
	 *
	 * @param string $columns which columns to select.
	 *
	 * @return $this
	 */
	public function testQuery()
	{
		self::$colors->init();
		$res = self::$colors->query('id')
					 ->where('color = "Red"')
					 ->andWhere('name = "Sofia"')
					 ->execute();
		
		$res2 = '';
		foreach($res as $key => $val){
			foreach ($val as $k => $v) {
				$res2 = $v;
			}
		}
		
		$exp =  '1';
		
		$this->assertEquals($exp, $res2, "ID missmatch.");
	}
	
	/**
	 * Build the where part.
	 *
	 * @param string $condition for building the where part of the query.
	 *
	 * @return $this
	 */
	public function where($condition)
	{
		$this->db->where($condition);
	 
		return $this;
	}
	
	/**
	 * Build the where part.
	 *
	 * @param string $condition for building the where part of the query.
	 *
	 * @return $this
	 */
	public function andWhere($condition)
	{
		$this->db->andWhere($condition);
	 
		return $this;
	}
	
	/**
	 * Execute the query built.
	 *
	 * @param array $params for custom query.
	 *
	 * @return $this
	 */
	public function execute($params = [])
	{
		$this->db->execute($this->db->getSQL(), $params);
		$this->db->setFetchModeClass(__CLASS__);
	 
		return $this->db->fetchAll();
	}
	
	/**
	 * Create a slug of a string, to be used as url.
	 *
	 * @param string $str the string to format as slug.
	 * @returns str the formatted slug. 
	 */
	function testSlugify() 
	{
		$res = self::$colors->slugify('Red is nice');
		$exp = 'red-is-nice';
		
		$this->assertEquals($exp, $res, "Slug missmatch.");
	}
 
}