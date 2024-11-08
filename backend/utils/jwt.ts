import jwt from "jsonwebtoken";
import { Types } from "mongoose";
import User, { Roles } from "../models/userModel";

const TOKEN_SECRET = process.env.AUTH_SECRET as string;

export async function generateAccessToken(user: {
	firstname: string;
	lastname: string;
	username: string;
	email: string;
	password: string;
	role: Roles;
	blocked: boolean;
	departmentId?: Types.ObjectId | null | undefined;
}) {
	return jwt.sign({ user }, TOKEN_SECRET, { expiresIn: "8h" });
}

// Whenever you change userModel, change the verifyToken function
export function verifyToken(token: string): {
	valid: boolean;
	user?: {
		firstname: string;
		lastname: string;
		username: string;
		email: string;
		password: string;
		role: Roles;
		blocked: boolean;
		departmentId?: Types.ObjectId | null | undefined;
	};
} {
	try {
		const decodedUser = jwt.verify(token, TOKEN_SECRET) as {
			user: {
				firstname: string;
				lastname: string;
				username: string;
				email: string;
				password: string;
				role: Roles;
				blocked: boolean;
				departmentId?: Types.ObjectId | null | undefined;
			};
		};
		return { valid: true, user: decodedUser.user };
	} catch (error) {
		return { valid: false };
	}
}
