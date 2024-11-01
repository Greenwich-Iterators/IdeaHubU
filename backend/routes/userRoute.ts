import express, { Request, Response } from "express";
import User from "../models/userModel";
import { generateAccessToken } from "../utils/jwt";
import { comparePasswords } from "../utils/crypt";
import sessionModel from "../models/sessionModel";

const userRouter = express.Router();

userRouter.get("/", (req: Request, res: Response) => {
	res.send("Hello from /api/user nested api.");
});

userRouter.post("/login", async (req: Request, res: Response) => {
	try {
		const { username, password } = req.body;
		const user = await User.findOne({ username });

		if (!user) {
			res.status(401).json({
				success: false,
				error: "Invalid credentials",
			});
			return;
		}

		const isPasswordValid = await comparePasswords(password, user.password);

		if (!isPasswordValid) {
			res.status(401).json({
				success: false,
				error: "Invalid credentials",
			});
			return;
		}

		const generatedToken = await generateAccessToken(username);
		const userId = user._id;
		let session = await sessionModel.findOne({ userId });
		let lastLogin = null;
		let firstLogin = false;

		if (session) {
			lastLogin = session.lastLogin;
			// Update the session with new login time
			session.lastLogin = new Date();
			await session.save();
		} else {
			// Create a new session if it doesn't exist
			firstLogin = true;
			session = new sessionModel({
				userId,
				lastLogin: new Date(),
			});
			await session.save();
		}

		res.json({
			success: true,
			message: "Login successful",
			token: generatedToken,
			expiresIn: "8h",
			lastLogin: lastLogin,
			firstLogin: firstLogin,
			userId: userId,
			role: user.role
		});
	} catch (error) {
		console.log(error);
		res.status(500).json({ success: false, error: "Server error" });
	}
});

userRouter.post("/register", async (req: Request, res: Response) => {
	try {
		const { username, email, password, role } = req.body;
		const userExists = await User.exists({ username });
		if (userExists) {
			res.status(409).json({
				success: false,
				error: "Username already exists",
			});
			return;
		}

		const newUser = new User({ username, email, password, role });
		await newUser.save();
		const generatedToken = await generateAccessToken(username);
		res.status(201).json({
			success: true,
			message: "User registered successfully",
			token: generatedToken,
		});
	} catch (error) {
		res.status(500).json({ success: false, error: "Registration failed" });
	}








});

export default userRouter;
