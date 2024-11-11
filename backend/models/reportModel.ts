import mongoose from "mongoose";

const ObjectId = mongoose.Schema.Types.ObjectId;

const reportSchema = new mongoose.Schema({
    ideaId: {type: ObjectId, ref: "Idea", required: true },
    reporterId: {type: ObjectId, ref: "User", required: true },
    reason: { type: String, required: false },
    createdAt: { type: Date, default: Date.now },

})

export default mongoose.model("Report", reportSchema);
