
<?php
session_start();
$totalItensCarrinho = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $totalItensCarrinho += $item['quantidade'];
    }
}
?>
<nav class="menu-superior">
    <div class="menu-container">
        <a href="index.php" class="logo">
            <img src="img-titulo.jpg" alt="Floricultura Bandeirante" class="logo-img">
        </a>
        <div class="menu-links">
            <a href="index.php">Início</a>
            <a href="produtos.php">Produtos</a>
            <a href="cadastro.php">Cadastro</a>
            <a href="gerenciar.php">Gerenciar</a>
            <a href="carrinho.php" class="carrinho-link">
                🛒 Carrinho
                <?php if ($totalItensCarrinho > 0): ?>
                    <span class="carrinho-badge"><?php echo $totalItensCarrinho; ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</nav>

<style>
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
        display: flex;
        align-items: center;
        text-decoration: none;
    }
    
    .logo-img {
        max-height: 60px;
        height: auto;
        width: auto;
        max-width: 300px;
        object-fit: contain;
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
            align-items: center;
        }
        
        .logo-img {
            max-height: 50px;
            max-width: 250px;
        }
        
        .menu-links {
            width: 100%;
            justify-content: center;
        }
    }
</style>

