<?php

namespace Anax\Database;
 
/**
 * Class with table for testing CDatabaseModel
 *
 */
class Colors extends CDatabaseModel
{
	
	public function init()
	{
		
		$di->setShared('db', function() {
			$db = new \Mos\Database\CDatabaseBasic();
			$db->setOptions(['dsn' => "sqlite:memory::", "verbose" => false]);
			$db->connect();
			return $db;
		});
		
		$this->db->dropTableIfExists("test");
		$this->db->execute();
		$this->db->createTable(
			'test',
			[
				'id'    => ['integer', 'auto_increment', 'primary key', 'not null'],
				'name'  => ['varchar(20)'],
				'color' => ['varchar(20)'],
			]
		);
		$this->db->execute();
		$this->db->insert(
			'test',
			['name', 'color']
		);
		$this->db->execute(['Sofia', 'Red']);
		$this->db->execute(['Olle', 'Blue']);
		
		
        /*
        $this->db->select("*")
            ->from("test")
        ;
        var_dump($this->db->executeFetchAll());
        */
		
	}
	
}