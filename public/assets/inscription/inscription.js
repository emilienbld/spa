document.addEventListener("DOMContentLoaded", function() {
    const registerForm = document.getElementById('register-form');
    const registerError = document.getElementById('register-error');

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
                    // Rediriger vers la page de connexion ou afficher un message de succès
                    alert("Inscription réussie! Vous pouvez maintenant vous connecter.");
                    window.location.href = '/assets/connexion/connexion.html'; // Rediriger vers la page de connexion
                } else {
                    registerError.textContent = data.error || "Erreur lors de l'inscription.";
                }
            })
            .catch(err => {
                registerError.textContent = "Une erreur s'est produite.";
            });
    });
});
