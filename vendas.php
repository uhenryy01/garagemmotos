<?php
$nome = $_SESSION['nome'] ?? 'Usuário';
$vendas = $vendas ?? [];
$carros = $carros ?? [];
$podeConfirmar = !in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);
?>
<?php $pageTitle='Vendas'; $pageSubtitle='Registre e acompanhe as vendas de motos.'; $pageIcon='cart'; $active='vendas'; require __DIR__ . '/partials/admin_top.php'; ?>

<div class="container">
    <div class="card">
        <h2 style="margin-top:0;">Registrar Venda</h2>
        <form method="POST" action="index.php?controller=venda&action=salvar" style="display:flex;flex-wrap:wrap;gap:10px;align-items:end;">
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Moto</label>
                <select name="carro_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($carros as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome'] . ' - ' . ($c['marca'] ?? '') . ' ' . ($c['modelo'] ?? '')) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Cliente</label>
                <input type="text" name="cliente_nome" placeholder="Nome" required>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Email</label>
                <input type="email" name="cliente_email" placeholder="Opcional">
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Telefone</label>
                <input type="text" name="cliente_telefone" placeholder="Opcional">
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Valor (R$)</label>
                <input type="text" name="valor" placeholder="0,00" required>
            </div>
            <button class="btn" type="submit">Registrar</button>
        </form>
    </div>

    <div class="card" style="margin-top:16px;">
        <h2 style="margin-top:0;">Histórico de Vendas</h2>
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
                            <th>Usuário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vendas as $v): ?>
                        <tr>
                            <td><?= $v['id'] ?></td>
                            <td><?= htmlspecialchars($v['carro_nome'] ?? '') ?></td>
                            <td><?= htmlspecialchars($v['cliente_nome']) ?><br><small style="color:var(--muted)"><?= htmlspecialchars($v['cliente_email'] ?? '') ?> <?= htmlspecialchars($v['cliente_telefone'] ?? '') ?></small></td>
                            <td>R$ <?= number_format($v['valor'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['data_venda'])) ?></td>
                            <td><?= htmlspecialchars($v['usuario_nome'] ?? '') ?></td>
                            <td>
                                <a class="btn btn-sm btn-danger" href="index.php?controller=venda&action=deletar&id=<?= $v['id'] ?>" <?= $podeConfirmar ? "onclick=\"return confirm('Excluir venda?')\"" : '' ?>>Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/partials/admin_bottom.php'; ?>
