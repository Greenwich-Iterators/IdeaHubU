import jwt from "jsonwebtoken";
import { Types } from "mongoose";

const TOKEN_SECRET = process.env.AUTH_SECRET as string;

export async function generateAccessToken(userId: Types.ObjectId) {
	return jwt.sign({ userId }, TOKEN_SECRET, { expiresIn: "8h" });
}

export function verifyToken(token: string): {
	valid: boolean;
	userId?: string;
} {
	try {
		const decoded = jwt.verify(token, TOKEN_SECRET) as { userId: string };
		return { valid: true, userId: decoded.userId };
	} catch (error) {
		return { valid: false };
	}
}
