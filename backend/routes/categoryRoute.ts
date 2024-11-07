import express, { Request, Response } from "express";
import Category from "../models/categoryModel";

const categoryRouter = express.Router();

categoryRouter.post("/add", async (req: Request, res: Response) => {
	try{
		const{ name } = req.body;

		const existingName = await Category.find({name: name.toLowerCase() });

		if(!name){
			res.status(403).json({
				success: false,
				error: "No category name provided"
			})
			return
		}
		if(existingName.length>0){
			res.status(409).json({
				success: false,
				error: "Category name already exists"
			})
			return;
		}

		const newCategory = new Category({name});

		await Category.create(newCategory);

		res.status(201).json({
			success: true,
			message: `${newCategory.name} Category added successfully`
		})

	}
	catch (error: any){
		res.status(500).json({
			success: false,
			error: `Failed to add category. Reason: ${error.message}`
		})
	}
})

categoryRouter.get("/all", async (req: Request, res: Response) => {
	const allCategories = await Category.find();

	res.status(200).json({
		success: true,
		categories: allCategories
	});
})

export default  categoryRouter;