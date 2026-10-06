<?php
$contato = $contato ?? [];
$messages = $messages ?? [];
?>
<?php $pageTitle='Conversa'; $pageSubtitle='Responda ao cliente.'; $pageIcon='chat'; $active='mensagens'; require __DIR__ . '/partials/admin_top.php'; ?>
<style>
        .card { max-width:760px; }
        .form-group { margin-bottom:16px; }
        .form-group label { display:block; margin-bottom:8px; }
        .form-group textarea { width:100%; min-height:140px; }
        .msg-history { margin-top:24px; }
        .msg-bubble { padding:18px; border-radius:20px; margin-bottom:14px; max-width:100%; }
        .msg-client { background: rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.08); }
        .msg-admin { background: rgba(26,109,255,0.16); border:1px solid rgba(26,109,255,0.4); }
        .msg-bubble strong { display:block; margin-bottom:8px; }
        .msg-bubble small { display:block; margin-top:10px; color:rgba(255,255,255,0.65); }
    </style>
<div class="container">
    <div class="card">
        <h1>Conversa com <?= htmlspecialchars($contato['nome'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p><strong>E-mail:</strong> <?= htmlspecialchars($contato['email'], ENT_QUOTES, 'UTF-8') ?> | <strong>Telefone:</strong> <?= htmlspecialchars($contato['telefone'], ENT_QUOTES, 'UTF-8') ?></p>

        <div class="msg-history">
            <?php foreach ($messages as $item): ?>
                <div class="msg-bubble <?= $item['sender'] === 'admin' ? 'msg-admin' : 'msg-client' ?>">
                    <strong><?= $item['sender'] === 'admin' ? 'Vendedor' : 'Cliente'; ?>:</strong>
                    <p><?= nl2br(htmlspecialchars($item['texto'], ENT_QUOTES, 'UTF-8')) ?></p>
                    <small><?= htmlspecialchars($item['criado_em'], ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="post" action="index.php?controller=contato&action=thread&id=<?= (int)$contato['id'] ?>">
            <div class="form-group">
                <label>Nova mensagem</label>
                <textarea class="input" name="texto" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <button class="btn" type="submit">Enviar resposta</button>
                <a class="btn btn-ghost" href="index.php?controller=contato&action=index">Voltar</a>
            </div>
        </form>
    </div>
</div>
<?php require __DIR__ . '/partials/admin_bottom.php'; ?>
