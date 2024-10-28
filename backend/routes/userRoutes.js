import express from "express";
// import hashAndSalt from "../utils/crypt.js";

const userRoutes = express.Router();

userRoutes.get("/", (req, res) => {
	res.send("Hello from user nested api.");
});

// How to use the hash function (TEST iN DEV NOT PROD!!)
// userRoutes.get("/hashpass", async (req, res) => {
// 	const plainPass = "test";
// 	const result = await hashAndSalt(plainPass);
// 	console.log(result);
// 	res.send({ hashedPass: result });
// });

export default userRoutes;
