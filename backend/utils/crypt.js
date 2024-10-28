import bcrypt from "bcrypt";

const SALTROUNDS = 10;
const myPlaintextPassword = "s0//P4$$w0rD";
const someOtherPlainTextPassword = "cloudy";

const hashAndSalt = async (plainText) => {
	try {
		const hash = await bcrypt.hash(plainText, SALTROUNDS);
		return hash;
	} catch (error) {
		console.error("Error hashing password:", error);
		throw error;
	}

	// RECOMMENDED for security
	// const result = bcrypt.hash(plainText, SALTROUNDS, function (err, hash) {
	// 	return hash; // Returns String
	// });
	return result;
};
export default hashAndSalt;
