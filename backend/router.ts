import express, { Request, Response } from "express";
import userRoutes from "./routes/userRoute";
import { createRouteHandler } from "uploadthing/express";
import { uploadRouter } from "./utils/uploadthing";

const mainRouter = express.Router(); // Base api route is /api/*

mainRouter.get("/test", (req: Request, res: Response) => {
	res.send("Hello from the API!");
});

mainRouter.use("/user", userRoutes);

// UploadThing
mainRouter.use(
	"/uploadthing",
	createRouteHandler({
		router: uploadRouter,
	})
);

export default mainRouter;
