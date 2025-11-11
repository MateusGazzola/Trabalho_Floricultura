<?php
require_once 'config.php';
$pdo = conectarDB();
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY categoria, nome");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciar Produtos - Floricultura</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 200px auto 40px auto;
            padding: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn:hover {
            opacity: 0.8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .img-preview {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <header class="cabecalho">
        <img class="img_titulo" src="imagens/img-titulo.jpg" alt="titulo" />
        <nav>
            <a href="index.php"><button class="menu_inicio">Inicio</button></a>
            <a href="admin.php"><button class="menu_inicio">Admin</button></a>
        </nav>
    </header>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Gerenciar Produtos</h1>
            <a href="index.php" class="btn">Voltar ao Site</a>
        </div>
        <div class="form-container">
            <h2><?php echo isset($_GET['edit']) ? 'Editar Produto' : 'Adicionar Novo Produto'; ?></h2>
            <form action="processar.php" method="POST">
                <?php if (isset($_GET['edit'])): 
                    $id = $_GET['edit'];
                    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
                    $stmt->execute([$id]);
                    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nome">Nome do Produto:</label>
                    <input type="text" id="nome" name="nome" required 
                           value="<?php echo isset($produto) ? htmlspecialchars($produto['nome']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="categoria">Categoria:</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Selecione uma categoria</option>
                        <option value="Orquideas" <?php echo (isset($produto) && $produto['categoria'] == 'Orquideas') ? 'selected' : ''; ?>>Orquídeas</option>
                        <option value="Rosas" <?php echo (isset($produto) && $produto['categoria'] == 'Rosas') ? 'selected' : ''; ?>>Rosas</option>
                        <option value="Outros" <?php echo (isset($produto) && $produto['categoria'] == 'Outros') ? 'selected' : ''; ?>>Outros</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="preco">Preço (R$):</label>
                    <input type="number" id="preco" name="preco" step="0.01" min="0" required 
                           value="<?php echo isset($produto) ? $produto['preco'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="imagem">Caminho da Imagem:</label>
                    <input type="text" id="imagem" name="imagem" 
                           value="<?php echo isset($produto) ? htmlspecialchars($produto['imagem']) : ''; ?>"
                           placeholder="ex: imagens/orquidea1.jpg">
                    <?php if (isset($produto) && $produto['imagem']): ?>
                        <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Preview" class="img-preview">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="3"><?php echo isset($produto) ? htmlspecialchars($produto['descricao']) : ''; ?></textarea>
                </div>

                <button type="submit" class="btn" name="<?php echo isset($_GET['edit']) ? 'update' : 'create'; ?>">
                    <?php echo isset($_GET['edit']) ? 'Atualizar' : 'Adicionar'; ?> Produto
                </button>
                <?php if (isset($_GET['edit'])): ?>
                    <a href="admin.php" class="btn" style="background-color: #666;">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>

        <h2>Lista de Produtos</h2>
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
                        <td colspan="6" style="text-align: center;">Nenhum produto cadastrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo $produto['id']; ?></td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td>
                                <?php if ($produto['imagem']): ?>
                                    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                         alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                                         style="max-width: 50px; max-height: 50px;">
                                <?php else: ?>
                                    Sem imagem
                                <?php endif; ?>
                            </td>
                            <td class="actions">
                                <a href="admin.php?edit=<?php echo $produto['id']; ?>" class="btn btn-edit">Editar</a>
                                <a href="admin.php?delete=<?php echo $produto['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2025 Floricultura - Todos os direitos reservados.</p>
    </footer>
</body>
</html>

