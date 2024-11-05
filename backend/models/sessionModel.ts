import mongoose from "mongoose";

const ObjectId = mongoose.Schema.Types.ObjectId;

const sessionSchema = new mongoose.Schema({
	userId: { type: ObjectId, ref: "User", required: true },
	lastLogin: { type: Date, default: Date.now },
});

export default mongoose.model("Session", sessionSchema);
