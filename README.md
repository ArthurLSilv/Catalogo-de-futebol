# ⚽ FutCatálogo

Catálogo de jogadores de futebol com cadastro, listagem, edição e exclusão. Interface web com PHP + MySQL no back-end.

---

## Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx) — recomendado: **XAMPP** ou **WAMP**

---

## Como rodar

### 1. Clone o repositório

```bash
git clone https://github.com/ArthurLSilv/Cat-logo-de-futebol.git
```

Coloque a pasta dentro do diretório público do seu servidor:

- **XAMPP (Windows):** `C:/xampp/htdocs/`
- **WAMP (Windows):** `C:/wamp64/www/`
- **Linux/Apache:** `/var/www/html/`

---

### 2. Crie o banco de dados

Acesse o **phpMyAdmin** (ou seu cliente MySQL) e execute:

```sql
CREATE DATABASE catalogo_futebol CHARACTER SET utf8 COLLATE utf8_general_ci;

USE catalogo_futebol;

CREATE TABLE jogadores (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  nome    VARCHAR(100) NOT NULL,
  posicao VARCHAR(50)  NOT NULL,
  clube   VARCHAR(100) NOT NULL,
  pais    VARCHAR(100),
  idade   INT,
  numero  INT,
  foto    VARCHAR(500)
);
```

---

### 3. Configure a conexão

Dentro da pasta `Back/`, copie o arquivo de exemplo e preencha com seus dados:

```bash
cp Back/conexao.example.php Back/conexao.php
```

Edite `Back/conexao.php`:

```php
$host    = "localhost";
$usuario = "SEU_USUARIO";   // ex: root
$senha   = "SUA_SENHA";     // ex: (vazio no XAMPP padrão)
$banco   = "catalogo_futebol";
```

---

### 4. Ajuste o caminho da API (se necessário)

Se o projeto **não** estiver em `/catalogo_futebol/`, abra os arquivos abaixo e altere a constante `API` para o caminho correto:

- `Front/index.js` → linha 1
- `Front/cadastrar.html` → dentro da tag `<script>`
- `Front/atualizar.html` → dentro da tag `<script>`

Exemplo:
```js
const API = "/catalogo_futebol/Back/";
```

---

### 5. Acesse no navegador

```
http://localhost/catalogo_futebol/Front/index.html
```

---

## Estrutura do projeto

```
catalogo_futebol/
├── Back/
│   ├── conexao.example.php   ← modelo de configuração
│   ├── conexao.php           ← criado por você (ignorado pelo git)
│   ├── listar.php
│   ├── cadastro.php
│   ├── atualizar.php
│   └── deletar.php
└── Front/
    ├── index.html
    ├── cadastrar.html
    ├── atualizar.html
    ├── index.css
    ├── index.js
    └── logofut.png
```

---

## Funcionalidades

- Listar jogadores com filtro por nome, clube, país e posição
- Cadastrar novo jogador com foto (URL)
- Editar dados de um jogador
- Deletar jogador com confirmação
- Alternância de tema escuro/claro nos cards
