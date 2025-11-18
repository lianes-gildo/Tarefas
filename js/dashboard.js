document.addEventListener("DOMContentLoaded", () => {
    const lista = document.getElementById("lista-tarefas");
    const template = document.getElementById("template-tarefa");
    const formTarefa = document.getElementById("form-tarefa");
    const usuarioNome = document.getElementById("usuario-nome");
    const logoutBtn = document.getElementById("logout");

    // ---------------- LOGOUT ----------------
    logoutBtn.addEventListener("click", () => {
        window.location.href = "index.html";
    });

    // ---------------- CARREGAR USUÁRIO ----------------
    fetch("backend/login.php", { method: "GET" }) // apenas para pegar o nome do usuário logado
        .then(res => res.json())
        .then(data => {
            if (data.nome) usuarioNome.textContent = `Bem-vindo, ${data.nome}`;
        });

    // ---------------- CARREGAR TAREFAS ----------------
    const carregarTarefas = () => {
        fetch("backend/listar.php")
            .then(res => res.json())
            .then(tarefas => {
                lista.innerHTML = "";
                tarefas.forEach(tarefa => {
                    const clone = template.content.cloneNode(true);
                    const li = clone.querySelector("li");
                    li.dataset.id = tarefa.id;

                    const titulo = clone.querySelector(".titulo");
                    const descricao = clone.querySelector(".descricao");
                    const criadoEm = clone.querySelector(".criado-em");

                    titulo.textContent = tarefa.titulo;
                    descricao.textContent = tarefa.descricao;
                    criadoEm.textContent = `Criado em: ${tarefa.criado_em}`;

                    if (tarefa.concluida == 1) li.classList.add("concluida");

                    // ---------------- AÇÕES ----------------
                    const btnConcluir = clone.querySelector(".concluir");
                    const btnEditar = clone.querySelector(".editar");
                    const btnApagar = clone.querySelector(".apagar");

                    // Concluir / Desconcluir
                    btnConcluir.addEventListener("click", () => {
                        fetch(`backend/concluir.php?id=${tarefa.id}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.concluida != null) {
                                    li.classList.toggle("concluida", data.concluida == 1);
                                }
                            });
                    });

                    // Editar
                    btnEditar.addEventListener("click", () => {
                        const novoTitulo = prompt("Editar título", tarefa.titulo);
                        if (novoTitulo === null || novoTitulo.trim() === "") return;
                        const novaDesc = prompt("Editar descrição", tarefa.descricao);
                        fetch("backend/editar.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ id: tarefa.id, titulo: novoTitulo, descricao: novaDesc })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === "ok") {
                                    titulo.textContent = novoTitulo;
                                    descricao.textContent = novaDesc;
                                } else {
                                    alert("Erro ao editar: " + (data.error || ""));
                                }
                            });
                    });

                    // Apagar
                    btnApagar.addEventListener("click", () => {
                        if (!confirm("Tem certeza que deseja apagar esta tarefa?")) return;
                        fetch(`backend/apagar.php?id=${tarefa.id}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === "ok") {
                                    li.remove();
                                } else {
                                    alert("Erro ao apagar: " + (data.error || ""));
                                }
                            });
                    });

                    lista.appendChild(clone);
                });
            });
    };

    carregarTarefas();

    // ---------------- ADICIONAR NOVA TAREFA ----------------
    formTarefa.addEventListener("submit", e => {
        e.preventDefault();
        const formData = new FormData(formTarefa);
        const titulo = formData.get("titulo").trim();
        const descricao = formData.get("descricao").trim();

        if (!titulo) return alert("O título é obrigatório.");

        fetch("backend/criar.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ titulo, descricao })
        })
            .then(res => res.json())
            .then(tarefa => {
                if (tarefa.id) {
                    carregarTarefas();
                    formTarefa.reset();
                } else {
                    alert("Erro ao criar tarefa.");
                }
            });
    });
});
