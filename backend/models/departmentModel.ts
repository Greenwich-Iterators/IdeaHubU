import mongoose from "mongoose";

const ObjectID = mongoose.Schema.Types.ObjectId;

const departmentSchema = new mongoose.Schema({
	name: { type: String, required: true },
	coordinator: { type: ObjectID, ref: "User", required: true },
});

export default mongoose.model("Department", departmentSchema);
