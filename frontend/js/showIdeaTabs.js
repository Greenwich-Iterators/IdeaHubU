// Store the original HTML content of #ideasContainer
const originalContent = document.getElementById("ideasContainer").innerHTML;

// Function to restore the original content of #ideasContainer
function showOriginalView() {
	document.getElementById("ideasContainer").innerHTML = originalContent;
}

// Initial setup: Simulate the idea data and display it based on tab selection

const ideas = {
	mostPopular: [
		{
			title: "Improve Campus Wi-Fi",
			tags: ["Technology"],
			likes: 45,
			views: 127,
			comments: 12,
		},
		{
			title: "New Teaching Methods",
			tags: ["Teaching"],
			likes: 60,
			views: 90,
			comments: 15,
		},
		{
			title: "Need more Water stations",
			tags: ["Health"],
			likes: 39,
			views: 128,
			comments: 19,
		},
		{
			title: "Improve Projector display",
			tags: ["Teaching"],
			likes: 90,
			views: 89,
			comments: 45,
		},
		{
			title: "Increase Library hours ",
			tags: ["Learning"],
			likes: 40,
			views: 83,
			comments: 41,
		},
		// Add more ideas
	],
	mostLiked: [
		{
			title: "Outdoor Study Spaces",
			tags: ["Facilities"],
			likes: 80,
			views: 150,
			comments: 20,
		},
		{
			title: "Better Cafeteria Options",
			tags: ["Facilities"],
			likes: 75,
			views: 110,
			comments: 14,
		},
		// Add more ideas
	],
	mostViewed: [
		{
			title: "Enhanced Library Hours",
			tags: ["Facilities"],
			likes: 35,
			views: 200,
			comments: 18,
		},
		{
			title: "Green Campus Initiative",
			tags: ["Environment"],
			likes: 50,
			views: 180,
			comments: 25,
		},
		// Add more ideas
	],
};

// Function to display all categories on initial page load or after navigating away
function showAllIdeas() {
	const container = document.getElementById("ideasContainer");
	container.innerHTML = ""; // Clear any previously displayed ideas

	Object.keys(ideas).forEach((category) => {
		const categorySection = document.createElement("div");
		categorySection.className = "category";
		categorySection.innerHTML = `<h2>${capitalizeFirstLetter(
			category
		)} Ideas</h2>`;

		const ideasGrid = document.createElement("div");
		ideasGrid.className = "ideas-grid";

		ideas[category].forEach((idea) => {
			const ideaCard = document.createElement("div");
			ideaCard.className = "idea-card";
			ideaCard.innerHTML = `
                <h3>${idea.title}</h3>
                <p>🔖 ${idea.tags.join(", ")}</p>
                <p>👍 ${idea.likes} 👀 ${idea.views} 💬 ${idea.comments}</p>
            `;
			ideasGrid.appendChild(ideaCard);
		});

		categorySection.appendChild(ideasGrid);
		container.appendChild(categorySection);
	});
}

// Function to capitalize the first letter of each category
function capitalizeFirstLetter(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

// Function to render ideas based on selected tab
function showIdeas(category) {
	const container = document.getElementById("ideasContainer");
	container.innerHTML = ""; // Clear previous ideas

	const categorySection = document.createElement("div");
	categorySection.className = "category";
	categorySection.innerHTML = `<h2>${capitalizeFirstLetter(
		category
	)} Ideas</h2>`;

	const ideasGrid = document.createElement("div");
	ideasGrid.className = "ideas-grid";

	ideas[category].forEach((idea) => {
		const ideaCard = document.createElement("div");
		ideaCard.className = "idea-card";
		ideaCard.innerHTML = `
            <h3>${idea.title}</h3>
            <p>🔖 ${idea.tags.join(", ")}</p>
            <p>👍 ${idea.likes} 👀 ${idea.views} 💬 ${idea.comments}</p>
        `;
		ideasGrid.appendChild(ideaCard);
	});

	categorySection.appendChild(ideasGrid);
	container.appendChild(categorySection);
}

// Load more ideas (example function, to be expanded as needed)
function loadMoreIdeas() {
	alert("Load more ideas functionality to be implemented!");
}
