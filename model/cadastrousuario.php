<?php
    function emailExiste($email) {
        // Inclua o arquivo de conexão
        include("../model/conexao.php");
        
        // Consulta SQL para verificar se o email já existe no banco de dados
        $query = "SELECT COUNT(*) as total FROM usuarios WHERE email = ?";
        
        // Preparar a declaração
        $stmt = $con->prepare($query);
        
        // Bind dos parâmetros
        $stmt->bind_param("s", $email);
        
        // Executar a consulta
        $stmt->execute();
        
        // Obter o resultado da consulta
        $result = $stmt->get_result();
        
        // Obter o número de linhas retornadas
        $row = $result->fetch_assoc();
        $total = $row['total'];
        
        // Retornar true se o email existe, false caso contrário
        return $total > 0;
    }

    function cadastrarUsuario($primeiroNome, $sobrenome, $email, $senha) {
        // Verificar se o email já existe
        if (emailExiste($email)) {
            // Se o email já existir, retorna false
            return false;
        }
        
        // Inclua o arquivo de conexão
        include("../model/conexao.php");
        
        // Consulta SQL para inserir um novo usuário no banco de dados
        $query = "INSERT INTO usuarios (nome, sobrenome, email, senha) VALUES (?, ?, ?, ?)";
        
        // Preparar a declaração
        $stmt = $con->prepare($query);
        
        // Bind dos parâmetros
        $stmt->bind_param("ssss", $primeiroNome, $sobrenome, $email, $senha);
        
        // Executar a consulta
        if ($stmt->execute()) {
            // Retornar true se o usuário foi cadastrado com sucesso
            return true;
        } else {
            // Retornar false caso contrário
            return false;
        }
    }
?>
