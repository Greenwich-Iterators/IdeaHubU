import express, { Request, Response } from "express";
import mongoose, { Connection } from "mongoose";
import routeHandler from "./router";

// Initialisation of express and mongodb
const app = express();
const PORT = 9000;
const MONGODB_USERNAME = process.env.MONGODB_USERNAME;
const MONGODB_PASSWORD = process.env.MONGODB_PASSWORD;
const URI = `mongodb://${MONGODB_USERNAME}:${MONGODB_PASSWORD}@ideauhub-shard-00-00.ap32l.mongodb.net:27017,ideauhub-shard-00-01.ap32l.mongodb.net:27017,ideauhub-shard-00-02.ap32l.mongodb.net:27017/?replicaSet=atlas-cpytvt-shard-0&ssl=true&authSource=admin`;

// Database connection
mongoose.connect(URI, {
	dbName: "main",
});

const db: Connection = mongoose.connection;
db.on("error", console.error.bind(console, "Connection error:"));
db.once("open", () => {
	console.log("Connected to MongoDB");
});

// Express app setup
app.get("/", (req: Request, res: Response) => {
	res.send("Hello from Express!");
});

// API Route Handler
app.use("/api", routeHandler);

// Start the server
async function startServer() {
	app.listen(PORT, () => {
		console.log(`Server is running on http://localhost:${PORT}`);
	});
}

startServer().catch(console.error);
