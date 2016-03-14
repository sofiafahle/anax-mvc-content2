<?php

namespace Anax\Database;
 
/**
 * Class with table for testing CDatabaseModel
 *
 *	@property $db Database connection
 *
 */
class Colors extends CDatabaseModel
{
	use \Anax\DI\TInjectable;
	
	
	
	public function init()
	{
		
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
		$this->db->execute(['Pia', 'Green']);
		
		
        /*
        $this->db->select("*")
            ->from("test")
        ;
        var_dump($this->db->executeFetchAll());
        */
		
	}
	
}