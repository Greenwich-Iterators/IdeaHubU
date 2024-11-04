import mongoose from "mongoose";
import { hashPassword } from "../utils/crypt";


export enum Roles {
 	Manager = "Manager",
 	Coordinator = "Coordinator",
	Staff = "Staff",
	Administrator = "Administrator"
}

const userSchema = new mongoose.Schema({
	firstName: {type: String, required: true },
	lastName: { type: String, required: true },
	username: { type: String, required: true, unique: true },
	email: { type: String, required: true, unique: true },
	password: { type: String, required: true },
	role: { type: String, enum: Object.values(Roles), required: true }
});

userSchema.pre("save", async function (next) {
	if (this.isModified("password")) {
		this.password = await hashPassword(this.password);
	}
	next();
});

export default mongoose.model("User", userSchema);
