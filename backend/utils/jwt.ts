// To be implemented

import jwt from "jsonwebtoken";
import { Types } from "mongoose";

const TOKEN_SECRET = process.env.AUTH_SECRET as string;

export async function generateAccessToken(userId: Types.ObjectId) {
    return jwt.sign({ userId }, TOKEN_SECRET, { expiresIn: '8h' });
}
