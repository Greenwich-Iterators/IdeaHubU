import nodemailer from "nodemailer";

const ICLOUD_EMAIL = process.env.ICLOUD_EMAIL;
const ICLOUD_PASSWORD = process.env.ICLOUD_PASSWORD;

const icloud = nodemailer.createTransport({
	host: "smtp.mail.me.com",
	port: 587,
	secure: false,
	auth: {
		user: ICLOUD_EMAIL,
		pass: ICLOUD_PASSWORD,
	},
	tls: {
		ciphers: "SSLv3",
	},
});

export const sendEmail = async (email: string[], subject: string, body: string) => {
	try {
		const info = await icloud.sendMail({
			from: '"IdeaHubU Platform"',
			to: email,
			subject: subject,
			text: body,
		});

		console.log("Message sent:", info.messageId);
		return { success: true, messageId: info.messageId };
	} catch (error) {
		console.error("Error sending email:", error);
		return { success: false, error: error };
	}
};

export default icloud;
