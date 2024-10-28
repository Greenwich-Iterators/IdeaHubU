import express from "express";
import mongoose from "mongoose";
const app = express();
const PORT = 9000;
const MONGODB_USERNAME = process.env.MONGODB_USERNAME;
const MONGODB_PASSWORD = process.env.MONGODB_PASSWORD;
const URI =
	`mongodb://${MONGODB_USERNAME}:${MONGODB_PASSWORD}@ideauhub-shard-00-00.ap32l.mongodb.net:27017,ideauhub-shard-00-01.ap32l.mongodb.net:27017,ideauhub-shard-00-02.ap32l.mongodb.net:27017/?replicaSet=atlas-cpytvt-shard-0&ssl=true&authSource=admin`;

mongoose.connect(URI, {
	dbName: "main"
});

const db = mongoose.connection;
db.on("error", console.error.bind(console, "Connection error:"));
db.once("open", () => {
	console.log("Connected to Mongo DataBase");
});

async function main() {
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
}

main().catch(console.dir);
