<?php
class Accounts {
	private $CMS;

	function __construct($CMS){
		$this->CMS = $CMS;
	}

	function passwordHasher($password) { return password_hash($password, PASSWORD_DEFAULT); }
	function passwordVerfiy($password, $hash) { return password_verify($password, $hash); }

	function getAll() {
		return $this->CMS->DataBase->execute('SELECT * FROM account')->fetchAll();
	}

	function create($data) {
		$reg = [
			'uniqid' => $data['uniqid'] ?? uniqid(),
			'username' => $data['username'] ?? '',
			'password' => $data['password'] ?? '',
			'role' => $data['role'] ?? 0
		];

		$reg['password'] = $this->passwordHasher($reg['password']);

		$exSql = "INSERT INTO account (uniqid, username, password, role, register_date) VALUES(?, ?, ?, ?, Now())";
		//return;
		$this->CMS->DataBase->execute(
			$exSql,
			[$reg['uniqid'], $reg['username'], $reg['password'], $reg['role']]
		);

		$userToken = $this->CMS->Function->RandomString(25);
		if(isset($_SESSION['string_token'])) $userToken = htmlentities($_SESSION['string_token']);
		$this->CMS->DataBase->execute(
			'INSERT INTO account_login (token, user_id, reg_date) VALUES(?,?,Now())',
			[$userToken, $reg['uniqid']]
		);

		$_SESSION['string_token'] = $userToken;

		return ['account' => $reg, 'success' => true];

		//CMS->Log->added('<@0> à créer <@'.$reg['uniqid'].'>');
	}

	function connect($data) {
		$reg = [
			'username' => $data['username'] ?? '',
			'password' => $data['password'] ?? ''
		];

		$reqUser = $this->CMS->DataBase->execute(
			'SELECT * FROM account WHERE username = ?',
			[$reg['username']]
		)->fetchAll();

		if(!$reqUser) return null;

		$reqUser = $reqUser[0];

		if($this->passwordVerfiy($reg['password'], $reqUser['password'])) {
			$_SESSION = $reqUser;
			$_SESSION['Role'] = intval($reqUser['role']);

			$userToken = $this->CMS->Function->RandomString(25);
			if(isset($_SESSION['string_token'])) $userToken = htmlentities($_SESSION['string_token']);
			$this->CMS->DataBase->execute(
				'INSERT INTO account_login (token, user_id, reg_date) VALUES(?,?,Now())',
				[$userToken, $reqUser['uniqid']]
			);

			$_SESSION['string_token'] = $userToken;

			return ['account' => $reqUser, 'success' => true];
		}
		return null;
	}
}