<?php
require_once __DIR__ . '/../models/UserProfile.php';

class UserProfileController
{
	private $userProfile;

	public function __construct()
	{
		// Instantiate the UserProfile model
		$this->userProfile = new UserProfile();
	}

	// View the profile of the logged-in user
	public function viewProfile($userId)
	{
		// Fetch the user's profile from the database
		return $this->userProfile->getProfile($userId);
	}

	// Update the profile of the logged-in user
	public function updateProfile($userId, $name, $fullname, $fieldOfInterestId)
	{
		// Update the user's profile in the database
		return $this->userProfile->updateProfile($userId, $name, $fullname, $fieldOfInterestId);
	}

	// Fetch fields of interest for the form selection
	public function getFieldsOfInterest()
	{
		return $this->userProfile->getFieldsOfInterest();
	}
}
