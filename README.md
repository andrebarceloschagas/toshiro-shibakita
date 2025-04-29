# Projeto Exemplo PHP com Banco de Dados e Docker

Este é um projeto simples que demonstra uma aplicação PHP conectando-se a um banco de dados MySQL para inserir dados aleatórios. O projeto também inclui configurações para rodar com Nginx como proxy reverso/balanceador de carga usando Docker.

## Propósito

O objetivo principal é servir como um exemplo básico de:
*   Conexão PHP com MySQL usando a extensão `mysqli`.
*   Inserção de dados no banco de dados.
*   Uso de Nginx como proxy reverso.
*   Containerização básica com Docker.

## Estrutura de Arquivos

*   `index.php`: O script PHP principal que conecta ao banco, gera dados e os insere na tabela `dados`.
*   `banco.sql`: Script SQL para criar a tabela `dados` necessária para a aplicação.
*   `nginx.conf`: Arquivo de configuração do Nginx, definindo um upstream para balanceamento de carga (neste exemplo, aponta para IPs que podem ser de outros containers ou instâncias).
*   `dockerfile`: Define a imagem Docker para o serviço Nginx, copiando a configuração personalizada.
*   `README.md`: Este arquivo.

## Pré-requisitos

*   Servidor Web com PHP (com extensão `mysqli` habilitada).
*   Servidor de Banco de Dados MySQL.
*   (Opcional) Docker e Docker Compose para rodar a configuração com Nginx.

## Configuração

1.  **Banco de Dados:**
    *   Crie um banco de dados (ex: `meubanco`).
    *   Execute o script `banco.sql` para criar a tabela `dados`.
        ```bash
        mysql -u seu_usuario -p seu_banco < banco.sql
        ```
    *   Certifique-se de que o usuário do banco de dados tenha permissões para conectar e inserir dados na tabela `dados`.

2.  **Aplicação PHP (`index.php`):**
    *   **IMPORTANTE:** Atualize as credenciais do banco de dados (`$servername`, `$username`, `$password`, `$database`) no arquivo `index.php`. **É altamente recomendável usar variáveis de ambiente ou um arquivo de configuração seguro em vez de colocar as credenciais diretamente no código.**
    *   Coloque o arquivo `index.php` em um diretório acessível pelo seu servidor web.

3.  **(Opcional) Configuração com Nginx e Docker:**
    *   A configuração `nginx.conf` assume que existem instâncias da aplicação rodando nos IPs `172.31.0.37`, `172.31.0.151`, e `172.31.0.149` na porta 80. Você precisará ajustar esses IPs conforme sua infraestrutura (provavelmente usando nomes de serviço do Docker Compose se estiver usando-o).
    *   Construa a imagem Docker do Nginx:
        ```bash
        docker build -t meu-nginx .
        ```
    *   Execute o container Nginx, mapeando a porta 4500:
        ```bash
        docker run -d -p 4500:4500 --name nginx_proxy meu-nginx
        ```
    *   Você precisará garantir que os containers/serviços PHP estejam acessíveis a partir do container Nginx nos IPs e portas configurados no `upstream`.

## Execução

1.  **Acesso Direto (sem Nginx/Docker):**
    *   Acesse o `index.php` através do seu navegador (ex: `http://localhost/seu_diretorio/index.php`). Cada acesso irá inserir um novo registro aleatório no banco de dados e exibir uma mensagem de sucesso ou erro.

2.  **Acesso via Nginx (com Docker):**
    *   Após configurar e executar o container Nginx e as instâncias PHP, acesse o Nginx pelo navegador (ex: `http://localhost:4500`). O Nginx encaminhará a requisição para uma das instâncias PHP configuradas no `upstream`.

## Observações

*   Este é um exemplo didático. Em um ambiente de produção, considere:
    *   Gerenciamento seguro de senhas (variáveis de ambiente, vaults).
    *   Tratamento de erros mais robusto e logging.
    *   Validação de dados.
    *   Uso de frameworks PHP para melhor organização.
    *   Configurações de segurança adicionais no Nginx e PHP.
