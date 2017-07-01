<?php
class DatabasePDO extends PDO  {
	
	private static $pdo = NULL;
	
	public function __construct(){
		
		
		
  		$verbose = true;

  		$mysql_dbname = "TEST";
  		$mysql_user = "root";
  		$mysql_password = "root";
	
  		$dsn = "mysql:host=localhost;dbname=$mysql_dbname";
  		$user = $mysql_user;
  		$password = $mysql_password;
		
		try {
			parent::__construct($dsn,$user,$password);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $erreur) {
			if ($verbose)
				echo 'Erreur : '.$erreur->getMessage();
			else
				echo 'Désolé cher utilisateur...';
		}

	}
	
	public static function CurrentDatabase(){
		
		if(self::$pdo==null){
			
			self::$pdo = new DatabasePDO();
		}
		return self::$pdo;
	}
}