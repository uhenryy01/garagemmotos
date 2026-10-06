<?php
$nome = $_SESSION['nome'] ?? 'Usuário';
$vendas = $vendas ?? [];
$entradas = $entradas ?? [];
$totalVendas = $totalVendas ?? 0;
$vendasMes = $vendasMes ?? 0;
$totalProdutos = $totalProdutos ?? 0;
$totalEntradas = $totalEntradas ?? 0;
$lucro = $totalVendas - $totalEntradas;
$margem = $totalVendas > 0 ? round(($lucro / $totalVendas) * 100, 1) : 0;
?>
<?php $pageTitle='Relatórios'; $pageSubtitle='Resumo financeiro de vendas e entradas.'; $pageIcon='chart'; $active='relatorios'; require __DIR__ . '/partials/admin_top.php'; ?>

<div class="container">
    <div class="kpis">
        <div class="kpi">
            <div class="label">Total em Vendas</div>
            <div class="value">R$ <?= number_format($totalVendas, 2, ',', '.') ?></div>
        </div>
        <div class="kpi">
            <div class="label">Vendas no Mês</div>
            <div class="value"><?= $vendasMes ?></div>
        </div>
        <div class="kpi">
            <div class="label">Custo Total (Entradas)</div>
            <div class="value">R$ <?= number_format($totalEntradas, 2, ',', '.') ?></div>
        </div>
        <div class="kpi">
            <div class="label">Lucro</div>
            <div class="value">R$ <?= number_format($lucro, 2, ',', '.') ?></div>
        </div>
        <div class="kpi">
            <div class="label">Margem</div>
            <div class="value"><?= $margem ?>%</div>
        </div>
        <div class="kpi">
            <div class="label">Produtos Ativos</div>
            <div class="value"><?= $totalProdutos ?></div>
        </div>
    </div>

    <div class="card" style="margin-top:20px;">
        <h2 style="margin-top:0;">Últimas Vendas</h2>
        <?php if (empty($vendas)): ?>
            <p style="color:var(--muted)">Nenhuma venda registrada.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Moto</th>
                            <th>Cliente</th>
                            <th>Valor</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($vendas, 0, 10) as $v): ?>
                        <tr>
                            <td><?= $v['id'] ?></td>
                            <td><?= htmlspecialchars($v['carro_nome'] ?? '') ?></td>
                            <td><?= htmlspecialchars($v['cliente_nome']) ?></td>
                            <td>R$ <?= number_format($v['valor'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($v['data_venda'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/partials/admin_bottom.php'; ?>
