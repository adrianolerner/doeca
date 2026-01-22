# 🏛️ DOECA - Diário Oficial Eletrônico de Código Aberto

> Sistema simples, leve e eficiente para gerenciamento e publicação de Diários Oficiais municipais.

O **DOECA** foi desenvolvido para oferecer uma solução gratuita e de fácil manutenção para prefeituras e câmaras municipais que precisam dar transparência aos seus atos oficiais. O sistema conta com uma área pública de fácil leitura e um painel administrativo seguro para gestão de edições e usuários.

---

## 🚀 Funcionalidades

### 🌍 Área Pública
- Listagem organizada de diários com **DataTables** (Busca, Paginação e Filtros).
- Visualizador de PDF integrado (Leitura sem sair do site).
- Design responsivo (Mobile-friendly) com **Bootstrap 5**.
- Botão de Download direto.

### 🔒 Painel Administrativo
- Autenticação segura com criptografia de senha (Bcrypt).
- **Gestão de Edições:** Upload de PDFs, edição de dados e exclusão (com limpeza automática de arquivos).
- **Gestão de Usuários:** Cadastro de múltiplos usuários com níveis de permissão (`Admin` e `Editor`).
- Proteção contra acesso não autorizado.

---

## 🛠️ Requisitos do Servidor

Para rodar o DOECA, você precisará de um servidor web básico com suporte a PHP.

- **PHP:** Versão 7.4 ou superior (Recomendado 8.0+).
- **Banco de Dados:** MySQL ou MariaDB.
- **Servidor Web:** Apache (com `mod_rewrite` opcional) ou Nginx.
- **Extensões PHP:** `pdo_mysql`.

*Funciona perfeitamente em ambientes locais como XAMPP, Laragon ou WampServer.*

---

## 📦 Instalação

Siga os passos abaixo para colocar o sistema no ar:

### 1. Clonar ou Baixar
Faça o download dos arquivos e coloque na pasta pública do seu servidor (ex: `htdocs` ou `www`).

```bash
git clone https://seu-repositorio/doeca.git
cd doeca

```

### 2. Criar o Banco de Dados

Acesse seu gerenciador de banco de dados (phpMyAdmin, DBeaver, etc), crie um banco chamado `doeca_db` e execute o seguinte script SQL:

```sql
CREATE DATABASE IF NOT EXISTS doeca_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE doeca_db;

-- Tabela de Edições
CREATE TABLE edicoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_edicao VARCHAR(50) NOT NULL,
    data_publicacao DATE NOT NULL,
    arquivo_path VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('admin', 'editor') DEFAULT 'editor',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuário Padrão (Senha: admin123)
INSERT INTO usuarios (nome, email, senha, nivel) VALUES 
('Administrador', 'admin@municipio.gov.br', '$2y$10$OSzVz6E6vdRVzhZW3jzS7u9DIJgt/s9MxoW6pBILcGu7JatFcCZJm', 'admin');

```

### 3. Configurar Conexão

Abra o arquivo `config.php` na raiz do projeto e ajuste as credenciais se necessário:

```php
$host = 'localhost';
$db   = 'doeca_db';
$user = 'root'; // Seu usuário do MySQL
$pass = '';     // Sua senha do MySQL

```

### 4. Permissões de Pasta

Certifique-se de que a pasta `uploads/` tenha permissão de **escrita** para que o PHP possa salvar os PDFs.

* **Linux/Mac:** `chmod -R 777 uploads/` (ou `755` dependendo do user do Apache).
* **Windows:** Geralmente já vem liberado.

---

## 🔑 Acesso Inicial

Após a instalação, acesse a área administrativa em:
`http://seusite/doeca/admin`

Utilize as credenciais padrão (e altere-as imediatamente após o login):

| Usuário (E-mail) | Senha | Nível |
| --- | --- | --- |
| **admin@municipio.gov.br** | **admin** | Administrador |

---

## 📂 Estrutura de Arquivos

```text
/doeca
├── admin/
│   ├── index.php        # Painel Principal (CRUD Edições)
│   ├── editar.php       # Edição de publicações
│   ├── usuarios.php     # Gerenciamento de Usuários (CRUD)
│   ├── editar_usuario.php
│   ├── login.php        # Tela de Login
│   ├── auth.php         # Controle de Sessão
│   └── logout.php       # Sair
├── assets/              # CSS/JS personalizados (se houver)
├── uploads/             # Onde os PDFs são salvos
├── config.php           # Conexão com Banco de Dados
├── index.php            # Página Pública (Lista de Diários)
├── visualizar.php       # Leitor de PDF
└── README.md            # Documentação

```

---

## 🤝 Contribuição

Contribuições são bem-vindas! Se você tiver melhorias, correções de bugs ou novas ideias:

1. Faça um Fork do projeto.
2. Crie uma Branch para sua Feature (`git checkout -b feature/NovaFeature`).
3. Faça o Commit (`git commit -m 'Adicionando nova feature'`).
4. Faça o Push (`git push origin feature/NovaFeature`).
5. Abra um Pull Request.

---

## 📄 Licença

Este projeto é de código aberto, licenciado sob a licença [MIT](https://opensource.org/licenses/MIT). Sinta-se livre para usar, modificar e distribuir em seu município.

```

```