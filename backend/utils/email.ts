import nodemailer from "nodemailer";


const ICLOUD_PASSWORD = process.env.ICLOUD_PASSWORD;

const icloud = nodemailer.createTransport({
	host: "smtp.mail.me.com",
	port: 587,
	secure: false,
	auth: {
		user: "ideahubu@icloud.com",
		pass: ICLOUD_PASSWORD,
	},
	tls: {
		ciphers: "SSLv3",
	},
});

export default icloud;
