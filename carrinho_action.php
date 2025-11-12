<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$acao = $_GET['acao'] ?? '';

if ($acao === 'adicionar') {
    $id = intval($_GET['id'] ?? 0);
    
    if ($id > 0) {
        $pdo = conectarDB();
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($produto) {
            $encontrado = false;
            foreach ($_SESSION['carrinho'] as &$item) {
                if ($item['id'] == $id) {
                    $item['quantidade']++;
                    $encontrado = true;
                    break;
                }
            }
            
            if (!$encontrado) {
                $_SESSION['carrinho'][] = [
                    'id' => $produto['id'],
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco'],
                    'imagem' => $produto['imagem'],
                    'quantidade' => 1
                ];
            }
            
            header('Location: ' . ($_GET['retorno'] ?? 'produtos.php') . '?adicionado=1');
            exit;
        }
    }
    
    header('Location: ' . ($_GET['retorno'] ?? 'produtos.php'));
    exit;
    
} elseif ($acao === 'remover') {
    $id = intval($_GET['id'] ?? 0);
    
    if ($id > 0) {
        foreach ($_SESSION['carrinho'] as $key => $item) {
            if ($item['id'] == $id) {
                unset($_SESSION['carrinho'][$key]);
                $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                break;
            }
        }
    }
    
    header('Location: carrinho.php');
    exit;
    
} elseif ($acao === 'atualizar') {
    $id = intval($_POST['id'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 0);
    
    if ($id > 0 && $quantidade > 0) {
        foreach ($_SESSION['carrinho'] as &$item) {
            if ($item['id'] == $id) {
                $item['quantidade'] = $quantidade;
                break;
            }
        }
    } elseif ($id > 0 && $quantidade <= 0) {
        foreach ($_SESSION['carrinho'] as $key => $item) {
            if ($item['id'] == $id) {
                unset($_SESSION['carrinho'][$key]);
                $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                break;
            }
        }
    }
    
    header('Location: carrinho.php');
    exit;
    
} elseif ($acao === 'limpar') {
    $_SESSION['carrinho'] = [];
    header('Location: carrinho.php');
    exit;
}

header('Location: produtos.php');
exit;
?>

