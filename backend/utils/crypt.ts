import bcrypt from "bcrypt";

const SALTROUNDS = 10;

const hashAndSalt = async (plainPassword: string): Promise<string> => {
	try {
		const hash = await bcrypt.hash(plainPassword, SALTROUNDS);
		return hash;
	} catch (error) {
		console.error("Error hashing password:", error);
		throw error;
	}
};

const veryfyHash = async (
	plainPassword: string,
	hashedPassword: string
): Promise<boolean> => {
	try {
		const match = await bcrypt.compare(plainPassword, hashedPassword);
		return match;
	} catch (error) {
		console.error("Error verifying password:", error);
		throw error;
	}
};
export default { hashAndSalt, veryfyHash };
