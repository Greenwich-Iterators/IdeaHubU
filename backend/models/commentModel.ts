import mongoose from "mongoose";

const ObjectID = mongoose.Schema.Types.ObjectId;

const commentSchema = new mongoose.Schema({
    ideaId: { type: ObjectID, ref: "Idea", required: true },
    userId: { type: ObjectID, ref: "User", required: true },
    content: { type: String, required: true },
    createdAt: { type: Date, default: Date.now },
    hidden: { type: Boolean, default: false },
});

export default mongoose.model("Comment", commentSchema);
