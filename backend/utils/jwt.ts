// To be implemented

import jwt from "jsonwebtoken";

const TOKEN_SECRET = process.env.AUTH_SECRET as string;

export async function generateAccessToken(username: string) {
    return jwt.sign({ username }, TOKEN_SECRET, { expiresIn: '8h' });
}
