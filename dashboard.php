<?php
require_once __DIR__ . '/../models/Venda.php';
require_once __DIR__ . '/../models/produto.php';
$nome = $_SESSION['nome'] ?? 'Usuário';
$vendaModel = new Venda();
$vendasMes = $vendaModel->quantidadeMes();
$faturamentoMes = $vendaModel->totalMes();
$motosAtivas = count((new Produto())->listarTodos(true));
$pageTitle = 'Painel';
$pageSubtitle = 'Bem-vindo(a), ' . $nome . '! Escolha um módulo para continuar.';
$pageIcon = 'home';
$active = 'painel';
require __DIR__ . '/partials/admin_top.php';
?>
<div class="kpis" style="margin-bottom:22px;">
    <div class="kpi"><div class="label">Vendas (mês)</div><div class="value"><?= (int)$vendasMes ?></div></div>
    <div class="kpi"><div class="label">Faturamento (mês)</div><div class="value">R$ <?= number_format($faturamentoMes, 2, ',', '.') ?></div></div>
    <div class="kpi"><div class="label">Motos ativas</div><div class="value"><?= (int)$motosAtivas ?></div></div>
    <div class="kpi"><div class="label">Mensagens pendentes</div><div class="value"><?= (int)$pendentes ?></div></div>
</div>
<div class="a-quick">
    <?php foreach ($nav as $i): if ($i === null || $i[0] === 'painel') continue; ?>
        <a href="<?= a_e($i[3]) ?>"><?= adminIcon($i[2], 24) ?><b><?= a_e($i[1]) ?></b></a>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/partials/admin_bottom.php'; ?>
