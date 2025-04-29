<?php
// Habilita a exibição de erros (útil para desenvolvimento, desabilitar em produção)
ini_set("display_errors", 1);
error_reporting(E_ALL); // Reportar todos os tipos de erro

// Define o cabeçalho HTTP para HTML com charset UTF-8 (mais comum que iso-8859-1)
header('Content-Type: text/html; charset=utf-8');

// --- Configurações do Banco de Dados ---
// Recomenda-se usar variáveis de ambiente ou arquivos de configuração para segurança
$servername = "54.234.153.24";
$username = "root";
$password = "Senha123"; // Cuidado: Senha hardcoded!
$database = "meubanco";

// --- Conexão com o Banco de Dados ---
$link = new mysqli($servername, $username, $password, $database);

// Verifica se houve erro na conexão
if ($link->connect_error) {
    // Exibe uma mensagem de erro genérica para o usuário e loga o erro real
    error_log("Erro de conexão com o banco de dados: " . $link->connect_error);
    die("Falha ao conectar com o banco de dados. Por favor, tente novamente mais tarde.");
}

// Define o charset da conexão para UTF-8
if (!$link->set_charset("utf8")) {
    error_log("Erro ao definir charset UTF-8: " . $link->error);
    // Não é crítico continuar, mas logar o erro é importante
}

// --- Geração de Dados Aleatórios ---
$valor_rand1 = rand(1, 999);
// Gera um valor hexadecimal aleatório mais seguro e o converte para maiúsculas
try {
    $valor_rand2 = strtoupper(bin2hex(random_bytes(4)));
} catch (Exception $e) {
    // Fallback caso random_bytes falhe (raro)
    error_log("Erro ao gerar random_bytes: " . $e->getMessage());
    $valor_rand2 = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
}
$host_name = gethostname(); // Obtém o hostname do servidor onde o script está rodando

// --- Preparação e Execução da Query SQL (Usando Prepared Statements) ---
$query = "INSERT INTO dados (AlunoID, Nome, Sobrenome, Endereco, Cidade, Host) VALUES (?, ?, ?, ?, ?, ?)";

// Prepara a declaração
$stmt = $link->prepare($query);

if ($stmt === false) {
    // Erro ao preparar a query
    error_log("Erro ao preparar a query: " . $link->error);
    die("Ocorreu um erro interno. Por favor, tente novamente mais tarde.");
}

// Associa os parâmetros (bind) - 'isssss' significa Integer, String, String, String, String, String
$stmt->bind_param("isssss", $valor_rand1, $valor_rand2, $valor_rand2, $valor_rand2, $valor_rand2, $host_name);

// Executa a query
$success = $stmt->execute();

?>
<!DOCTYPE html>
<html lang="pt-br"> <!-- Definir o idioma da página -->

<head>
    <meta charset="UTF-8"> <!-- Usar UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Para responsividade -->
    <title>Exemplo PHP - Resultado</title>
</head>

<body>

    <h1>Exemplo PHP com Banco de Dados</h1>

    <p>Versão Atual do PHP: <?php echo htmlspecialchars(phpversion()); ?></p>

    <?php
    // Verifica o resultado da execução da query
    if ($success) {
        echo "<p style='color: green;'>Novo registro criado com sucesso!</p>";
        echo "<p>ID do Aluno: " . htmlspecialchars($valor_rand1) . "</p>";
        echo "<p>Dados Inseridos: " . htmlspecialchars($valor_rand2) . "</p>";
        echo "<p>Hostname: " . htmlspecialchars($host_name) . "</p>";
    } else {
        // Exibe uma mensagem de erro genérica e loga o erro real
        error_log("Erro ao executar a query: " . $stmt->error);
        echo "<p style='color: red;'>Erro ao inserir registro no banco de dados.</p>";
    }

    // Fecha o statement
    $stmt->close();

    // Fecha a conexão com o banco de dados
    $link->close();
    ?>

</body>

</html>
