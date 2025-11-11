<?php
require_once 'config.php';

$pdo = conectarDB();

function fazerUploadImagem($arquivo, $produtoId = null) {
    $diretorio = 'imagens/produtos/';
    
    // Criar diretório se não existir
    if (!file_exists($diretorio)) {
        mkdir($diretorio, 0777, true);
    }
    
    if (isset($arquivo['name']) && $arquivo['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($extensao, $extensoesPermitidas)) {
            return ['erro' => 'Formato de arquivo não permitido. Use: jpg, jpeg, png, gif ou webp'];
        }
        
        $nomeArquivo = ($produtoId ? 'produto_' . $produtoId . '_' : 'produto_') . time() . '_' . uniqid() . '.' . $extensao;
        $caminhoCompleto = $diretorio . $nomeArquivo;
        
        if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            return ['sucesso' => $caminhoCompleto];
        } else {
            return ['erro' => 'Erro ao fazer upload da imagem'];
        }
    }
    
    return null;
}

if (isset($_POST['create'])) {
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'] ?? '';
    $imagem = '';
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload = fazerUploadImagem($_FILES['imagem']);
        if (isset($upload['erro'])) {
            header("Location: cadastro.php?error=" . urlencode($upload['erro']));
            exit;
        }
        if (isset($upload['sucesso'])) {
            $imagem = $upload['sucesso'];
        }
    }

    $stmt = $pdo->prepare("INSERT INTO produtos (nome, categoria, preco, imagem, descricao) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$nome, $categoria, $preco, $imagem, $descricao])) {
        header("Location: cadastro.php?success=Produto adicionado com sucesso!");
        exit;
    } else {
        header("Location: cadastro.php?error=Erro ao adicionar produto.");
        exit;
    }
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'] ?? '';
    
    $stmt = $pdo->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produtoAtual = $stmt->fetch(PDO::FETCH_ASSOC);
    $imagem = $produtoAtual['imagem'] ?? '';
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        if ($imagem && file_exists($imagem) && strpos($imagem, 'produtos/') !== false) {
            @unlink($imagem);
        }
        
        $upload = fazerUploadImagem($_FILES['imagem'], $id);
        if (isset($upload['erro'])) {
            header("Location: cadastro.php?edit=" . $id . "&error=" . urlencode($upload['erro']));
            exit;
        }
        if (isset($upload['sucesso'])) {
            $imagem = $upload['sucesso'];
        }
    }

    $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, categoria = ?, preco = ?, imagem = ?, descricao = ? WHERE id = ?");
    
    if ($stmt->execute([$nome, $categoria, $preco, $imagem, $descricao, $id])) {
        header("Location: cadastro.php?success=Produto atualizado com sucesso!");
        exit;
    } else {
        header("Location: cadastro.php?error=Erro ao atualizar produto.");
        exit;
    }
}

header("Location: cadastro.php");
exit;
?>
