<?php
// Include the model file to access the RecommendationModel class
require_once 'models/RecommendationModel.php';

// Get the user's field of interest (you can adjust this based on your actual user input)
$userField = "Accountancy"; // Example user input. Replace with actual user input.

$recommendedPrograms = RecommendationModel::recommendPrograms($userField);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Recommendations</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your own styles here -->
</head>
<body>
    <h1>Recommended Educational Programs</h1>

    <?php if (!empty($recommendedPrograms)): ?>
        <div id="recommendations-container">
            <?php foreach ($recommendedPrograms as $program): ?>
                <div class="program">
                    <h3><?php echo htmlspecialchars($program['name']); ?></h3>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($program['description']); ?></p>
                    <p><strong>Provider:</strong> <?php echo htmlspecialchars($program['provider']); ?></p>
                    <p><strong>Cost (Gross Monthly Mean):</strong> <?php echo htmlspecialchars($program['cost']); ?></p>
                    <p><strong>Employment Rate:</strong> <?php echo htmlspecialchars($program['mode']); ?>%</p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No programs found for your field of interest.</p>
    <?php endif; ?>
</body>
</html>
