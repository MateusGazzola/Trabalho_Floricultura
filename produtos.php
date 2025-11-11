<?php
require_once 'config.php';

$pdo = conectarDB();

$stmt = $pdo->query("SELECT * FROM produtos ORDER BY categoria, nome");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$produtosPorCategoria = [];
foreach ($produtos as $produto) {
    $categoria = $produto['categoria'];
    if (!isset($produtosPorCategoria[$categoria])) {
        $produtosPorCategoria[$categoria] = [];
    }
    $produtosPorCategoria[$categoria][] = $produto;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Produtos - Floricultura</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .container-principal * {
            box-sizing: border-box;
        }
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
            width: 100%;
            box-sizing: border-box;
        }
        
        .titulo-pagina {
            text-align: center;
            color: var(--cor-primaria);
            font-size: 2.5rem;
            margin-bottom: 40px;
            font-weight: bold;
        }
        
        .categoria-section {
            margin-bottom: 50px;
            width: 100%;
            clear: both;
            position: relative;
            overflow: visible;
        }
        
        .categoria-titulo {
            color: var(--cor-primaria) !important;
            font-size: 2rem !important;
            margin-bottom: 30px !important;
            padding-bottom: 10px !important;
            border-bottom: 3px solid var(--cor-destaque) !important;
            width: 100% !important;
            clear: both !important;
            position: relative !important;
            left: auto !important;
            top: auto !important;
            text-align: left !important;
            text-transform: none !important;
            letter-spacing: normal !important;
            display: block !important;
            float: none !important;
        }
        
        .grid-produtos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            width: 100%;
            clear: both;
            justify-items: stretch;
            align-items: start;
        }
        
        @media (min-width: 1200px) {
            .grid-produtos {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                max-width: 100%;
            }
        }
        
        @media (min-width: 1400px) {
            .grid-produtos {
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                max-width: 100%;
            }
        }
        
        .card-produto {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            display: flex;
            flex-direction: column;
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
            flex: 1;
            display: flex;
            flex-direction: column;
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
            box-sizing: border-box;
            flex-shrink: 0;
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
        
        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
        }
        
        .categoria-section {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        .container-principal .categoria-section h2.categoria-titulo,
        .categoria-section h2.categoria-titulo,
        h2.categoria-titulo {
            display: block !important;
            color: var(--cor-primaria) !important;
            font-size: 2rem !important;
            font-weight: bold !important;
            margin: 0 0 30px 0 !important;
            padding: 0 0 10px 0 !important;
            border: none !important;
            border-bottom: 3px solid var(--cor-destaque) !important;
            width: 100% !important;
            clear: both !important;
            position: relative !important;
            left: 0 !important;
            top: 0 !important;
            right: auto !important;
            bottom: auto !important;
            text-align: left !important;
            text-transform: none !important;
            letter-spacing: normal !important;
            float: none !important;
            line-height: 1.2 !important;
            background: transparent !important;
            box-shadow: none !important;
            outline: none !important;
            z-index: 1 !important;
            text-decoration: none !important;
            overflow: visible !important;
        }
        
                .categoria-section h2.categoria-titulo::before,
        .categoria-section h2.categoria-titulo::after {
            content: none !important;
            display: none !important;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container-principal">
        <h1 class="titulo-pagina">Todos os Produtos</h1>
        
        <?php if (isset($_GET['adicionado']) && $_GET['adicionado'] == 1): ?>
            <div class="mensagem-sucesso">
                ✅ Produto adicionado ao carrinho com sucesso!
            </div>
        <?php endif; ?>
        
        <?php if (empty($produtos)): ?>
            <div class="sem-produtos">
                <h2>Nenhum produto cadastrado</h2>
                <p>Ainda não há produtos disponíveis.</p>
                <p style="margin-top: 20px;">
                    <a href="cadastro.php" style="color: var(--cor-destaque); text-decoration: none; font-weight: bold;">
                        Cadastrar Primeiro Produto →
                    </a>
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($produtosPorCategoria as $categoria => $produtosCategoria): ?>
                <div class="categoria-section">
                    <h2 class="categoria-titulo"><?php echo htmlspecialchars($categoria); ?></h2>
                    <div class="grid-produtos">
                        <?php foreach ($produtosCategoria as $produto): ?>
                            <div class="card-produto">
                                <?php 
                                $imagemSrc = $produto['imagem'] && file_exists($produto['imagem']) 
                                    ? htmlspecialchars($produto['imagem']) 
                                    : 'imagens/outrasflores.jpg';
                                ?>
                                <img src="<?php echo $imagemSrc; ?>" 
                                     alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                                     onerror="this.src='imagens/outrasflores.jpg'" />
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
                                    <a href="carrinho_action.php?acao=adicionar&id=<?php echo $produto['id']; ?>&retorno=produtos.php" 
                                       class="btn-adicionar-carrinho">
                                        🛒 Adicionar ao Carrinho
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
