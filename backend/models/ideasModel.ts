import mongoose from "mongoose";

const ObjectId = mongoose.Schema.Types.ObjectId;

const ideaSchema = new mongoose.Schema({
	idea: { type: String, required: true },
	userId: { type: ObjectId, ref: "User", required: true },
	createdAt: { type: Date, default: Date.now },
	updatedAt: { type: Date, default: Date.now },
	comments: { type: [ObjectId], ref: "Comment", default: null },
	anonymousPost: { type: Boolean, default: false },
	categoryId: { type: ObjectId, ref: "Category" },
	userLikes: { type: [ObjectId], ref: "User" },
	userDislikes: { type: [ObjectId], ref: "User" },
	hidden: { type: Boolean, default: false },
});

export default mongoose.model("Idea", ideaSchema);
