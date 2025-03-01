<?php
require_once __DIR__ . '/../models/UserAuthentication.php';

class UserAuthenticationController
{
	private $authModel;

	public function __construct()
	{
		$this->authModel = new UserAuthentication();
	}

	public function register($username, $email, $password)
	{
		return $this->authModel->registerUser($username, $email, $password);
	}

	public function login($usernameOrEmail, $password)
	{
		return $this->authModel->loginUser($usernameOrEmail, $password);
	}
}
