import { NextFunction, Request, Response } from "express";
import { verifyToken } from "./jwt";
import User, { Roles } from "../models/userModel";

export default (req: Request, res: Response) => {
	console.log("Connection Made:", Date.now().toLocaleString());
};

export const staffCheck = (req: Request, res: Response) => {
	console.log("Connection Made:", Date.now().toLocaleString());

	if (req.headers.authorization) {
		const token = req.headers.authorization;
		const user = verifyToken(token);
		console.log(user);
		if (!user.valid) {
			res.status(400).json({
				success: false,
				error: "Invalid User or Token",
			});
			return;
		}

		if (user.user?.role != Roles.Staff) {
			console.log("Staff rejected!");
			res.status(401).json({
				success: false,
				error: "Unauthorised Access",
			});
			return;
		}
		console.log("Staff connected!");
	} else {
		res.status(403).json({ success: false, error: "No credentials sent!" });
		return;
	}
};

export const adminCheck = (req: Request, res: Response) => {
	console.log("Connection Made:", Date.now().toLocaleString());

	if (req.headers.authorization) {
		const token = req.headers.authorization;
		const user = verifyToken(token);
		if (!user.valid) {
			res.status(400).json({
				success: false,
				error: "Invalid User or Token",
			});
			return;
		}

		if (user.user?.role != Roles.Administrator) {
			console.log("Administrator rejected!");
			res.status(401).json({
				success: false,
				error: "Unauthorised Access",
			});
			return;
		}
		console.log("Administrator connected!");
	} else {
		res.status(403).json({ success: false, error: "No credentials sent!" });
		return;
	}
};

export const coordinatorCheck = (req: Request, res: Response) => {
	console.log("Connection Made:", Date.now().toLocaleString());

	if (req.headers.authorization) {
		const token = req.headers.authorization;
		const user = verifyToken(token);
		if (!user.valid) {
			res.status(400).json({
				success: false,
				error: "Invalid User or Token",
			});
		}

		if (user.user?.role != Roles.Coordinator) {
			console.log("Coordinator rejected!");
			res.status(401).json({
				success: false,
				error: "Unauthorised Access",
			});
		}
		console.log("Coordinator connected!");
	} else {
		res.status(403).json({ success: false, error: "No credentials sent!" });
		return;
	}
};

export const managerCheck = (req: Request, res: Response) => {
	console.log("Connection Made:", Date.now().toLocaleString());

	if (req.headers.authorization) {
		const token = req.headers.authorization;
		const user = verifyToken(token);
		if (!user.valid) {
			res.status(400).json({
				success: false,
				error: "Invalid User or Token",
			});
			return;
		}

		if (user.user?.role != Roles.Manager) {
			console.log("Manager rejected!");
			res.status(401).json({
				success: false,
				error: "Unauthorised Access",
			});
			return;
		}
		console.log("Manager connected!");
	} else {
		res.status(403).json({ success: false, error: "No credentials sent!" });
		return;
	}
};
