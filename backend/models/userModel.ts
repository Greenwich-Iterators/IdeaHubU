import mongoose from "mongoose";
import { hashPassword } from "../utils/crypt";

const ObjectID = mongoose.Schema.Types.ObjectId;
export enum Roles {
	Manager = "Manager",
	Coordinator = "Coordinator",
	Staff = "Staff",
	Administrator = "Administrator",
}

// Whenever you change userSchema, change the verifyToken function
const userSchema = new mongoose.Schema({
	firstname: { type: String, required: true },
	lastname: { type: String, required: true },
	username: { type: String, required: true, unique: true },
	email: { type: String, required: true, unique: true },
	password: { type: String, required: true },
	role: { type: String, enum: Object.values(Roles), required: true },
	departmentId: { type: ObjectID, ref: "Department" },
	blocked: { type: Boolean, default: false },
});

userSchema.pre("save", async function (next) {
	if (this.isModified("password")) {
		console.log("New User Created: ", this);
		this.password = await hashPassword(this.password);
	}
	next();
});

export default mongoose.model("User", userSchema);
