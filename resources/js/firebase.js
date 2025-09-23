import { initializeApp } from "firebase/app";
import { getAuth, GoogleAuthProvider, signInWithPopup } from "firebase/auth";

const firebaseConfig = {
	apiKey: "AIzaSyCWq1C29yCW7aY1jtS2_I4vT73FpOoo6Yg",
	authDomain: "sistemasdistribuidos-b0727.firebaseapp.com",
	projectId: "sistemasdistribuidos-b0727",
	storageBucket: "sistemasdistribuidos-b0727.appspot.com",
	messagingSenderId: "491589573744",
	appId: "1:491589573744:web:dbf1acd02468766c87e8b5",
	measurementId: "G-F3E124X274"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

async function signInWithGoogle() {
	try {
		const cred = await signInWithPopup(auth, provider);
		const idToken = await cred.user.getIdToken();
		const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
		await fetch('/auth/firebase/callback', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'X-Requested-With': 'XMLHttpRequest',
				'X-CSRF-TOKEN': csrf || ''
			},
			credentials: 'include',
			body: JSON.stringify({ idToken })
		});
		window.location.assign('/');
	} catch (e) {
		console.error('Firebase login error:', e);
		alert('No se pudo iniciar sesión con Google');
	}
}

window.addEventListener('DOMContentLoaded', () => {
	const btn = document.getElementById('btn-google');
	if (btn) {
		btn.addEventListener('click', (e) => {
			e.preventDefault();
			signInWithGoogle();
		});
	}
});
