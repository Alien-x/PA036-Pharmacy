<?php

use Nette\Security as NS;

class UserManager extends Nette\Object implements NS\IAuthenticator
{
	const
		TABLE_NAME = 'lekarnik',
		COLUMN_ID = 'id_lekarnik',
		COLUMN_NAME = 'id_lekarnik',
		COLUMN_PASSWORD_HASH = 'heslo',
		COLUMN_ROLE = 'id_pravo';


	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

                // username must be integer
                if(!is_numeric($username)) {
                    throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
                }
                
		$row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $username)->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif ($password !== $row[self::COLUMN_PASSWORD_HASH]) {
                        
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		}
                
		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);
		return new Nette\Security\Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
	}
}



class DuplicateNameException extends \Exception
{}
