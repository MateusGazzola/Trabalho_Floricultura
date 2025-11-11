<?php
require_once 'config.php';

$pdo = conectarDB();
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE categoria = 'Rosas' ORDER BY nome");
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rosas - Floricultura</title>
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
            margin-bottom: 40px;
            font-weight: bold;
        }
        
        .grid-produtos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .card-produto {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .card-produto:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .card-produto img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }
        
        .card-produto-info {
            padding: 20px;
        }
        
        .card-produto-nome {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--cor-primaria);
            margin-bottom: 10px;
        }
        
        .card-produto-preco {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--cor-destaque);
            margin-top: 10px;
        }
        
        .btn-adicionar-carrinho {
            display: block;
            width: 100%;
            margin-top: 15px;
            padding: 12px;
            background: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-adicionar-carrinho:hover {
            background: #4CAF50;
            transform: none;
        }
        
        .sem-produtos {
            text-align: center;
            padding: 60px 20px;
            color: #666;
            background: white;
            border-radius: 15px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            margin-top: 20px;
        }
        
        .sem-produtos h2 {
            font-size: 2rem !important;
            margin-bottom: 20px !important;
            margin-top: 0 !important;
            color: #0a6140 !important;
            clear: both !important;
            position: relative !important;
            left: auto !important;
            right: auto !important;
            top: auto !important;
            bottom: auto !important;
            width: 100% !important;
            text-align: center !important;
            display: block !important;
            float: none !important;
            transform: none !important;
        }
        
        .sem-produtos p {
            position: relative !important;
            left: auto !important;
            right: auto !important;
            width: 100% !important;
            text-align: center !important;
            display: block !important;
            float: none !important;
            clear: both !important;
        }
        
        .mensagem-sucesso {
            background: #4caf50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .grid-produtos {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container-principal">
        <h1 class="titulo-pagina">Rosas</h1>
        
        <?php if (isset($_GET['adicionado']) && $_GET['adicionado'] == 1): ?>
            <div class="mensagem-sucesso">
                ✅ Produto adicionado ao carrinho com sucesso!
            </div>
        <?php endif; ?>
        
        <?php if (empty($produtos)): ?>
            <div class="sem-produtos">
                <h2>Nenhuma rosa cadastrada</h2>
                <p>Ainda não há rosas disponíveis.</p>
            </div>
        <?php else: ?>
            <div class="grid-produtos">
                <?php foreach ($produtos as $produto): ?>
                    <div class="card-produto">
                        <?php 
                        $imagemSrc = $produto['imagem'] && file_exists($produto['imagem']) 
                            ? htmlspecialchars($produto['imagem']) 
                            : 'imagens/rosas1.jpg';
                        ?>
                        <img src="<?php echo $imagemSrc; ?>" 
                             alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                             onerror="this.src='imagens/rosas1.jpg'" />
                        <div class="card-produto-info">
                            <div class="card-produto-nome"><?php echo htmlspecialchars($produto['nome']); ?></div>
                            <?php if ($produto['descricao']): ?>
                                <p style="color: #666; font-size: 0.9rem; margin: 10px 0;">
                                    <?php echo htmlspecialchars($produto['descricao']); ?>
                                </p>
                            <?php endif; ?>
                            <div class="card-produto-preco">
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </div>
                            <a href="carrinho_action.php?acao=adicionar&id=<?php echo $produto['id']; ?>&retorno=rosas.php" 
                               class="btn-adicionar-carrinho">
                                🛒 Adicionar ao Carrinho
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
