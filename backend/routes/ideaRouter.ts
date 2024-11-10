import express, { Request, Response } from "express";
import { staffCheck } from "../utils/authorization";
import Idea from "../models/ideasModel";
import User from "../models/userModel";

const ideaRouter = express.Router();

ideaRouter.post("/add", async (req: Request, res: Response) => {
	// staffCheck(req, res);

	try {
		const { idea, userId, anonymousPost, categoryId } = req.body;

		if (!idea) {
			res.status(400).json({
				success: false,
				error: "Idea cannot be empty",
			});
			return;
		}
		if (!userId) {
			res.status(400).json({
				success: false,
				error: "No user Id provided",
			});
			return;
		}

		const existingUser = await User.findOne({ _id: userId });

		if (!existingUser) {
			res.status(404).json({
				success: false,
				error: "Couldn't find requested user",
			});
			return;
		}

		if (existingUser.blocked) {
			res.status(403).json({
				success: false,
				error: "User is blocked",
			});
			return;
		}

		const newIdea = new Idea({
			idea: idea,
			userId: userId,
			categoryId: categoryId ?? null,
			anonymousPost: anonymousPost ?? false,
		});

		await Idea.create(newIdea);

		res.status(200).json({
			success: true,
			message: "Successfully added idea",
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			message: `An error occurred. Reason: ${error.message}`,
		});
	}
});

ideaRouter.get("/all", async (req: Request, res: Response) => {
	const allIdeas = await Idea.find()
		.populate("comments")
		.populate("userId")
		.populate("categoryId");

	res.status(200).json({
		success: true,
		ideas: allIdeas,
	});
});

ideaRouter.delete("/delete", async (req: Request, res: Response) => {
	try {
		const { ideaId } = req.body;

		if (!ideaId) {
			res.status(400).json({
				success: false,
				error: "Couldn't find idea",
			});
			return;
		}

		const existingIdea = await Idea.findOne({ _id: ideaId });

		if (!existingIdea) {
			res.status(404).json({
				success: false,
				error: "Couldn't find idea",
			});
			return;
		}

		await Idea.deleteOne({ _id: existingIdea._id });

		res.status(200).json({
			success: true,
			message: "Successfully deleted idea",
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			error: `An error occurred. Reason: ${error.message}`,
		});
	}
});

ideaRouter.put("/edit", async (req: Request, res: Response) => {
	try {
		const { newIdea, ideaId } = req.body;

		if (!newIdea) {
			res.status(400).json({
				success: false,
				error: "Idea cannot be empty",
			});
			return;
		}

		const existingIdea = await Idea.findOne({ _id: ideaId });

		if (!existingIdea) {
			res.status(404).json({
				success: false,
				error: "Couldn't find requested idea",
			});
			return;
		}
		console.log("I have reached");
		await Idea.updateOne({ _id: existingIdea._id }, { idea: newIdea });

		res.status(200).json({
			success: true,
			message: "Edited idea",
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			error: `Couldn't update comment. Reason: ${error.message}`,
		});
	}
});

export default ideaRouter;
