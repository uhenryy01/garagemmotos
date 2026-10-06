<?php
$editar = $editar ?? null;
$produtos = $produtos ?? [];
$view = $view ?? 'lista';
$q = $q ?? '';
$podeConfirmar = !in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);

function imagemProdutoUrl(int $id): string
{
    foreach (['jpg', 'png', 'webp'] as $ext) {
        if (file_exists(__DIR__ . '/../public/uploads/carros/' . $id . '.' . $ext)) {
            return 'public/uploads/carros/' . $id . '.' . $ext;
        }
    }
    return '';
}
function pv($editar, string $k): string { return $editar ? htmlspecialchars((string)($editar[$k] ?? ''), ENT_QUOTES, 'UTF-8') : ''; }

$isForm = $view === 'form';
$pageTitle = $isForm ? ($editar ? 'Editar Moto' : 'Adicionar Moto') : 'Motos';
$pageSubtitle = $isForm ? ($editar ? 'Atualize os dados da moto #' . (int)$editar['id'] . '.' : 'Cadastre uma nova moto no sistema.') : 'Gerencie as motos cadastradas no sistema.';
$pageIcon = 'bike';
$active = ($isForm && !$editar) ? 'adicionar' : 'motos';
require __DIR__ . '/partials/admin_top.php';
$imgAtual = $editar ? imagemProdutoUrl((int)$editar['id']) : '';
?>
<?php if ($isForm): ?>
<form method="post" action="index.php?controller=produto&action=salvar" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $editar ? (int)$editar['id'] : 0 ?>">
<div class="a-cols">
    <div class="card a-f">
        <div class="a-ch"><?= adminIcon('doc', 24) ?>Informações Gerais</div>
        <div class="a-g2">
            <div class="a-full"><label>Nome <i>*</i></label><input type="text" name="nome" required placeholder="Ex.: Yamaha MT-07" value="<?= pv($editar, 'nome') ?>"></div>
            <div><label>Marca <i>*</i></label><input type="text" name="marca" required placeholder="Ex.: Yamaha" value="<?= pv($editar, 'marca') ?>"></div>
            <div><label>Modelo <i>*</i></label><input type="text" name="modelo" required placeholder="Ex.: MT-07, CB 500, etc." value="<?= pv($editar, 'modelo') ?>"></div>
            <div><label>Ano</label><input type="number" name="ano" min="1900" max="2100" placeholder="Ex.: 2020" value="<?= $editar ? (int)$editar['ano'] : '' ?>"></div>
            <div><label>Cor</label><input type="text" name="cor" placeholder="Ex.: Preta, Vermelha, Branca, etc." value="<?= pv($editar, 'cor') ?>"></div>
            <div><label>Quilometragem</label><input type="number" name="km" min="0" placeholder="Ex.: 12000" value="<?= $editar ? (int)$editar['km'] : '' ?>"></div>
            <div><label>Preço (R$) <i>*</i></label><input type="text" name="preco" required inputmode="decimal" pattern="[0-9.,]*" placeholder="Ex.: 25000,00" value="<?= pv($editar, 'preco') ?>"></div>
            <div><label>Preço atual (opcional)</label><input type="text" name="preco_atual" inputmode="decimal" pattern="[0-9.,]*" placeholder="Ex.: 25000,00" value="<?= pv($editar, 'preco_atual') ?>"><small>Se vazio, usa o preço acima.</small></div>
            <div class="a-full"><label>Descrição</label><textarea name="descricao" id="desc" rows="5" maxlength="500" placeholder="Descreva a moto: opcionais, estado de conservação, etc."><?= pv($editar, 'descricao') ?></textarea><div class="a-ct"><span id="cnt">0</span>/500</div></div>
            <div class="a-full"><label class="a-sw"><input type="hidden" name="ativo" value="0"><input type="checkbox" name="ativo" value="1" <?= (!$editar || (int)$editar['ativo'] === 1) ? 'checked' : '' ?>><span class="tr"></span><span><b>Ativar no site</b><small>Deixe a moto visível para os clientes na loja.</small></span></label></div>
        </div>
    </div>
    <div class="card a-f">
        <div class="a-ch"><?= adminIcon('image', 24) ?>Imagem da Moto</div>
        <label class="a-drop"><input type="file" name="imagem" id="img" accept="image/png,image/jpeg,image/webp">
            <?= adminIcon('upload', 40) ?><b>Clique para adicionar a imagem</b><span>JPG, PNG ou WEBP. Tamanho máximo: 2MB.</span></label>
        <img id="prev" class="a-prev" alt="Pré-visualização" <?= $imgAtual ? 'src="' . htmlspecialchars($imgAtual, ENT_QUOTES, 'UTF-8') . '"' : 'hidden' ?>>
    </div>
</div>
<div class="a-bar">
    <button class="btn btn-ghost" type="reset"><?= adminIcon('refresh', 18) ?>Limpar campos</button>
    <button class="btn" type="submit"><?= adminIcon('save', 18) ?>Salvar Moto</button>
</div>
</form>
<script>
const d=document.getElementById('desc'),c=document.getElementById('cnt');
const u=()=>c.textContent=d.value.length;d.addEventListener('input',u);u();
document.getElementById('img').addEventListener('change',e=>{const f=e.target.files[0],p=document.getElementById('prev');if(f){p.src=URL.createObjectURL(f);p.hidden=false;}});
</script>
<?php else: ?>
<div class="card">
    <div class="a-tools">
        <h2><?= $q !== '' ? 'Resultados para “' . htmlspecialchars($q, ENT_QUOTES, 'UTF-8') . '”' : 'Lista de Motos' ?> <small style="color:var(--a-mu);font-weight:400">(<?= count($produtos) ?>)</small></h2>
        <a class="btn" href="index.php?controller=produto&action=index&view=form"><?= adminIcon('plus', 18) ?>Adicionar Moto</a>
    </div>
    <table class="table">
        <thead><tr><th>Imagem</th><th>ID</th><th>Moto</th><th>Ano</th><th>Preço</th><th>Status</th><th style="width:250px;">Ações</th></tr></thead>
        <tbody>
        <?php foreach ($produtos as $p): $pid = (int)$p['id']; $img = imagemProdutoUrl($pid); ?>
            <tr>
                <td><?php if ($img): ?><img class="a-thumb" src="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>" alt=""><?php else: ?><div class="a-noimg"><?= adminIcon('image', 20) ?></div><?php endif; ?></td>
                <td>#<?= $pid ?></td>
                <td><strong><?= htmlspecialchars($p['nome']) ?></strong><br><small style="color:var(--a-mu)"><?= htmlspecialchars(trim(($p['marca'] ?? '') . ' ' . ($p['modelo'] ?? ''))) ?></small></td>
                <td><?= (int)$p['ano'] ?: '-' ?></td>
                <td>R$ <?= number_format((float)$p['preco_atual'], 2, ',', '.') ?></td>
                <td><?= (int)$p['ativo'] === 1 ? '<span class="tag ok">Ativo</span>' : '<span class="tag off">Inativo</span>' ?></td>
                <td>
                    <a class="btn btn-sm" href="index.php?controller=produto&action=index&id=<?= $pid ?>">Editar</a>
                    <?php if ((int)$p['ativo'] === 1): ?>
                        <a class="btn btn-sm btn-ghost" href="index.php?controller=produto&action=toggle&id=<?= $pid ?>&ativo=0" <?= $podeConfirmar ? "onclick=\"return confirm('Inativar esta moto?')\"" : '' ?>>Inativar</a>
                    <?php else: ?>
                        <a class="btn btn-sm btn-success" href="index.php?controller=produto&action=toggle&id=<?= $pid ?>&ativo=1">Ativar</a>
                    <?php endif; ?>
                    <a class="btn btn-sm btn-danger" href="index.php?controller=produto&action=deletar&id=<?= $pid ?>" <?= $podeConfirmar ? "onclick=\"return confirm('DELETAR permanentemente? Não há volta!')\"" : '' ?>>Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$produtos): ?><tr><td colspan="7" style="color:var(--a-mu)">Nenhuma moto encontrada.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php require __DIR__ . '/partials/admin_bottom.php'; ?>
