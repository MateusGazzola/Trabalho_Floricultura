<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$carrinho = $_SESSION['carrinho'];
$total = 0;
$totalItens = 0;

foreach ($carrinho as $item) {
    $total += $item['preco'] * $item['quantidade'];
    $totalItens += $item['quantidade'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Carrinho - Floricultura</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8f5e9 100%);
            min-height: 100vh;
        }
        
        .container-principal {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
            position: relative;
            overflow-x: hidden;
        }
        
        .titulo-pagina {
            text-align: center;
            color: var(--cor-primaria);
            font-size: 2.5rem;
            margin-bottom: 40px;
            font-weight: bold;
            clear: both;
            position: relative;
            z-index: 1;
        }
        
        .carrinho-vazio {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }
        
        .carrinho-vazio h2 {
            color: #0a6140 !important;
            margin-bottom: 20px !important;
            font-size: 1.8rem !important;
            margin-top: 0 !important;
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
        
        .carrinho-vazio p {
            color: #666 !important;
            margin-bottom: 30px !important;
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
        
        .btn-continuar {
            display: inline-block;
            padding: 12px 30px;
            background: var(--cor-destaque);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-continuar:hover {
            background: #d32f2f;
            transform: translateY(-2px);
        }
        
        .carrinho-container {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }
        
        .itens-carrinho {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }
        
        .item-carrinho {
            display: flex;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        
        .item-carrinho:last-child {
            border-bottom: none;
        }
        
        .item-imagem {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .item-info {
            flex: 1;
        }
        
        .item-nome {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--cor-primaria);
            margin-bottom: 10px;
        }
        
        .item-preco {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--cor-destaque);
            margin-bottom: 15px;
        }
        
        .item-controles {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .quantidade-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .quantidade-control input {
            width: 60px;
            padding: 8px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .btn-quantidade {
            width: 35px;
            height: 35px;
            border: none;
            background: #0a6140 !important;
            color: white !important;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.3s ease;
            opacity: 1 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-quantidade:hover {
            background: #0a6140 !important;
            opacity: 1 !important;
        }
        
        .btn-quantidade:active {
            opacity: 1 !important;
        }
        
        .btn-remover {
            padding: 8px 15px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-remover:hover {
            background: #c82333;
        }
        
        .resumo-carrinho {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 100px;
        }
        
        .resumo-titulo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--cor-primaria);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }
        
        .resumo-linha {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .resumo-total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            padding-bottom: 20px;
            border-top: 2px solid var(--cor-primaria);
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--cor-destaque);
            margin-bottom: 0;
        }
        
        .resumo-botoes {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-finalizar {
            width: 100%;
            padding: 15px;
            background: #4CAF50 !important;
            color: white !important;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.3s ease;
            opacity: 1 !important;
            display: block;
            order: 1;
        }
        
        .btn-finalizar:hover {
            background: #4CAF50 !important;
            transform: none;
            opacity: 1 !important;
        }
        
        .btn-limpar {
            width: 100%;
            padding: 12px;
            background: #6c757d !important;
            color: white !important;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            opacity: 1 !important;
            display: block;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            order: 2;
        }
        
        .btn-limpar:hover {
            background: #5a6268 !important;
            opacity: 1 !important;
        }
        
        .btn-continuar-comprando {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: var(--cor-primaria);
            text-decoration: none;
            font-weight: 500;
            order: 3;
        }
        
        .btn-continuar-comprando:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 968px) {
            .carrinho-container {
                grid-template-columns: 1fr;
            }
            
            .resumo-carrinho {
                position: static;
            }
        }
        
        @media (max-width: 600px) {
            .item-carrinho {
                flex-direction: column;
            }
            
            .item-imagem {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container-principal">
        <h1 class="titulo-pagina">🛒 Meu Carrinho</h1>
        
        <?php if (empty($carrinho)): ?>
            <div class="carrinho-vazio">
                <h2>Seu carrinho está vazio</h2>
                <p>Adicione produtos ao carrinho para continuar comprando.</p>
                <a href="produtos.php" class="btn-continuar">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <div class="carrinho-container">
                <div class="itens-carrinho">
                    <?php foreach ($carrinho as $item): ?>
                        <div class="item-carrinho">
                            <?php 
                            $imagemSrc = $item['imagem'] && file_exists($item['imagem']) 
                                ? htmlspecialchars($item['imagem']) 
                                : 'imagens/outrasflores.jpg';
                            ?>
                            <img src="<?php echo $imagemSrc; ?>" 
                                 alt="<?php echo htmlspecialchars($item['nome']); ?>" 
                                 class="item-imagem"
                                 onerror="this.src='imagens/outrasflores.jpg'" />
                            <div class="item-info">
                                <div class="item-nome"><?php echo htmlspecialchars($item['nome']); ?></div>
                                <div class="item-preco">
                                    R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
                                </div>
                                <div class="item-controles">
                                    <form method="POST" action="carrinho_action.php?acao=atualizar" style="display: flex; align-items: center; gap: 10px;">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="button" class="btn-quantidade" onclick="alterarQuantidade(<?php echo $item['id']; ?>, -1)">-</button>
                                        <input type="number" 
                                               name="quantidade" 
                                               value="<?php echo $item['quantidade']; ?>" 
                                               min="1" 
                                               id="qtd-<?php echo $item['id']; ?>"
                                               onchange="this.form.submit()">
                                        <button type="button" class="btn-quantidade" onclick="alterarQuantidade(<?php echo $item['id']; ?>, 1)">+</button>
                                    </form>
                                    <a href="carrinho_action.php?acao=remover&id=<?php echo $item['id']; ?>" 
                                       class="btn-remover"
                                       onclick="return confirm('Deseja remover este item do carrinho?')">
                                        Remover
                                    </a>
                                </div>
                                <div style="margin-top: 10px; font-weight: bold; color: var(--cor-primaria);">
                                    Subtotal: R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="resumo-carrinho">
                    <div class="resumo-titulo">Resumo do Pedido</div>
                    <div class="resumo-linha">
                        <span>Itens (<?php echo $totalItens; ?>):</span>
                        <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                    </div>
                    <div class="resumo-total">
                        <span>Total:</span>
                        <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                    </div>
                    <div class="resumo-botoes">
                        <button class="btn-finalizar" onclick="">
                            Finalizar Compra
                        </button>
                        <a href="carrinho_action.php?acao=limpar" 
                           class="btn-limpar"
                           onclick="return confirm('Deseja limpar todo o carrinho?')">
                            Limpar Carrinho
                        </a>
                        <a href="produtos.php" class="btn-continuar-comprando">
                            ← Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function alterarQuantidade(id, delta) {
            const input = document.getElementById('qtd-' + id);
            const novaQuantidade = parseInt(input.value) + delta;
            if (novaQuantidade >= 1) {
                input.value = novaQuantidade;
                input.form.submit();
            }
        }
    </script>
</body>
</html>

