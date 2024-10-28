import express from "express";
import userRoutes from "./routes/userRoute";
import { createRouteHandler } from "uploadthing/express";
import { createUploadthing, type FileRouter } from "uploadthing/express";

const routeHandler = express.Router();

routeHandler.get("/test", (req, res) => {
	res.send("Hello from the API!");
});

routeHandler.get("/json_test", (req, res) => {
	const data = {
		name: "John Doe",
		age: 30,
		city: "New York",
	};
	res.json(data);
});

routeHandler.use("/user", userRoutes);

// UploadThing
const f = createUploadthing();
const uploadRouter = {
	imageUploader: f({
		image: {
			maxFileSize: "4MB",
			maxFileCount: 4,
		},
	}).onUploadComplete((data) => {
		console.log("upload completed", data);
	}),
} satisfies FileRouter;

routeHandler.use(
	"/uploadthing",
	createRouteHandler({
		router: uploadRouter,
	})
);

export default routeHandler;
