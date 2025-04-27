document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('switch'); // Bouton toggle switch

    // Fonction pour appliquer le thème
    function applyTheme(theme) {
        if (theme === 'dark') {
            document.body.classList.add('dark-theme');
            document.body.classList.remove('light-theme');
            themeToggle.checked = true; // Synchronise le bouton
        } else {
            document.body.classList.add('light-theme');
            document.body.classList.remove('dark-theme');
            themeToggle.checked = false; // Synchronise le bouton
        }
        localStorage.setItem('theme', theme); // Sauvegarde le thème dans localStorage
    }

    // Charger le thème depuis localStorage ou définir le thème clair par défaut
    const savedTheme = localStorage.getItem('theme') || 'light'; // Par défaut, thème clair
    applyTheme(savedTheme);

    // Écouteur pour basculer le thème
    themeToggle.addEventListener('change', () => {
        const newTheme = themeToggle.checked ? 'dark' : 'light';
        applyTheme(newTheme);
    });
});