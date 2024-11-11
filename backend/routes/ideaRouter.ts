import express, { Request, Response } from "express";
import { staffCheck } from "../utils/authorization";
import Idea from "../models/ideasModel";
import User from "../models/userModel";
import Report from "../models/reportModel";
import Department from "../models/departmentModel";
import Comment from "../models/commentModel";
import { sendEmail } from "../utils/email";

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

		// Send Email to Coordinator
		const department = await Department.findOne({
			_id: existingUser.departmentId,
		});
		if (department?.coordinator) {
			const coordinator = await User.findOne({
				_id: department.coordinator,
			});
			if (coordinator?.email) {
				const sentEmail = await sendEmail(
					[coordinator.email],
					"New Idea Posted",
					idea
				);
				console.log(sentEmail);
			}
		}

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

ideaRouter.get("/departmentIdeas", async (req, res) => {
	const { departmentId } = req.body;
	const allIdeas = await Idea.find();

	const departmentIdeas = await Promise.all(
		allIdeas.map(async (idea) => {
			const user = await User.findOne({ _id: idea.userId });
			if (
				user?.departmentId &&
				user.departmentId.toString() === departmentId.toString()
			) {
				return idea;
			}
			return null;
		})
	);

	const filteredDepartmentIdeas = departmentIdeas.filter(
		(idea) => idea !== null
	);

	res.status(200).json({
		success: true,
		departmentIdeas: filteredDepartmentIdeas,
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

ideaRouter.post("/report", async (req, res) => {
	const { ideaId, reporterId, reason } = req.body;

	const existingIdea = await Idea.findOne({ _id: ideaId });
	const existingReporter = await User.findOne({ _id: reporterId });
	if (!existingIdea) {
		res.status(404).json({
			success: false,
			error: "Couldn't find idea",
		});
		return;
	}

	if (!existingReporter) {
		res.status(404).json({
			success: false,
			error: "Couldn't find User",
		});
	}

	const report = new Report({
		ideaId: ideaId,
		reporterId: reporterId,
		reason: reason ?? null,
	});

	await Idea.create(report);

	res.status(201).json({
		success: true,
		message: "Successfully reported the post",
	});
});

ideaRouter.get("/reported", async (req, res) => {
	const reportedIdeas = await Report.find()
		.populate("reporterId")
		.populate("ideaId");

	res.status(200).json({
		success: true,
		message: "List of reported ideas",
		reportedIdeas: reportedIdeas,
	});
});

ideaRouter.get("/contributors", async (req, res) => {});

ideaRouter.post("/like", async (req, res) => {
	try {
		const { ideaId, userId } = req.body;

		if (!ideaId || !userId) {
			res.status(400).json({
				success: false,
				error: "Missing ideaId or userId",
			});
			return;
		}

		const existingIdea = await Idea.findById(ideaId);

		if (!existingIdea) {
			res.status(404).json({
				success: false,
				error: "Idea not found",
			});
			return;
		}

		// Check if user has already liked the idea
		if (existingIdea.userLikes.includes(userId)) {
			res.status(400).json({
				success: false,
				error: "User has already liked this idea",
			});
			return;
		}

		// Add user to likes and remove from dislikes if present
		const updated = await Idea.findByIdAndUpdate(
			ideaId,
			{
				$addToSet: { userLikes: userId },
				$pull: { userDislikes: userId },
			},
			{ new: true }
		);

		res.status(200).json({
			success: true,
			message: "Idea liked successfully",
			updatedIdea: updated,
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			error: `An error occurred. Reason: ${error.message}`,
		});
	}
});

ideaRouter.post("/dislike", async (req, res) => {
	try {
		const { ideaId, userId } = req.body;

		if (!ideaId || !userId) {
			res.status(400).json({
				success: false,
				error: "Missing ideaId or userId",
			});
			return;
		}

		const existingIdea = await Idea.findById(ideaId);

		if (!existingIdea) {
			res.status(404).json({
				success: false,
				error: "Idea not found",
			});
			return;
		}

		// Check if user has already disliked the idea
		if (existingIdea.userDislikes.includes(userId)) {
			res.status(400).json({
				success: false,
				error: "User has already disliked this idea",
			});
			return;
		}

		// Add user to dislikes and remove from likes if present
		const updated = await Idea.findByIdAndUpdate(
			ideaId,
			{
				$addToSet: { userDislikes: userId },
				$pull: { userLikes: userId },
			},
			{ new: true }
		);

		res.status(200).json({
			success: true,
			message: "Idea disliked successfully",
			updatedIdea: updated,
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			error: `An error occurred. Reason: ${error.message}`,
		});
	}
});

ideaRouter.get("/popular", async (req: Request, res: Response) => {
	try {
		const popularIdeas = await Idea.find()
			.sort({ "userLikes.length": -1 })
			.limit(5)
			.populate("userId", "username")
			.populate("categoryId", "name");

		res.status(200).json({
			success: true,
			popularIdeas: popularIdeas,
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			error: `An error occurred. Reason: ${error.message}`,
		});
	}
});

ideaRouter.get("/mostViewed", async (req: Request, res: Response) => {
	try {
		const mostViewedIdeas = await Idea.find()
			.sort({ views: -1 })
			.limit(5)
			.populate("userId", "username")
			.populate("categoryId", "name");

		res.status(200).json({
			success: true,
			mostViewedIdeas: mostViewedIdeas,
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			error: `An error occurred. Reason: ${error.message}`,
		});
	}
});

ideaRouter.get("/latest", async (req: Request, res: Response) => {
	try {
		const latestIdeas = await Idea.find()
			.sort({ createdAt: -1 })
			.limit(5)
			.populate("userId", "username")
			.populate("categoryId", "name");

		res.status(200).json({
			success: true,
			latestIdeas: latestIdeas,
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			error: `An error occurred. Reason: ${error.message}`,
		});
	}
});

// Get latest comments
ideaRouter.get("/latestComments", async (req: Request, res: Response) => {
	try {
		const latestComments = await Comment.find()
			.sort({ createdAt: -1 })
			.limit(5)
			.populate("userId", "username")
			.populate("ideaId", "idea");

		res.status(200).json({
			success: true,
			latestComments: latestComments,
		});
	} catch (error: any) {
		res.status(500).json({
			success: false,
			error: `An error occurred. Reason: ${error.message}`,
		});
	}
});

export default ideaRouter;
