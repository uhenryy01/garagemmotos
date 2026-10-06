<?php
$nome = $_SESSION['nome'] ?? 'Usuário';
$entradas = $entradas ?? [];
$carros = $carros ?? [];
$podeConfirmar = !in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);
?>
<?php $pageTitle='Entradas'; $pageSubtitle='Registre a entrada de motos no estoque.'; $pageIcon='box'; $active='entradas'; require __DIR__ . '/partials/admin_top.php'; ?>

<div class="container">
    <div class="card">
        <h2 style="margin-top:0;">Registrar Entrada</h2>
        <form method="POST" action="index.php?controller=entrada&action=salvar" style="display:flex;flex-wrap:wrap;gap:10px;align-items:end;">
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
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Quantidade</label>
                <input type="number" name="quantidade" value="1" min="1" required>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Preço de Custo (R$)</label>
                <input type="text" name="preco_custo" placeholder="0,00" required>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Fornecedor</label>
                <input type="text" name="fornecedor" placeholder="Opcional">
            </div>
            <button class="btn" type="submit">Registrar</button>
        </form>
    </div>

    <div class="card" style="margin-top:16px;">
        <h2 style="margin-top:0;">Histórico de Entradas</h2>
        <?php if (empty($entradas)): ?>
            <p style="color:var(--muted)">Nenhuma entrada registrada.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Moto</th>
                            <th>Qtd</th>
                            <th>Valor</th>
                            <th>Total</th>
                            <th>Fornecedor</th>
                            <th>Data</th>
                            <th>Usuário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entradas as $e): ?>
                        <tr>
                            <td><?= $e['id'] ?></td>
                            <td><?= htmlspecialchars($e['carro_nome'] ?? '') ?></td>
                            <td><?= $e['quantidade'] ?></td>
                            <td>R$ <?= number_format($e['preco_custo'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($e['preco_custo'] * $e['quantidade'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($e['fornecedor'] ?? '-') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($e['data_entrada'])) ?></td>
                            <td><?= htmlspecialchars($e['usuario_nome'] ?? '') ?></td>
                            <td>
                                <a class="btn btn-sm btn-danger" href="index.php?controller=entrada&action=deletar&id=<?= $e['id'] ?>" <?= $podeConfirmar ? "onclick=\"return confirm('Excluir entrada?')\"" : '' ?>>Excluir</a>
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
