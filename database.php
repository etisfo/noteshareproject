<?php
class databaseaccess {

	public $hostname = 'localhost';
	public $username = 'root';
	public $password = 'root';
	private $db = null;
	public $rows;

  	public function __construct() {
        	try {
       			$this->db = new PDO("mysql:host=$hostname;dbname=noteshareproject", $this->username, $this->password);
     		}
        	catch (PDOException $Exception) {
           		throw new Exception("DB failed to connect ".$Exception->getMessage());
       		}
    }

	public function displaybyid($id) {
		if ($this->db === null) throw new Exception("DB is not connected");
		$query = "SELECT * FROM files WHERE id = :id";
		$statement = $this->db->prepare($query);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->execute();
		$this->result = $statement->fetch(PDO::FETCH_ASSOC);
		var_dump($this->result); //debugging only
	}

	public function writetable($title,$id){
		if ($this->db === null) throw new Exception("DB is not connected");
		//query works with `:title` however keeps the commas. Gotta find out what is wrong.
			$query = "CREATE TABLE noteshareproject.:title (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), username VARCHAR(20)) ENGINE=myISAM;";
			$statement = $this->db->prepare($query);
			$title = $title . $id;
			$title = (string) $title;
			$statement->bindValue(':title', $title, PDO::PARAM_STR);
			$statement->execute();
   			print_r($statement->errorInfo());
   			echo $title;
			
	}

	public function write($title,$topic,$subject,$author,$path) {
		if ($this->db === null) throw new Exception("DB is not connected");

		$query = "INSERT INTO `noteshareproject`.`files` (`title` ,`topic` ,`subject` ,`author` ,`path`) VALUES (:title, :topic, :subject, :author, :fpath);";
		$statement = $this->db->prepare($query);
		$statement->bindValue(':title', $title, PDO::PARAM_STR);
		$statement->bindValue(':topic', $topic, PDO::PARAM_STR);
		$statement->bindValue(':subject', $subject, PDO::PARAM_STR);
		$statement->bindValue(':author', $author, PDO::PARAM_STR);
		$statement->bindValue(':fpath', $path, PDO::PARAM_STR);
		$statement->execute();
		$this->writetable($title,$this->db->lastInsertId());
	}



	public function displayfiles($subject,$lbound,$FilesPerPage) {
		if ($this->db === null) throw new Exception("DB is not connected");
		$query = "SELECT * FROM files WHERE subject = :subject ORDER BY id DESC LIMIT :lbound , :FilesPerPage";
		$statement = $this->db->prepare($query);
		$statement->bindValue(':lbound', $lbound, PDO::PARAM_INT);
		$statement->bindValue(':FilesPerPage', $FilesPerPage, PDO::PARAM_INT);
		$statement->bindValue(':subject', $subject, PDO::PARAM_STR);
		$statement->execute();
		$this->result = $statement->fetchAll(PDO::FETCH_ASSOC);
		//var_dump($this->result); //debugging only
	}
	//CRYPTO RELATED 
	public function getuser($username){
		if ($this->db === null) throw new Exception("DB is not connected");
		$query = "SELECT * FROM users WHERE username = :username;";
		$statement = $this->db->prepare($query);
		$statement->bindValue(':username', $username, PDO::PARAM_STR);
		$statement->execute();
		$temp = $statement->fetch(PDO::FETCH_ASSOC);
		$this->hash = $temp['hash'];
	}
	
	public function writeuser($username,$hash){
		if ($this->db  === null) throw new Exception("DB is not connected");

		$query = "INSERT INTO `users`(`username` ,`hash`) VALUES (:username, :hash);";
		$statement = $this->db->prepare($query);
		$statement->bindValue(':username', $username, PDO::PARAM_STR);
		$statement->bindValue(':hash', $hash, PDO::PARAM_STR);
		$statement->execute();
	
	}

}
	
	//NEEDS TO BE RE-WRITTEN TO SUIT NOTESHAREPROJECT
	/*

	public function vote($id){
	
	}
	public function delete($id){ 
		if ($this->db === null) throw new Exception("DB is not connected");

		$query = "DELETE FROM `Blogging-Platform`.`posts` WHERE `posts`.`id` = :id";
		$statement = $this->db->prepare($query);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->execute();
	}

	public function update($title,$text,$id){ 
		if ($this->db === null) throw new Exception("DB is not connected");

		$query = "UPDATE `Blogging-Platform`.`posts` SET `title` = :title,
`text` = :text WHERE `posts`.`id` = :id;";
		$statement = $this->db->prepare($query);
		$statement->bindValue(':title', $title, PDO::PARAM_STR);
		$statement->bindValue(':text', $text, PDO::PARAM_STR);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->execute();
	}
	public function count(){
		if ($this->db === null) throw new Exception("DB is not connected");

		$query = "SELECT COUNT(*) FROM `Blogging-Platform`.`posts`";
		$statement = $this->db->prepare($query);
		$statement->execute();
		$tempcount = $statement->fetch(PDO::FETCH_ASSOC);
		$this->amount = $tempcount['COUNT(*)'];
	}
*/

	

?>
