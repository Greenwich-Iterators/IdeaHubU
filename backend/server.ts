import express from "express";
import mongoose from "mongoose";
import mainRouter from "./router";
import auth from "./middleware/auth";

// Initialisation of express and mongodb
const app = express();
const PORT = 9000;
const MONGODB_USERNAME = process.env.MONGODB_USERNAME;
const MONGODB_PASSWORD = process.env.MONGODB_PASSWORD;
const URI = `mongodb://${MONGODB_USERNAME}:${MONGODB_PASSWORD}@ideauhub-shard-00-00.ap32l.mongodb.net:27017,ideauhub-shard-00-01.ap32l.mongodb.net:27017,ideauhub-shard-00-02.ap32l.mongodb.net:27017/?replicaSet=atlas-cpytvt-shard-0&ssl=true&authSource=admin`;

// Database connection
mongoose
	.connect(URI, {
		dbName: "main",
	})
	.then(() => console.log("Connected to MongoDB"))
	.catch((error) => console.error("MongoDB connection error:", error));



// API Route Handler
app.use(express.json());
app.use("/api", mainRouter);

// Auth Middlware
app.use(auth);

// Start the server
app.listen(PORT, () => {
	console.log(`Server running on http://localhost:${PORT}`);
});
