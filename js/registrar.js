document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-registrar');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const nome = form.nome.value.trim();
        const email = form.email.value.trim();
        const senha = form.senha.value;

        if (!nome || !email || !senha) {
            alert('Preencha todos os campos!');
            return;
        }

        try {
            const res = await fetch('backend/registrar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nome, email, senha })
            });

            const data = await res.json();

            if (data.error) {
                alert(data.error);
            } else {
                alert('Registrado com sucesso! Faça login.');
                window.location.href = 'index.html';
            }
        } catch (err) {
            console.error(err);
            alert('Erro ao registrar.');
        }
    });
});
