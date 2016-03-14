<?php

namespace Anax\Database;
 
/**
 * Class with table for testing CDatabaseModel
 *
 * @property $db Database connection
 *
 */
class Colors extends CDatabaseModel
{
	use \Anax\DI\TInjectable;
	
	
	
	public function init()
	{
		
		$this->db->dropTableIfExists("colors");
		$this->db->execute();
		$this->db->createTable(
			'colors',
			[
				'id'    => ['integer', 'auto_increment', 'primary key', 'not null'],
				'name'  => ['varchar(20)'],
				'color' => ['varchar(20)'],
			]
		);
		$this->db->execute();
		$this->db->insert(
			'colors',
			['name', 'color']
		);
		$this->db->execute(['Sofia', 'Red']);
		$this->db->execute(['Olle', 'Blue']);
		$this->db->execute(['Pia', 'Green']);
		
		
        /*
        $this->db->select("*")
            ->from("colors")
        ;
        var_dump($this->db->executeFetchAll());
        */
		
	}
	
}