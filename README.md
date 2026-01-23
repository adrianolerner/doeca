# 🏛️ DOECA - Diário Oficial Eletrônico de Código Aberto

> Sistema simples, leve e eficiente para gerenciamento e publicação de Diários Oficiais municipais.

O **DOECA** foi desenvolvido para oferecer uma solução gratuita e de fácil manutenção para prefeituras e câmaras municipais que precisam dar transparência aos seus atos oficiais. O sistema conta com uma área pública de fácil leitura com busca textual avançada e um painel administrativo seguro para gestão de edições e usuários.

---

## 🆕 O que há de novo na Versão 0.2 e 0.3

A versão 0.2 e 0.3 eleva o nível de segurança e funcionalidade do sistema:

* **🔍 Busca Full-Text (OCR/Extração):** O sistema agora lê automaticamente o texto dos PDFs no momento do upload. Isso permite que o cidadão pesquise por leis, decretos ou termos específicos dentro do conteúdo dos documentos.
* **🛡️ Auditoria e Logs:** Novo módulo administrativo que rastreia todas as ações críticas (quem publicou, quem excluiu, IP e data).
* **🔒 Segurança de Arquivos:** Bloqueio de acesso direto à pasta `uploads`. Os arquivos agora são servidos via proxy seguro (`arquivo.php`), validando o acesso antes do download.
* **📂 Armazenamento Inteligente:** Os arquivos são salvos em subpastas organizadas por Ano e Mês (ex: `uploads/2024/01/...`), garantindo performance e organização.
* **👤 Permissões de Usuário:** Diferenciação real entre **Admin** (controle total e auditoria) e **Editor** (apenas publica/edita).

---

## 🚀 Funcionalidades

### 🌍 Área Pública
* **Busca Inteligente:** Barra de pesquisa estilo "Google" que encontra termos dentro dos PDFs e nos metadados.
* **Listagem Otimizada:** Exibição clara das edições recentes.
* **Visualizador Integrado:** Leitura do PDF sem sair do site (layout responsivo).
* **Download Seguro:** Botão de download protegido.

### 🔒 Painel Administrativo
* Autenticação segura com criptografia de senha (Bcrypt).
* **Gestão de Edições:** Upload com extração automática de texto, visualização e exclusão.
* **Gestão de Usuários:** Cadastro com níveis de permissão.
* **Auditoria:** Histórico visual (timeline) de todas as alterações.
* **Segurança:** Bloqueio de ações críticas por usuários não-admin.

---
<img width="1920" height="947" alt="Pagina de Consulta Publica" src="https://github.com/user-attachments/assets/53f9fcba-2600-426b-a23b-52475118d88b" />
---
<img width="1920" height="947" alt="Tela de Login" src="https://github.com/user-attachments/assets/0e55d556-055c-4085-9373-badd9ddd8c03" />
---
<img width="1920" height="947" alt="Painel Admin" src="https://github.com/user-attachments/assets/d7405e84-d101-4836-a673-fc1577fecaa2" />
---
<img width="1920" height="947" alt="Histórico de Alterações" src="https://github.com/user-attachments/assets/5d28f428-54aa-42d2-8201-14919360fc58" />
---
<img width="1920" height="947" alt="Gerenciar Usuários" src="https://github.com/user-attachments/assets/c6812d45-3949-4c02-af8a-a1630d9fe29c" />
---
<img width="1920" height="947" alt="Alteração de senha" src="https://github.com/user-attachments/assets/aa0bd6ab-8ed7-48e1-8fc3-9baa07707081" />
---

## 🛠️ Requisitos do Servidor

Para rodar o DOECA, você precisará de um servidor web básico com suporte a PHP.

* **PHP:** Versão 7.4 ou superior (Recomendado 8.0+).
* **Banco de Dados:** MySQL ou MariaDB.
* **Servidor Web:** Apache (Recomendado) ou Nginx.
* **Gerenciador de Dependências:** Composer (para instalar o leitor de PDF).
* **Extensões PHP:** `pdo_mysql`, `mbstring`.

*O sistema é leve: O banco de dados cresce apenas cerca de 35MB por ano (considerando 360 edições anuais), graças ao armazenamento otimizado de texto.*

---

## 📦 Instalação

Siga os passos abaixo para colocar o sistema no ar:

### 1. Clonar ou Baixar
Faça o download dos arquivos e coloque na pasta pública do seu servidor (ex: `htdocs` ou `www`).

```bash
git clone https://seu-repositorio/doeca.git
cd doeca

```

### 2. Instalar Dependências

O sistema utiliza a biblioteca `smalot/pdfparser` para ler o conteúdo dos Diários. É necessário instalá-la via Composer.

Na raiz do projeto, execute:

```bash
composer install

```

> **Nota para Hospedagem Compartilhada (cPanel/Hostgator/etc):**
> Se o seu servidor não tem acesso SSH/Terminal para rodar o Composer, execute o comando acima no seu computador local (Windows/Mac/Linux) e depois faça o upload da pasta `vendor` gerada para o servidor via FTP.

### 3. Configurar Conexão

1. Renomeie o arquivo `config.example.php` (se houver) para `config.php`.
2. Abra o arquivo e configure suas credenciais de banco de dados:

```php
$host = 'localhost';
$db   = 'doeca_db';
$user = 'root';      // Seu usuário do MySQL
$pass = 'suasenha';  // Sua senha do MySQL

```

### 4. Criar o Banco de Dados

Acesse seu gerenciador (phpMyAdmin, DBeaver) e rode o script SQL completo abaixo:

```sql
CREATE DATABASE IF NOT EXISTS doeca_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE doeca_db;

-- Tabela de Edições (Com suporte a busca Fulltext)
CREATE TABLE edicoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_edicao VARCHAR(50) NOT NULL,
    data_publicacao DATE NOT NULL,
    arquivo_path VARCHAR(255) NOT NULL,
    conteudo_indexado LONGTEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Índice para busca textual ultra-rápida
ALTER TABLE edicoes ADD FULLTEXT(conteudo_indexado);

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('admin', 'editor') DEFAULT 'editor',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de Logs (Auditoria)
CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_nome VARCHAR(100),
    acao VARCHAR(50),
    alvo VARCHAR(255),
    detalhes TEXT,
    ip VARCHAR(45),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Usuário Padrão (Senha: admin)
INSERT INTO usuarios (nome, email, senha, nivel) VALUES 
('Administrador', 'admin@municipio.gov.br', '$2y$10$OSzVz6E6vdRVzhZW3jzS7u9DIJgt/s9MxoW6pBILcGu7JatFcCZJm', 'admin');

```

### 5. Permissões de Pasta

Certifique-se de que a pasta `uploads/` tenha permissão de **escrita** pelo servidor web.

* **Linux:** `chmod -R 775 uploads/` (ou 777 se necessário).
* **Windows:** Geralmente a permissão é automática.

---

## 🔑 Acesso Inicial

Após a instalação, acesse a área administrativa em:
`http://seusite/doeca/admin`

Utilize as credenciais padrão:

| Usuário (E-mail) | Senha | Nível |
| --- | --- | --- |
| **admin@municipio.gov.br** | **admin** | Administrador |

> **⚠️ Importante:** Por segurança, vá em "Olá, Administrador" > "Alterar Senha" imediatamente após o primeiro login.

---

## 📂 Estrutura de Arquivos

```text
/doeca
├── admin/
│   ├── index.php        # Painel Principal (Upload e Extração de Texto)
│   ├── editar.php       # Edição de publicações
│   ├── usuarios.php     # Gerenciamento de Usuários
│   ├── historico.php    # Auditoria e Logs
│   ├── logger.php       # Função auxiliar de logs
│   ├── perfil.php       # Alteração de senha
│   ├── login.php        # Tela de Login
│   ├── auth.php         # Controle de Sessão
│   └── reindexar.php    # Script para indexar PDFs antigos
├── assets/              # CSS/JS personalizados
├── uploads/             # Raiz de armazenamento (contém .htaccess de bloqueio)
├── vendor/              # Bibliotecas externas (instaladas via Composer)
├── arquivo.php          # Proxy seguro para download/visualização
├── config.php           # Conexão com Banco de Dados
├── index.php            # Página Pública (Busca e Listagem)
├── composer.json        # Definição das dependências
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
