import express, {Request, Response} from "express";
import Idea from "../models/ideasModel"
import User from "../models/userModel"
import Comment from "../models/commentModel"

const commentRoute = express();

//add a comment
commentRoute.post("/add", async (req: Request, res: Response) => {
	try {
		const {ideaId, userId, content} = req.body;

		if(!content){
			res.status(400)
				.json({
					success:false,
					error: "Comment content cannot be empty"
				})
			return;
		}

		const existingIdea = await Idea.findById(ideaId);
		if(!existingIdea){
			res.status(400)
				.json({
					success: false,
					error: "Couldn't not find any idea matching the request."
				});
			return;
		}

		const existingUser = await User.findById(userId);
		if(!existingUser){
			res.status(400)
				.json({
					success: false,
					error: "Couldn't not find any user matching the request."
				})
			return;
		}

		const newComment = new Comment({
			ideaId: ideaId,
			userId: userId,
			content: content,
			hidden: false
		})

		await Comment.create(newComment);

		res.status(200)
			.json({
				success: true,
				message: "Successfully added comment"
			})

	}
	catch{

	}
})

//edit a comment
commentRoute.put("/edit", async (req: Request, res: Response) => {
	const { commentId, content } = req.body;

	if(!content){
		res.status(400)
			.json({
				success: false,
				error: "Comment cannot be empty."
			})
		return;
	}

	const existingComment = await Comment.findById(commentId);

	if(!existingComment){
		res.status(400)
			.json({
			success: false,
			error: "Couldn't find requested comment."
		})
		return;
	}

	existingComment.content = content;

	await Comment.updateOne({_id: commentId}, existingComment);

	res.status(200)
		.json({
			success: true,
			message: "Successfully edited comment"
		})
})

//delete a comment
commentRoute.delete("/delete", async (req: Request, res: Response) => {
	const { commentID: commentId} = req.body;

	const existingComment = await Comment.findById(commentId);

	if(!existingComment){
		res.status(404).json({
			success: false,
			error: "Couldn't to find requested comment"
		})
		return;
	}

	await Comment.deleteOne({_id: commentId});

	res.status(200)
		.json({
			success: true,
			message: "Successfully deleted comment"
		})
})

//get all comments per idea
commentRoute.get("/idea", async (req: Request, res: Response) => {
	const {ideaId} = req.body;
	const allIdeaComments = await Comment.find({ideaId: ideaId});

	res.status(200).json({
		success: true,
		comments: allIdeaComments
	})
})

export default commentRoute;