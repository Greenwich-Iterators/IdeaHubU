import { createUploadthing, type FileRouter } from "uploadthing/express";

const f = createUploadthing();

export const uploadRouter = {
	fileUploader: f({
		pdf: {
			maxFileSize: "4MB",
			maxFileCount: 4,
		},
	}).onUploadComplete((data) => {
		console.log("upload completed", data);
	}),
} satisfies FileRouter;

export type OurFileRouter = typeof uploadRouter;
