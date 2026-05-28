const API = "/alexandre/catalogo_futebol/Back/";

let idParaDeletar = null;

async function carregarJogadores() {
  const busca   = document.getElementById("busca").value;
  const posicao = document.getElementById("filtroPosicao").value;
  const grid    = document.getElementById("grid");

  grid.innerHTML = '<p class="carregando">Carregando...</p>';

  try {
    const params = new URLSearchParams({ busca, posicao });
    const res    = await fetch(`${API}listar.php?${params}`);
    const dados  = await res.json();

    if (!dados.length) {
      grid.innerHTML = `
        <a href="cadastrar.html" class="card card-add">
          <div class="card-add-inner">
            <div class="card-add-circulo">+</div>
            <span class="card-add-label">Cadastrar Jogador</span>
          </div>
        </a>
        <p class="vazio" style="grid-column:2/-1">Nenhum jogador encontrado.</p>
      `;
      return;
    }

    const cardCadastrar = `
      <a href="cadastrar.html" class="card card-add" style="animation-delay:0s">
        <div class="card-add-inner">
          <div class="card-add-circulo">+</div>
          <span class="card-add-label">Cadastrar Jogador</span>
        </div>
      </a>
    `;

    grid.innerHTML = cardCadastrar + dados.map((j, i) => `
      <div class="card" style="animation-delay:${(i + 1) * 0.06}s">
        ${j.foto
          ? `<img class="card-foto" src="${j.foto}" alt="${j.nome}" onerror="this.outerHTML='<div class=\\'card-foto-placeholder\\'>⚽</div>'">`
          : `<div class="card-foto-placeholder">⚽</div>`}
        <div class="card-body">
          ${j.numero ? `<span class="card-numero">#${j.numero}</span>` : ""}
          <div class="card-nome">${j.nome}</div>
          <div class="card-posicao">${j.posicao}</div>
          <div class="card-info">
            🏟️ <span>${j.clube}</span><br>
            🌍 <span>${j.pais || "—"}</span><br>
            🎂 <span>${j.idade ? j.idade + " anos" : "—"}</span>
          </div>
        </div>
        <div class="card-acoes">
          <button class="btn-editar" onclick="irParaEditar(${j.id})">✏️ Editar</button>
          <button class="btn-deletar" onclick="confirmarDeletar(${j.id}, '${j.nome}')">🗑️ Deletar</button>
        </div>
      </div>
    `).join("");
  } catch (e) {
    grid.innerHTML = '<p class="vazio">Erro ao conectar com o servidor.</p>';
  }
}

function irParaEditar(id) {
  window.location.href = `atualizar.html?id=${id}`;
}

function confirmarDeletar(id, nome) {
  idParaDeletar = id;
  document.querySelector(".modal h3").textContent = "Deletar Jogador";
  document.querySelector(".modal p").textContent  = `Tem certeza que deseja deletar "${nome}"? Esta ação não pode ser desfeita.`;
  document.querySelector(".modal-overlay").classList.add("ativo");
}

async function executarDeletar() {
  if (!idParaDeletar) return;
  const form = new FormData();
  form.append("id", idParaDeletar);

  const res  = await fetch(`${API}deletar.php`, { method: "POST", body: form });
  const data = await res.json();

  fecharModal();
  if (data.sucesso) {
    carregarJogadores();
  } else {
    alert("Erro: " + data.erro);
  }
}

function fecharModal() {
  idParaDeletar = null;
  document.querySelector(".modal-overlay").classList.remove("ativo");
}

function alternarTema() {
  const grid  = document.getElementById("grid");
  const btn   = document.getElementById("btnTema");
  const escuro = grid.classList.toggle("modo-escuro");
  btn.textContent = escuro ? "☀️ Claro" : "🌙 Escuro";
  btn.classList.toggle("modo-claro", !escuro);
}

document.addEventListener("DOMContentLoaded", () => {
  carregarJogadores();

  let timer;
  document.getElementById("busca").addEventListener("input", () => {
    clearTimeout(timer);
    timer = setTimeout(carregarJogadores, 400);
  });

  document.body.insertAdjacentHTML("beforeend", `
    <div class="modal-overlay" onclick="fecharModal()">
      <div class="modal" onclick="event.stopPropagation()">
        <h3>Confirmar</h3>
        <p></p>
        <div class="modal-btns">
          <button class="btn-cancelar" onclick="fecharModal()">Cancelar</button>
          <button class="btn-confirmar" onclick="executarDeletar()">Deletar</button>
        </div>
      </div>
    </div>
  `);
}
);