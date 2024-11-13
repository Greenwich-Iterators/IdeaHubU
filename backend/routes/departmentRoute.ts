import express, { Request, Response } from "express";
import Departments from "../models/departmentModel";

const departmentRouter = express.Router();

departmentRouter.get("/get", async (req: Request, res: Response) => {
	try {
		const allDepartments = await Departments.find();

		res.json({
			success: true,
			departments: allDepartments,
		});
	} catch (error) {
		res.status(500).json({
			success: false,
			error: `An error occurred ${error}`,
		});
	}
});

departmentRouter.get("/name", async (req: Request, res: Response) => {
	const { departmentId } = req.body;
	try {
		const dept = await Departments.findOne({ _id: departmentId });
		console.log(dept);
		res.status(200).json({
			success: true,
			departmentName: dept?.name,
		});
	} catch (error) {
		res.status(500).json({
			success: false,
			error: `An error occurred ${error}`,
		});
	}
});

departmentRouter.post("/create", async (req: Request, res: Response) => {
	// Save new department in the database
	const { departmentname } = req.body;
	const newdept = await Departments.create({
		name: departmentname,
	});

	const saveddept = await newdept.save();
	res.status(200).json(saveddept);
});
departmentRouter.post("/seed", async (req: Request, res: Response) => {
	// try{
	// 	const newDepartments = [
	// 		{
	//
	// 		}
	// 	]
	// }
});

export default departmentRouter;
