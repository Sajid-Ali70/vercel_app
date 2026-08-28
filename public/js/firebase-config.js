// Updated Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyCq7azRgUVG87yM7tx0-3vWXeSfsK9vjDg",
    authDomain: "mobile-store-cce4f.firebaseapp.com",
    projectId: "mobile-store-cce4f",
    storageBucket: "mobile-store-cce4f.appspot.com", // Changed from .firebasestorage.app
    messagingSenderId: "663613970341",
    appId: "1:663613970341:web:82d3da5d5a4b7d44aefa4c",
    measurementId: "G-4L9E4L6BXD"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

const db = firebase.firestore();
const storage = firebase.storage();
const auth = firebase.auth();
const analytics = firebase.analytics ? firebase.analytics() : null;
