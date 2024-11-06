import express, { Request, Response } from "express";
import userRoutes from "./routes/userRoute";

const mainRouter = express.Router(); // Base api route is /api/*

mainRouter.get("/test", (req: Request, res: Response) => {
	res.send("Hello from the API!");
});

mainRouter.use("/user", userRoutes);

export default mainRouter;
