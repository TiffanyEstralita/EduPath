<?php

class RecommendationModel
{
    public static function recommendPrograms($userField)
{
    // Load the collected program data from the JSON file
    $programsJson = file_get_contents(__DIR__ . '/../data/programs.json');

    // Check if the file was read successfully
    if ($programsJson === false) {
        echo "Error: Unable to read programs.json file.";
        return []; // Return an empty array to avoid further errors
    }

    $programs = json_decode($programsJson, true);

    // Check if JSON decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error: JSON decoding failed: " . json_last_error_msg();
        return []; // Return an empty array to avoid further errors
    }

    // Filter programs based on the user's field of interest
    $filteredPrograms = array_filter($programs, function ($program) use ($userField) {
        return stripos($program['course_name'], $userField) !== false; // Assuming 'course_name' is the correct key
    });

    return array_values($filteredPrograms);
}
public static function filterByInstitution($recommendedPrograms, $institution)
    {
        // Filter the already recommended programs by institution
        $filteredByInstitution = array_filter($recommendedPrograms, function ($program) use ($institution) {
            return stripos($program['institution'], $institution) !== false; // Check institution name
        });

        return array_values($filteredByInstitution); // Return the filtered programs
    }
}