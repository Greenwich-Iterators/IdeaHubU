import express, { Request, Response } from "express";
import hashAndSalt from "../utils/crypt";

const userRoutes = express.Router();
// All routes bellow are /api/user/*
userRoutes.get("/", (req: Request, res: Response) => {
	res.send("Hello from /api/user nested api.");
});

// How to use the hash function (TEST iN DEV NOT PROD!!)
// userRoutes.get("/hashpass", async (req: Request, res: Response) => {
// 	const plainPass = "test";
// 	const result = await hashAndSalt(plainPass);
// 	console.log(result);
// 	res.send({ hashedPass: result });
// });

export default userRoutes;
