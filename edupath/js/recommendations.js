document.addEventListener("DOMContentLoaded", function () {
	// Fetch recommendations when the page loads
	fetchRecommendations();

	function fetchRecommendations() {
		fetch('recommendations.php')
			.then(response => response.json())
			.then(data => {
				if (data.error) {
					alert(data.error);  // Show an error if the user is not logged in or profile is not found
				} else {
					displayRecommendations(data);  // Display recommendations
				}
			})
			.catch(error => {
				console.error('Error fetching recommendations:', error);
			});
	}

	function displayRecommendations(recommendations) {
		const container = document.getElementById("recommendations-container");
		container.innerHTML = ''; // Clear any previous content

		if (recommendations.length > 0) {
			recommendations.forEach(program => {
				const programElement = document.createElement("div");
				programElement.classList.add("program");
				programElement.innerHTML = `
                    <h3>${program.name}</h3>
                    <p><strong>Description:</strong> ${program.description}</p>
                    <p><strong>Provider:</strong> ${program.provider}</p>
                    <p><strong>Cost:</strong> ${program.cost}</p>
                    <p><strong>Mode of Study:</strong> ${program.mode}</p>
                `;
				container.appendChild(programElement);
			});
		} else {
			container.innerHTML = "No recommended programs found.";
		}
	}
});
