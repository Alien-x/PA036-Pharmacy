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
            $className = strtolower(substr(get_class($this), strrpos(get_class($this), '\\') + 1));
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
    
    /**
     * Returns rows restricted by filter, e.g. array('name' => 'John').
     * @return Nette\Database\Table\Selection
     */
    public function findByID($value)
    {   
        return $this->getTable()->get($value);
    }
    
    /**
     * Parse postgres function return array to php array
     */
    protected function pg_array_parse($dbarr) {
        // Take off the first and last characters (the braces)
        $arr = substr($dbarr, 1, strlen($dbarr) - 2);

        // Pick out array entries by carefully parsing.  This is necessary in order
        // to cope with double quotes and commas, etc.
        $elements = array();
        $i = $j = 0;        
        $in_quotes = false;
        while ($i < strlen($arr)) {
            // If current char is a double quote and it's not escaped, then
            // enter quoted bit
            $char = substr($arr, $i, 1);
            if ($char == '"' && ($i == 0 || substr($arr, $i - 1, 1) != '\\')) 
                $in_quotes = !$in_quotes;
            elseif ($char == ',' && !$in_quotes) {
                // Add text so far to the array
                $elements[] = substr($arr, $j, $i - $j);
                $j = $i + 1;
            }
            $i++;
        }
        // Add final text to the array
        $elements[] = substr($arr, $j);

        // Do one further loop over the elements array to remote double quoting
        // and escaping of double quotes and backslashes
        for ($i = 0; $i < sizeof($elements); $i++) {
            $v = $elements[$i];
            if (strpos($v, '"') === 0) {
                $v = substr($v, 1, strlen($v) - 2);
                $v = str_replace('\\"', '"', $v);
                $v = str_replace('\\\\', '\\', $v);
                $elements[$i] = $v;
            }
        }

        return $elements;
    }
}