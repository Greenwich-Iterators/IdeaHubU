import express, { Request, Response } from "express";
import userRoutes from "./routes/userRoute";
import categoryRoutes from "./routes/categoryRoute"

const mainRouter = express.Router(); // Base api route is /api/*

mainRouter.get("/test", (req: Request, res: Response) => {
	res.send("Hello from the API!");
});

mainRouter.use("/user", userRoutes);

mainRouter.use("/category", categoryRoutes)

export default mainRouter;
