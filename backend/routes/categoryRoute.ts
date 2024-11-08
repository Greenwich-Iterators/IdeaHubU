import express, { Request, Response } from "express";
import Category from "../models/categoryModel";
import Idea from "../models/ideasModel";
import { staffCheck } from "../utils/authorization";

const categoryRouter = express.Router();

categoryRouter.post("/add", async (req: Request, res: Response) => {
	staffCheck(req, res);
	try {
		const { name } = req.body;

		const existingName = await Category.find({ name: name.toLowerCase() });

		if (!name) {
			res.status(403).json({
				success: false,
				error: "No category name provided",
			});
			return;
		}
		if (existingName.length > 0) {
			res.status(409).json({
				success: false,
				error: "Category name already exists",
			});
			return;
		}

		const newCategory = new Category({ name });

		await Category.create(newCategory);

		res.status(201).json({
			success: true,
			message: `${newCategory.name} Category added successfully`,
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			error: `Failed to add category. Reason: ${error.message}`,
		});
	}
});

categoryRouter.get("/all", async (req: Request, res: Response) => {
	const allCategories = await Category.find();

	res.status(200).json({
		success: true,
		categories: allCategories,
	});
});

categoryRouter.delete("/delete", async (req: Request, res: Response) => {
	try {
		const { categoryId } = req.body;

		const categoryFilter = { _id: categoryId };
		const ideaFilter = { categoryId: categoryId };

		const category = await Category.findOne(categoryFilter);

		if (!category) {
			res.status(404).json({
				success: false,
				error: "Couldn't find category",
			});
			return;
		}

		const existingIdeas = await Idea.find(ideaFilter);

		if (existingIdeas.length > 0) {
			res.status(400).json({
				success: false,
				error: "Couldn't not delete category. Existing Ideas with this category would be lost",
			});
			return;
		}

		await Category.deleteOne(categoryFilter);

		res.status(200).json({
			success: true,
			error: `Successfully delete ${category.name}`,
		});
	} catch {}
});

export default categoryRouter;
