import express, { Request, Response } from "express";
import Department from "../models/departmentModel";

const departmentRouter = express.Router();

departmentRouter.get("/get", async (req: Request, res: Response) => {
	try {
		const allDepartments = await Department.find();

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

departmentRouter.get("/getone", async (req: Request, res: Response) => {
	const { departmentId } = req.body;
	try {
		res.status(200).json({
			success: true,
			department: await Department.findById(departmentId),
		});
	} catch (error) {
		res.status(500).json({
			success: false,
			error: `An error occurred ${error}`,
		});
	}
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
