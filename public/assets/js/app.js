// document.addEventListener("DOMContentLoaded", function() {
//     const loginForm = document.getElementById('login-form');
//     const loginError = document.getElementById('login-error');
//     const userSection = document.getElementById('user-section');
//     const loginSection = document.getElementById('login-section');
//     const userName = document.getElementById('user-name');
//     const logoutBtn = document.getElementById('logout-btn');
//
//     // Vérifie si l'utilisateur est déjà connecté
//     const storedUser = localStorage.getItem('user');
//     if (storedUser) {
//         showUserSection(JSON.parse(storedUser));
//     }
//
//     // Gérer la soumission du formulaire de connexion
//     loginForm.addEventListener('submit', function(event) {
//         event.preventDefault();  // Chargement de la page asynchrone
//
//         const email = document.getElementById('email').value;
//         const password = document.getElementById('password').value;
//
//         // Appel Fetch pour faire une requête POST au backend
//         fetch('/connexion', {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json' },
//             body: JSON.stringify({ user: email, password: password })
//         })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.status === 'success') {
//                     localStorage.setItem('user', JSON.stringify(data.user));
//                     showUserSection(data.user);
//                 } else {
//                     loginError.textContent = data.error || "Erreur lors de la connexion.";
//                 }
//             })
//             .catch(err => {
//                 loginError.textContent = "Une erreur s'est produite.";
//             });
//     });
//
//     // Affiche la section utilisateur après la connexion
//     function showUserSection(user) {
//         loginSection.style.display = 'none';
//         userSection.style.display = 'block';
//         userName.textContent = user.firstname + " " + user.lastname;
//     }
//
//     // Gérer la déconnexion
//     logoutBtn.addEventListener('click', function() {
//         localStorage.removeItem('user');
//         loginSection.style.display = 'block';
//         userSection.style.display = 'none';
//     });
// });


document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const loginError = document.getElementById('login-error');
    const registerError = document.getElementById('register-error');
    const loginSection = document.getElementById('login-section');
    const registerSection = document.getElementById('register-section');
    const userSection = document.getElementById('user-section');
    const userName = document.getElementById('user-name');
    const logoutBtn = document.getElementById('logout-btn');

    // Liens pour basculer entre connexion et inscription
    const goToRegister = document.getElementById('go-to-register');
    const goToLogin = document.getElementById('go-to-login');

    // Vérifie si l'utilisateur est déjà connecté
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
        showUserSection(JSON.parse(storedUser));
    }

    // Bascule vers le formulaire d'inscription
    goToRegister.addEventListener('click', function(event) {
        event.preventDefault();
        loginSection.style.display = 'none';
        registerSection.style.display = 'block';
    });

    // Bascule vers le formulaire de connexion
    goToLogin.addEventListener('click', function(event) {
        event.preventDefault();
        registerSection.style.display = 'none';
        loginSection.style.display = 'block';
    });

    // Gérer la soumission du formulaire de connexion
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Appel Fetch pour faire une requête POST au backend (connexion)
        fetch('/connexion', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user: email, password: password })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    localStorage.setItem('user', JSON.stringify(data.user));
                    showUserSection(data.user);
                } else {
                    loginError.textContent = data.error || "Erreur lors de la connexion.";
                }
            })
            .catch(err => {
                loginError.textContent = "Une erreur s'est produite.";
            });
    });

    // Gérer la soumission du formulaire d'inscription
    registerForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const firstname = document.getElementById('firstname').value;
        const lastname = document.getElementById('lastname').value;
        const email = document.getElementById('register-email').value;
        const password = document.getElementById('register-password').value;

        // Appel Fetch pour faire une requête POST au backend (inscription)
        fetch('/inscription', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ firstname, lastname, email, password })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'User registered successfully') {
                    // Basculer vers le formulaire de connexion après l'inscription
                    alert('Inscription réussie ! Vous pouvez maintenant vous connecter.');
                    registerSection.style.display = 'none';
                    loginSection.style.display = 'block';
                } else {
                    registerError.textContent = data.error || "Erreur lors de l'inscription.";
                }
            })
            .catch(err => {
                registerError.textContent = "Une erreur s'est produite.";
            });
    });

    // Affiche la section utilisateur après la connexion
    function showUserSection(user) {
        loginSection.style.display = 'none';
        registerSection.style.display = 'none';
        userSection.style.display = 'block';
        userName.textContent = user.firstname + " " + user.lastname;
    }

    // Gérer la déconnexion
    logoutBtn.addEventListener('click', function() {
        localStorage.removeItem('user');
        loginSection.style.display = 'block';
        userSection.style.display = 'none';
    });
});
