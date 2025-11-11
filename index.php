<?php
session_start();
require_once 'config.php';

$pdo = conectarDB();

// Buscar todos os produtos cadastrados
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY categoria, nome");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular total de itens no carrinho
$totalItensCarrinho = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $totalItensCarrinho += $item['quantidade'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Floricultura - Página Inicial</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8f5e9 100%);
            min-height: 100vh;
        }
        
        /* Menu Superior */
        .menu-superior {
            background: linear-gradient(135deg, #0a6140 0%, #1f7e37 100%);
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .menu-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .menu-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .menu-links a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .menu-links a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        /* Container Principal */
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
        
        /* Grid de Produtos */
        .grid-produtos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .card-produto {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
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
        
        .card-produto-categoria {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card-produto-preco {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--cor-destaque);
            margin-top: 10px;
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
        
        .mensagem-sucesso {
            background: #4caf50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        .carrinho-link {
            position: relative;
        }
        
        .carrinho-badge {
            background: var(--cor-destaque);
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 0.8rem;
            margin-left: 5px;
            position: relative;
            top: -2px;
        }
        
        @media (max-width: 768px) {
            .menu-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .menu-links {
                width: 100%;
                justify-content: center;
            }
            
            .titulo-pagina {
                font-size: 2rem;
            }
            
            .grid-produtos {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
<?php include 'menu.php'; ?>
    <!-- Conteúdo Principal -->
    <div class="container-principal">
        <h1 class="titulo-pagina">Nossas Flores</h1>
        
        <?php if (isset($_GET['adicionado']) && $_GET['adicionado'] == 1): ?>
            <div class="mensagem-sucesso">
                ✅ Produto adicionado ao carrinho com sucesso!
            </div>
        <?php endif; ?>
        
        <?php if (empty($produtos)): ?>
            <div class="sem-produtos">
                <h2>Nenhum produto cadastrado</h2>
                <p>Ainda não há produtos disponíveis para visualização.</p>
                <p style="margin-top: 20px;">
                    <a href="cadastro.php" style="color: var(--cor-destaque); text-decoration: none; font-weight: bold;">
                        Cadastrar Primeiro Produto →
                    </a>
                </p>
            </div>
        <?php else: ?>
            <div class="grid-produtos">
                <?php foreach ($produtos as $produto): ?>
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
                            <div class="card-produto-categoria"><?php echo htmlspecialchars($produto['categoria']); ?></div>
                            <?php if ($produto['descricao']): ?>
                                <p style="color: #666; font-size: 0.9rem; margin: 10px 0;">
                                    <?php echo htmlspecialchars($produto['descricao']); ?>
                                </p>
                            <?php endif; ?>
                            <div class="card-produto-preco">
                                R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            </div>
                            <a href="carrinho_action.php?acao=adicionar&id=<?php echo $produto['id']; ?>&retorno=index.php" 
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
