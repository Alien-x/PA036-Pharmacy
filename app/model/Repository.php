<?php
namespace Pharmacy;
use Nette;



/**
 * Executes operations on database table
 */
abstract class Repository extends Nette\Object
{
    /** @var Nette\Database\Context */
    protected $connection;

    public function __construct(Nette\Database\Context $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Returns object which represents database table.
     * @return Nette\Database\Table\Selection
     */
    protected function getTable($className = null)
    {
        // the name of table is extract from the name of class
        // removes namespace and the backslash
        if(is_null($className)) {
            $className = substr(get_class($this), strrpos(get_class($this), '\\') + 1);
        }
        
        return $this->connection->table($className);
    }

    /**
     * Returns all rows from table
     * @return Nette\Database\Table\Selection
     */
    public function findAll()
    {
        return $this->getTable();
    }

    /**
     * Returns rows restricted by filter, e.g. array('name' => 'John').
     * @return Nette\Database\Table\Selection
     */
    public function findBy(array $by)
    {
        return $this->getTable()->where($by);
    }

}