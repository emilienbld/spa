document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById('login-form');
    const loginError = document.getElementById('login-error');
    const userSection = document.getElementById('user-section');
    const userName = document.getElementById('user-name');
    const logoutBtn = document.getElementById('logout-btn');

    // Vérifie si l'utilisateur est déjà connecté
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
        showUserSection(JSON.parse(storedUser));
    }

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

    // Affiche la section utilisateur après la connexion
    function showUserSection(user) {
        loginForm.parentElement.style.display = 'none';
        userSection.style.display = 'block';
        userName.textContent = user.firstname + " " + user.lastname;
    }

    // Gérer la déconnexion
    logoutBtn.addEventListener('click', function() {
        localStorage.removeItem('user');
        loginForm.parentElement.style.display = 'block';
        userSection.style.display = 'none';
    });
});
