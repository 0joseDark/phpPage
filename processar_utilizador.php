<?php

// Validar dados do formulário
$username = $_POST['username'];
$password = $_POST['password'];
$pastas = $_POST['pastas'];
$links = $_POST['links'];

// Conectar à base de dados (substitua os valores de acordo com a sua configuração)
$dbhost = "localhost";
$dbuser = "usuario";
$dbpass = "senha";
$dbname = "basededados";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Verificar se o utilizador já existe
$sql = "SELECT * FROM utilizadores WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    echo "Utilizador já existe!";
    exit;
}

// Criar hash da palavra-passe
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Inserir novo utilizador na base de dados
$sql = "INSERT INTO utilizadores (username, password) VALUES ('$username', '$hashed_password')";
mysqli_query($conn, $sql);

// Obter o ID do novo utilizador
$user_id = mysqli_insert_id($conn);

// Inserir permissões de acesso
foreach ($pastas as $pasta) {
    $sql = "INSERT INTO permissoes (user_id, tipo, recurso) VALUES ($user_id, 'pasta', '$pasta')";
    mysqli_query($conn, $sql);
}

foreach ($links as $link) {
    $sql = "INSERT INTO permissoes (user_id, tipo, recurso) VALUES ($user_id, 'link', '$link')";
    mysqli_query($conn, $sql);
}

// Fechar a conexão com a base de dados
mysqli_close($conn);

echo "Utilizador criado com sucesso!";

?>
