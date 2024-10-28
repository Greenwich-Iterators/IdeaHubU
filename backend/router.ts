import express from "express";
import userRoutes from "./routes/userRoute";

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

export default routeHandler;
