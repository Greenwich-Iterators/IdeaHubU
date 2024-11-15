import express, { Request, Response } from "express";
import userRoutes from "./routes/userRoute";
import categoryRoutes from "./routes/categoryRoute"
import commentRoutes from "./routes/commentRoute"
import departmentRoutes from "./routes/departmentRoute";
import ideaRoutes from "./routes/ideaRouter"

const mainRouter = express.Router(); // Base api route is /api/*

mainRouter.get("/test", (req: Request, res: Response) => {
	res.send("Hello from the API!");
});

mainRouter.use("/user", userRoutes);

mainRouter.use("/category", categoryRoutes)

mainRouter.use("/comment", commentRoutes);

mainRouter.use("/department", departmentRoutes)

mainRouter.use("/idea", ideaRoutes);

export default mainRouter;
