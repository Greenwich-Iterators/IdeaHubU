import mongoose, { Schema } from "mongoose";

const academicYearSchema = new Schema(
	{
		startDate: {
			type: Date,
			required: true,
		},
		endDate: {
			type: Date,
			required: true,
		},
		isActive: {
			type: Boolean,
			default: true,
		},
	},
	{ timestamps: true }
);

export default mongoose.model("AcademicYear", academicYearSchema);
