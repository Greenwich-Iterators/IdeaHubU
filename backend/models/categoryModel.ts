import mongoose from "mongoose";

const ObjectID = mongoose.Schema.Types.ObjectId;

const categorySchema = new mongoose.Schema({
    name: { type: String, required: true },
    createdAt: { type: Date, default: Date.now },
    updatedAt: { type: Date, default: Date.now },
});

export default mongoose.model("Category", categorySchema);