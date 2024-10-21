// backend/index.js
import express from "express";
const app = express();
const PORT = 9000;

app.get("/", (req, res) => {
	res.send("Hello from Express!");
});

app.get("/api/test", (req, res) => {
	res.send("Hello from the API!");
});

app.get("/api/json_test", (req, res) => {
	const data = {
		name: "John Doe",
		age: 30,
		city: "New York",
	};
	res.json(data);
});

app.listen(PORT, () => {
	console.log(`Server is running on http://localhost:${PORT}`);
});
