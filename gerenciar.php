<?php
require_once 'config.php';

$pdo = conectarDB();
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY categoria, nome");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $pdo->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produtoImg = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($produtoImg && $produtoImg['imagem'] && file_exists($produtoImg['imagem']) && strpos($produtoImg['imagem'], 'produtos/') !== false) {
        @unlink($produtoImg['imagem']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: gerenciar.php?success=Produto excluído com sucesso!");
    exit;
}

$mensagem = '';
$tipoMensagem = '';
if (isset($_GET['success'])) {
    $mensagem = $_GET['success'];
    $tipoMensagem = 'success';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciar Produtos - Floricultura</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8f5e9 100%);
            min-height: 100vh;
        }
        
        .container-principal {
            max-width: 1400px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .titulo-pagina {
            text-align: center;
            color: var(--cor-primaria);
            font-size: 2.5rem;
            margin-bottom: 30px;
            font-weight: bold;
        }
        
        .admin-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--cor-destaque);
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .admin-header h1 {
            color: var(--cor-primaria);
            font-size: 2rem;
            margin: 0;
        }
        
        .mensagem {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        .mensagem.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            padding: 15px 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background: linear-gradient(135deg, var(--cor-primaria) 0%, var(--cor-secundaria) 100%);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .actions .btn {
            padding: 10px 18px;
            font-size: 0.9rem;
            white-space: nowrap;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: all 0.3s ease;
            border-radius: 25px;
        }
        
        .actions .btn-edit {
            background-color: #2196F3;
            color: white;
        }
        
        .actions .btn-edit:hover {
            background-color: #1976D2;
            transform: translateY(-2px);
        }
        
        .actions .btn-danger {
            background-color: #f44336;
            color: white;
        }
        
        .actions .btn-danger:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
        }
        
        table img {
            max-width: 100px;
            max-height: 100px;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            display: block;
        }
        
        .sem-imagem {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 0.8rem;
            border: 2px dashed #ccc;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .actions {
                flex-direction: column;
                width: 100%;
            }
            
            .actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container-principal">
        <h1 class="titulo-pagina">Gerenciar Produtos</h1>
        
        <div class="admin-container">
            <div class="admin-header">
                <h1>Lista de Produtos</h1>
            </div>

            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo $tipoMensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Imagem</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($produtos)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <p>Nenhum produto cadastrado.</p>
                                <a href="cadastro.php" style="margin-top: 20px; display: inline-block; padding: 12px 25px; background: linear-gradient(135deg, var(--cor-destaque) 0%, var(--cor-secundaria) 100%); color: white !important; text-decoration: none; border-radius: 25px; font-weight: bold;">Cadastrar Primeiro Produto</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td><?php echo $produto['id']; ?></td>
                                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                                <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                                <td>
                                    <?php if ($produto['imagem'] && file_exists($produto['imagem'])): ?>
                                        <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                             alt="<?php echo htmlspecialchars($produto['nome']); ?>" />
                                    <?php else: ?>
                                        <div class="sem-imagem">Sem imagem</div>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <a href="cadastro.php?edit=<?php echo $produto['id']; ?>" class="btn btn-edit">✏️ Editar</a>
                                    <a href="gerenciar.php?delete=<?php echo $produto['id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Tem certeza que deseja excluir este produto?');">🗑️ Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
