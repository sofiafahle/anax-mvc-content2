<?php

namespace Anax\Database;
 
/**
 * Tests for base class for database models
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
		
		$this->assertEquals($res, $exp, "Table name missmatch.");
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
		
		$this->assertEquals($res, $exp, "Array result not as expected");
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
		
		$this->assertEquals($res, $exp, "Row count missmatch.");
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
		$array = array('id' => 1, 'number' => 3, 'name' => 'test');
		
		self::$colors->setProperties($array);
		$res = self::$colors->getProperties();
		
		unset($array['id']);
		$exp = $array;
		
		
		$this->assertEquals($res, $exp, "Array missmatch.");
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
		$array = array('id' => 2, 'number' => 5, 'name' => 'test');
		self::$colors->setProperties($array);
		$res = self::$colors->id;
		$exp = 2;
		
		$this->assertEquals($res, $exp, "Id missmatch.");
	}
	
	/**
	 * Save current object/row.
	 *
	 * @param array $values key/values to save or empty to use object properties.
	 *
	 * @return boolean true or false if saving went okey.
	 */
	public function save($values = [])
	{
		$this->setProperties($values);
		$values = $this->getProperties();
	 
		if (isset($values['id'])) {
			return $this->update($values);
		} else {
			return $this->create($values);
		}
	}
	
	
	/**
	 * Create new row.
	 *
	 * @param array $values key/values to save.
	 *
	 * @return boolean true or false if saving went okey.
	 */
	public function create($values)
	{
		$keys   = array_keys($values);
		$values = array_values($values);
	 
		$this->db->insert(
			$this->getSource(),
			$keys
		);
	 
		$res = $this->db->execute($values);
	 
		$this->id = $this->db->lastInsertId();
	 
		return $res;
	}
	
	/**
	 * Update row.
	 *
	 * @param array $values key/values to save.
	 *
	 * @return boolean true or false if saving went okey.
	 */
	public function update($values)
	{
		// Its update, remove id
		unset($values['id']);
		
		$keys   = array_keys($values);
		$values = array_values($values);
	 
		// Use id as where-clause
		$values[] = $this->id;
	 
		$this->db->update(
			$this->getSource(),
			$keys,
			"id = ?"
		);
	 
		return $this->db->execute($values);
	}
	
	/**
	 * Delete row by id.
	 *
	 * @param integer $id to delete.
	 *
	 * @return boolean true or false if deleting went okey.
	 */
	public function delete($id)
	{
		$this->db->delete(
			$this->getSource(),
			'id = ?'
		);
	 
		return $this->db->execute([$id]);
	}
	
	/**
	 * Delete all rows with where condition.
	 *
	 * @param string $column as key for condition
	 * @param string $value as value for condition.
	 *
	 * @return boolean true or false if deleting went okey.
	 */
	public function deleteWhere($column, $value)
	{
		$this->db->delete(
			$this->getSource(),
			$column . ' = ?'
		);
	 
		return $this->db->execute([$value]);
	}
	
	/**
	 * Delete all rows.
	 *
	 * @return boolean true or false if deleting went okey.
	 */
	public function deleteAll()
	{
		$this->db->delete(
			$this->getSource()
		);
	 
		return $this->db->execute();
	}
	
	
	/**
	 * Build a select-query.
	 *
	 * @param string $columns which columns to select.
	 *
	 * @return $this
	 */
	public function query($columns = '*')
	{
		$this->db->select($columns)
				 ->from($this->getSource());
	 
		return $this;
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
	function slugify($str) {
		$str = mb_strtolower(trim($str));
		$str = str_replace(array('å','ä','ö'), array('a','a','o'), $str);
		$str = preg_replace('/[^a-z0-9-]/', '-', $str);
		$str = trim(preg_replace('/-+/', '-', $str), '-');
		return $str;
	}
 
}