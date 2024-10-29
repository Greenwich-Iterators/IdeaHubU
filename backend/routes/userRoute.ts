import express, { Request, Response } from "express";
import User from "../models/userModel";

const userRouter = express.Router();

userRouter.get("/", (req: Request, res: Response) => {
	res.send("Hello from /api/user nested api.");
});

userRouter.post("/register", async (req: Request, res: Response) => {
	try {
		const { username, email, password } = req.body;
		const newUser = new User({ username, email, password });
		await newUser.save();
		res.status(201).json({ message: "User registered successfully" });
	} catch (error) {
		res.status(400).json({ error: "Registration failed" });
	}
});

export default userRouter;
