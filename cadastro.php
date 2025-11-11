<?php
require_once 'config.php';
$mensagem = '';
$tipoMensagem = '';

if (isset($_GET['success'])) {
    $mensagem = $_GET['success'];
    $tipoMensagem = 'success';
} elseif (isset($_GET['error'])) {
    $mensagem = $_GET['error'];
    $tipoMensagem = 'error';
}

$produto = null;
if (isset($_GET['edit'])) {
    $pdo = conectarDB();
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($produto) ? 'Editar Produto' : 'Cadastrar Produto'; ?> - Floricultura</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8f5e9 100%);
            min-height: 100vh;
        }
        
        .container-principal {
            max-width: 800px;
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
        
        .cadastro-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
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
        
        .mensagem.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--cor-primaria);
            font-size: 1rem;
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--cor-destaque);
        }
        
        .form-group input[type="file"] {
            padding: 10px;
            border: 2px dashed var(--cor-destaque);
            border-radius: 8px;
            background: #f9f9f9;
            cursor: pointer;
            width: 100%;
        }
        
        .form-group input[type="file"]:hover {
            background: #f0f8f0;
        }
        
        .img-preview {
            max-width: 300px;
            max-height: 300px;
            margin-top: 15px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            border: 3px solid var(--cor-destaque);
            display: block;
        }
        
        .preview-container {
            text-align: center;
            margin-top: 15px;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 30px;
            background: linear-gradient(135deg, var(--cor-destaque) 0%, var(--cor-secundaria) 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.25);
            color: white !important;
            background: linear-gradient(135deg, var(--cor-secundaria) 0%, var(--cor-primaria) 100%);
        }
        
        button.btn {
            color: white !important;
        }
        
        .btn-secondary {
            background: #666;
        }
        
        .btn-secondary:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container-principal">
        <h1 class="titulo-pagina"><?php echo isset($produto) ? 'Editar Produto' : 'Cadastrar Novo Produto'; ?></h1>
        
        <div class="cadastro-container">
            <?php if ($mensagem): ?>
                <div class="mensagem <?php echo $tipoMensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form action="processar.php" method="POST" enctype="multipart/form-data">
                <?php if (isset($produto)): ?>
                    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nome">Nome do Produto:</label>
                    <input type="text" id="nome" name="nome" required 
                           value="<?php echo isset($produto) ? htmlspecialchars($produto['nome']) : ''; ?>"
                           placeholder="Ex: Orquídea Dendrobium">
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
                           value="<?php echo isset($produto) ? $produto['preco'] : ''; ?>"
                           placeholder="Ex: 100.00">
                </div>

                <div class="form-group">
                    <label>Imagem do Produto:</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*" onchange="previewImagem(this)">
                    <div class="preview-container">
                        <?php if (isset($produto) && $produto['imagem']): ?>
                            <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Preview" class="img-preview" id="img-preview">
                            <p style="margin-top: 10px; color: #666; font-size: 0.9rem;">Deixe em branco para manter a imagem atual</p>
                        <?php else: ?>
                            <img src="" alt="Preview" class="img-preview" id="img-preview" style="display: none;">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="4" 
                              placeholder="Descrição do produto (opcional)"><?php echo isset($produto) ? htmlspecialchars($produto['descricao']) : ''; ?></textarea>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-secondary" name="<?php echo isset($produto) ? 'update' : 'create'; ?>">
                        <?php echo isset($produto) ? 'Atualizar Produto' : 'Cadastrar Produto'; ?>
                    </button>
                    <?php if (isset($produto)): ?>
                        <a href="cadastro.php" class="btn btn-secondary">Cancelar</a>
                    <?php endif; ?>
                    <a href="produtos.php" class="btn btn-secondary">Ver Produtos</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImagem(input) {
            const preview = document.getElementById('img-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
