import mongoose from "mongoose";

const userSchema = new mongoose.Schema({
    username: String,
    password: String, // Hashed / Encrypted
    email: String, // Hashed / Encrypted
    role: String // USe typescript enums  
});

const User = mongoose.model("User", userSchema);

export default User;