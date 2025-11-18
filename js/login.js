document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-login');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = form.email.value.trim();
        const senha = form.senha.value;

        if (!email || !senha) {
            alert('Preencha todos os campos!');
            return;
        }

        try {
            const res = await fetch('backend/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, senha })
            });

            const data = await res.json();

            if (data.error) {
                alert(data.error);
            } else {
                window.location.href = 'dashboard.html';
            }
        } catch (err) {
            console.error(err);
            alert('Erro ao fazer login.');
        }
    });
});
