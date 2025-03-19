<?php
// Include the necessary files, like the RecommendationModel
require_once 'RecommendationModel.php';  // Make sure to set the correct path

// Define a test field for user input (e.g., Accountancy or Business)
$userField = 'Accountancy';  // Change this to whatever field you're testing

// Call the method to get the recommended programs
$programs = RecommendationModel::recommendPrograms($userField);

// Display the results
echo "<pre>";
print_r($programs);  // This will print out the filtered programs
echo "</pre>";
