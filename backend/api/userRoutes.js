import express from "express";

const userRoutes = express.Router();

userRoutes.get("/user", (req, res) => {
	res.send("Hello from nested api.");
});

export default userRoutes;
