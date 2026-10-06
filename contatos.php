<?php
$contatos = $contatos ?? [];
?>
<?php $pageTitle='Mensagens'; $pageSubtitle='Propostas e contatos recebidos pela loja.'; $pageIcon='chat'; $active='mensagens'; require __DIR__ . '/partials/admin_top.php'; ?>

<div class="container">
	<div class="card">
		<h2>Mensagens recebidas</h2>
<?php if (empty($contatos)): ?>
<p>Nenhuma mensagem registrada ainda.</p>
<?php else: ?>
<table class="table">
<thead>
<tr>
<th>ID</th>
<th>Moto</th>
<th>Nome</th>
<th>E-mail</th>
<th>Telefone</th>
<th>Mensagem</th>
<th>Resposta</th>
<th>Recebido em</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
<?php foreach ($contatos as $contato): ?>
<tr>
<td>#<?= (int)$contato['id'] ?></td>
<td><?= htmlspecialchars($contato['carro_nome'] ?: 'Sem moto') ?></td>
<td><?= htmlspecialchars($contato['nome']) ?></td>
<td><?= htmlspecialchars($contato['email']) ?></td>
<td><?= htmlspecialchars($contato['telefone']) ?></td>
<td><?= nl2br(htmlspecialchars($contato['mensagem'])) ?></td>
<td><?= $contato['resposta'] ? 'Respondido' : 'Pendente' ?></td>
<td><?= htmlspecialchars($contato['criado_em']) ?></td>
<td><a class="btn" href="index.php?controller=contato&action=thread&id=<?= (int)$contato['id'] ?>">Ver conversa</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</div>
</div>
<?php require __DIR__ . '/partials/admin_bottom.php'; ?>
