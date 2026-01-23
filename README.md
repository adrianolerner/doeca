# 🏛️ DOECA - Diário Oficial Eletrônico de Código Aberto

> Sistema simples, leve e eficiente para gerenciamento e publicação de Diários Oficiais municipais.

O **DOECA** foi desenvolvido para oferecer uma solução gratuita e de fácil manutenção para prefeituras e câmaras municipais que precisam dar transparência aos seus atos oficiais. O sistema conta com uma área pública de fácil leitura e um painel administrativo seguro para gestão de edições e usuários.

---

## 🆕 O que há de novo na Versão 0.2

A versão 0.2 traz melhorias significativas de segurança e organização:
* **Auditoria e Logs:** Novo módulo para rastrear todas as ações (quem publicou, quem excluiu, IP e data).
* **Segurança de Arquivos:** Bloqueio de acesso direto à pasta `uploads`. Os arquivos agora são servidos via proxy seguro (`arquivo.php`) validando o ID no banco.
* **Armazenamento Inteligente:** Os arquivos agora são salvos em subpastas organizadas por Ano e Mês (`uploads/2023/10/...`), melhorando a performance do servidor.
* **Permissões de Usuário:** Diferenciação real entre `Admin` (controle total) e `Editor` (apenas publica).
* **Busca Melhorada:** Nova barra de pesquisa estilo "Google" na página inicial.
* **Perfil de Usuário:** Possibilidade do usuário alterar a própria senha.

---
## TELAS

<img width="1920" height="947" alt="Pagina de Consulta Publica" src="https://github.com/user-attachments/assets/5ec5bb9e-353d-48ba-bf88-7ea6b308be4b" />

<img width="1920" height="947" alt="Painel Admin" src="https://github.com/user-attachments/assets/27cb5074-3999-4f97-a58b-a1e50a70db52" />

<img width="1920" height="947" alt="Tela de Login" src="https://github.com/user-attachments/assets/c35a18bd-6783-4a79-ad6a-cbe6b3c19a05" />

<img width="1920" height="947" alt="Alteração de senha" src="https://github.com/user-attachments/assets/a01fb962-59ef-4204-886a-1113ace6ddd7" />

<img width="1920" height="947" alt="Gerenciar Usuários" src="https://github.com/user-attachments/assets/205c5854-c224-4ded-a5a8-ccf256689c5c" />

<img width="1920" height="947" alt="Histórico de Alterações" src="https://github.com/user-attachments/assets/805ca0b6-b227-4202-a08d-de6530df31f4" />

---

## 🚀 Funcionalidades

### 🌍 Área Pública
- Listagem organizada de diários com **DataTables**.
- **Barra de Pesquisa Global:** Estilo minimalista e centralizado.
- Visualizador de PDF integrado (Leitura sem sair do site, responsivo via Flexbox).
- Botão de Download seguro.

### 🔒 Painel Administrativo
- Autenticação segura com criptografia de senha (Bcrypt).
- **Gestão de Edições:** Upload, visualização e exclusão lógica + física.
- **Gestão de Usuários:** Cadastro com níveis de permissão.
- **Auditoria:** Histórico visual de alterações no sistema.
- **Proteção:** Bloqueio de editores para ações destrutivas (Excluir edições/usuários).

---

## 🛠️ Requisitos do Servidor

Para rodar o DOECA, você precisará de um servidor web básico com suporte a PHP.

- **PHP:** Versão 7.4 ou superior (Recomendado 8.0+).
- **Banco de Dados:** MySQL ou MariaDB.
- **Servidor Web:** Apache (Recomendado para proteção `.htaccess`) ou Nginx.
- **Extensões PHP:** `pdo_mysql`.

*Funciona perfeitamente em ambientes locais como XAMPP, Laragon ou WampServer.*

---

## 📦 Instalação

Siga os passos abaixo para colocar o sistema no ar:

### 1. Clonar ou Baixar
Faça o download dos arquivos e coloque na pasta pública do seu servidor (ex: `htdocs` ou `www`).

```bash
git clone https://github.com/adrianolerner/doeca/doeca.git
cd doeca

```

### 2. Criar o Banco de Dados

Acesse seu gerenciador de banco de dados (phpMyAdmin, DBeaver, etc), crie um banco chamado `doeca_db` e execute o seguinte script SQL atualizado:

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

-- Tabela de Logs (NOVO NA V0.2)
CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_nome VARCHAR(100),
    acao VARCHAR(50),
    alvo VARCHAR(255),
    detalhes TEXT,
    ip VARCHAR(45),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuário Padrão (Senha: admin)
-- Hash atualizado para a senha 'admin'
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

1. Certifique-se de que a pasta `uploads/` tenha permissão de **escrita** (chmod 777 ou 755).
2. O sistema criará automaticamente subpastas (ex: `uploads/2024/01/`) e copiará o arquivo `.htaccess` de proteção para dentro delas.

---

## 🔑 Acesso Inicial

Após a instalação, acesse a área administrativa em:
`http://seusite/doeca/admin`

Utilize as credenciais padrão:

| Usuário (E-mail) | Senha | Nível |
| --- | --- | --- |
| **admin@municipio.gov.br** | **admin** | Administrador |

> **Importante:** Vá em "Olá, Administrador" > "Alterar Senha" imediatamente após o primeiro login.

---

## 📂 Estrutura de Arquivos

```text
/doeca
├── admin/
│   ├── index.php        # Painel Principal (Upload/Listagem)
│   ├── editar.php       # Edição de publicações
│   ├── usuarios.php     # Gerenciamento de Usuários
│   ├── editar_usuario.php
│   ├── historico.php    # (Novo) Auditoria e Logs
│   ├── logger.php       # (Novo) Função de registro de logs
│   ├── perfil.php       # (Novo) Alteração de senha
│   ├── login.php        # Tela de Login
│   ├── auth.php         # Controle de Sessão
│   └── logout.php       # Sair
├── assets/              # CSS/JS personalizados
├── uploads/             # Raiz de armazenamento (contém .htaccess)
├── arquivo.php          # (Novo) Proxy seguro para download/visualização
├── config.php           # Conexão com Banco de Dados
├── index.php            # Página Pública
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