import { initializeApp } from "firebase/app";
import { getAuth, GoogleAuthProvider, FacebookAuthProvider } from "firebase/auth";

const firebaseConfig = {
  apiKey: "AIzaSyC61int_RB5WhA-pxEyV6pjZOZmiS1W2q8",
  authDomain: "funiro-5375b.firebaseapp.com",
  projectId: "funiro-5375b",
  storageBucket: "funiro-5375b.firebasestorage.app",
  messagingSenderId: "295110469125",
  appId: "1:295110469125:web:308588ad43e2a924435e6c",
  measurementId: "G-465TVWESLN"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

const googleProvider = new GoogleAuthProvider();
const facebookProvider = new FacebookAuthProvider();

export { auth, googleProvider, facebookProvider };
