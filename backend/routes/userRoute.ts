import express, { Request, Response } from "express";
import User, { Roles } from "../models/userModel";
import { generateAccessToken, verifyToken } from "../utils/jwt";
import { comparePasswords } from "../utils/crypt";
import sessionModel from "../models/sessionModel";
import icloud, { sendEmail } from "../utils/email";

const userRouter = express.Router();

userRouter.get("/", (req: Request, res: Response) => {
	res.send("Hello from /api/user nested api.");
});

userRouter.post("/login", async (req: Request, res: Response) => {
	try {
		const { email, password } = req.body;
		const user = await User.findOne({ email });

		if (!user) {
			res.status(401).json({
				success: false,
				error: "Invalid credentials",
			});
			return;
		}
		const userId = user._id;
		const isPasswordValid = await comparePasswords(password, user.password);

		if (!isPasswordValid) {
			res.status(401).json({
				success: false,
				error: "Invalid credentials",
			});
			return;
		}

		// const userId = user._id;
		const generatedToken = await generateAccessToken(user);
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
			lastLogin = new Date();
			session = new sessionModel({
				userId,
				lastLogin: new Date(),
			});
			await session.save();
		}

		res.status(200).json({
			message: "Login successful",
			token: generatedToken,
			expiresIn: "8h",
			lastLogin: lastLogin,
			firstLogin: firstLogin,
			userId: userId,
			role: user.role,
			username: user.username,
		});
	} catch (error) {
		console.log(error);
		res.status(500).json({ success: false, error: "Server error" });
	}
});

userRouter.get("/verifytoken", async (req: Request, res: Response) => {
	try {
		const token = req.headers.authorization?.split(" ")[1];

		if (!token) {
			res.status(401).json({
				valid: false,
				message: "No token provided",
			});
			return;
		}

		const result = verifyToken(token);

		if (!result.valid) {
			res.status(401).json({
				valid: false,
				message: "Invalid or expired token",
			});
			return;
		}
		res.status(200).json({ valid: true, userId: result.user });
	} catch (error) {
		console.error(error);
		res.status(500).json({ valid: false, message: "Server error" });
	}
});

userRouter.post("/register", async (req: Request, res: Response) => {
	try {
		const { firstname, lastname, username, email, password, department } = req.body;
		const userExists = await User.exists({ username });
		if (userExists) {
			res.status(409).json({
				success: false,
				error: "User already exists",
			});
			return;
		}

		const newUser = new User({
			firstname,
			lastname,
			username,
			email,
			password,
			role: Roles.Staff,
		});

		await User.create(newUser);
		//Implicitly apply Roles.Staff for users created in this manner.
		await newUser.save();
		res.status(201).json({
			success: true,
			message: "User registered successfully",
		});
	} catch (error) {
		console.log(error);
		res.status(500).json({ success: false, error: "Registration failed" });
	}
});

//assign user roles
userRouter.post("/roles", async (req: Request, res: Response) => {
	try {
		const { userId, role } = req.body;

		const user = await User.findById(userId);

		if (!user) {
			res.status(404).json({
				success: false,
				error: "Couldn't find requested user",
			});
			return;
		}

		if (!Object.values(Roles).includes(role)) {
			res.status(400).json({
				success: false,
				error: "Invalid user role. User role can only be Administrator, Coordinator, Manager or Staff",
			});
			return;
		}

		user.role = role;
		let filter = { _id: userId };
		await User.updateOne(filter, user);

		res.status(201).json({
			success: true,
			message: `Successfully updated ${user.firstname}'s role to ${user.role}`,
		});
	} catch (error: any) {
		res.status(400).json({
			success: false,
			message: `An error occurred. Reason: ${error.message}`,
		});
	}
});

//change user's blocked status
userRouter.post("/block", async (req: Request, res: Response) => {
	const { userId } = req.body;

	const user = await User.findById(userId);

	if (!user) {
		res.status(404).json({
			success: false,
			error: "Couldn't find requested user",
		});
		return;
	}

	user.blocked = !user.blocked;

	const filter = { _id: userId };
	await User.updateOne(filter, user);

	res.status(200).json({
		success: true,
		message: `Successfully ${
			user.blocked ? "blocked" : "unblocked"
		} user: ${user.firstname}`,
	});
});

//get all users
userRouter.get("/all", async (req: Request, res: Response) => {
	const allUsers = await User.find();

	res.status(200).json({
		success: true,
		users: allUsers,
	});
});

userRouter.get("/emailtest", async (req, res) => {
	console.log("starting");
	const result = await sendEmail(
		["nizasichi@Icloud.com", "dev.niza@icloud.com"],
		"Refactored test",
		"This is a message"
	);
	if (!result.success) {
		res.status(500).json(result);
	}
	res.status(200).json(result);
});

export default userRouter;
