<?php
$carros = $carros ?? [];
$perfil = $_SESSION['perfil'] ?? null;
$usuario = $_SESSION['nome'] ?? null;

function imagemCarroUrl(int $carroId): string
{
    $baseFs = __DIR__ . '/../public/uploads/carros/';
    $baseUrl = 'public/uploads/carros/';
    foreach (['jpg', 'png', 'webp'] as $ext) {
        if (file_exists($baseFs . $carroId . '.' . $ext)) {
            return $baseUrl . $carroId . '.' . $ext;
        }
    }
    return 'https://via.placeholder.com/400x250?text=Sem+imagem';
}

function bannerImageUrl(): string
{
    $bannerFiles = [
        __DIR__ . '/../img/banner-loja.png',
        __DIR__ . '/../img/a.jpg',
        __DIR__ . '/../img/banner.2.png',
    ];

    foreach ($bannerFiles as $filePath) {
        if (file_exists($filePath)) {
            return str_replace(__DIR__ . '/../', '', $filePath);
        }
    }

    return 'https://via.placeholder.com/1920x720?text=Garagem+Motos';
}

function motoCodigo(int $carroId, string $marca): string
{
    $slug = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $marca));
    $slug = $slug !== '' ? substr($slug, 0, 8) : 'GB';
    return sprintf('MOT-%s-%03d', $slug, $carroId);
}

function motoGarantiaMeses(array $car): int
{
    return (int)($car['km'] ?? 0) <= 50000 ? 6 : 3;
}

function motoCondicao(array $car): string
{
    return (int)($car['km'] ?? 0) === 0 ? 'Novo' : 'Usado';
}

function motoDestaques(array $car): array
{
    $destaques = ['Moto revisada', 'Documentação em dia'];
    if ((int)($car['km'] ?? 0) === 0) {
        $destaques[] = 'Sem uso';
    }
    if (!empty($car['ano'])) {
        $destaques[] = 'Ano ' . (int)$car['ano'];
    }
    return $destaques;
}

function motoTags(array $car): array
{
    return array_values(array_filter([
        trim((string)($car['marca'] ?? '')),
        trim((string)($car['modelo'] ?? '')),
        trim((string)($car['cor'] ?? '')),
    ], static fn ($tag) => $tag !== ''));
}

$whatsappNumero = '';
try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT numero FROM whatsapp_config WHERE id = 1");
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    $whatsappNumero = $config ? $config['numero'] : '';
} catch (Exception $e) {
    $whatsappNumero = '';
}

$whatsappLink = '';
if ($whatsappNumero !== '') {
    $whatsappLink = 'https://wa.me/55' . preg_replace('/\D/', '', $whatsappNumero)
        . '?text=' . rawurlencode('Olá! Gostaria de falar sobre as motos da Garagem Motos.');
}

$beneficios = [
    ['titulo' => 'Parcelamento', 'texto' => 'em até 6x sem juros', 'icone' => 'card'],
    ['titulo' => 'Frete Grátis', 'texto' => 'acima de R$ 1.500,00', 'icone' => 'truck'],
    ['titulo' => 'Segurança', 'texto' => 'pagamento 100% seguro', 'icone' => 'shield'],
    ['titulo' => 'Pague com Desconto', 'texto' => '10% de desconto no Pix', 'icone' => 'pix'],
];

$comoFunciona = [
    ['titulo' => 'Escolha as motos', 'texto' => 'Busque pelo nome, marca ou modelo e confira as fotos, o código e as especificações de cada moto.'],
    ['titulo' => 'Solicite o orçamento', 'texto' => 'Clique em Solicitar Orçamento e fale direto com a nossa equipe pelo WhatsApp, sem compromisso.'],
    ['titulo' => 'Confirme o pagamento', 'texto' => 'Pague no Pix com 10% de desconto ou parcele em até 6x sem juros no cartão.'],
    ['titulo' => 'Receba em casa', 'texto' => 'Enviamos para todo o Brasil com embalagem reforçada e código de rastreio, ou retire na loja.'],
];

$garantiaItens = [
    'Garantia de 3 a 6 meses conforme a condição da moto.',
    'Todas as motos passam por revisão antes do envio.',
    'Nota fiscal emitida em todas as vendas.',
    'Suporte técnico para dúvidas de instalação.',
    'Troca garantida em caso de divergência na peça.',
];

$depoimentos = [
    ['nome' => 'Leonardo Alves de Oliveira', 'data' => '17/01/2024', 'nota' => 5, 'texto' => 'Cumpre com o que promete.'],
    ['nome' => 'Vanessa Martina Silva', 'data' => '18/12/2023', 'nota' => 5, 'texto' => 'Excelente atendimento. Foram muito prestativos para esclarecer minhas dúvidas a respeito da peça que precisei comprar.'],
    ['nome' => 'Rocket Perfomance', 'data' => '14/12/2023', 'nota' => 5, 'texto' => 'Ótimo lugar para se adquirir, com perfeita qualidade. Ótimo atendimento e com boa solução de requisitos, foram super atenciosos.'],
    ['nome' => 'Edilson Monteiro', 'data' => '16/11/2023', 'nota' => 5, 'texto' => 'Atendimento excelente tanto na venda como na pós venda. Produto chegou como contratado e com entrega super rápida.'],
    ['nome' => 'Marcos Pereira', 'data' => '02/10/2023', 'nota' => 5, 'texto' => 'Moto entregue no prazo e exatamente como descrita no anúncio. Recomendo a loja.'],
];

$faqs = [
    ['pergunta' => 'As motos são novas?', 'resposta' => 'Trabalhamos com motos novas e usadas revisadas. A condição de cada item aparece no selo do anúncio (Novo ou Usado), junto com o prazo de garantia.'],
    ['pergunta' => 'Tem produtos a pronta entrega?', 'resposta' => 'Sim. Todas as motos exibidas no catálogo estão em estoque e prontas para envio. Se algum item sair de estoque, ele deixa de aparecer na loja.'],
    ['pergunta' => 'Quais as formas de pagamento?', 'resposta' => 'Aceitamos Pix com 10% de desconto, cartão de crédito em até 6x sem juros, boleto e transferência bancária.'],
    ['pergunta' => 'O produto pode ser retirado?', 'resposta' => 'Sim. A retirada pode ser feita na loja após a confirmação do pagamento. Combine o horário com a nossa equipe pelo WhatsApp.'],
    ['pergunta' => 'Os envios são para todo o Brasil?', 'resposta' => 'Enviamos para todo o Brasil por transportadora. O frete é grátis para compras acima de R$ 1.500,00 e você recebe o código de rastreio.'],
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja de Motos - Garagem Motos</title>
    <link rel="icon" type="image/png" href="img/logom.png">
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        *{ margin:0; padding:0; box-sizing:border-box; }
        body{ background:#0a0a0a; color:#e0e0e0; font-family:Inter,system-ui,sans-serif; }

        .header-bar{
            background:#111;
            border-bottom:2px solid #1a6dff;
            padding:12px 20px;
            position:sticky;
            top:0;
            z-index:100;
            box-shadow:0 4px 20px rgba(26,109,255,0.18);
        }
        .header-inner{
            max-width:1200px;
            margin:0 auto;
            display:flex;
            align-items:center;
            justify-content:space-between;
        }
        .logo-link{ display:flex; align-items:center; gap:12px; text-decoration:none; }
        .logo-link img{ height:52px; width:auto; object-fit:contain; filter:drop-shadow(0 2px 10px rgba(26,109,255,0.35)); transition:transform .2s; }
        .logo-link:hover img{ transform:scale(1.04); }
        .logo-text{
            color:#fff; font-weight:900; font-size:1.25rem;
            letter-spacing:-0.01em; line-height:1; white-space:nowrap;
        }
        .logo-text span{ color:#5aa2ff; }

        .header-actions{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
        .header-actions .btn{ padding:8px 18px; border-radius:10px; font-weight:600; font-size:0.9rem; }

        .header-nav{
            max-width:1200px;
            margin:10px auto 0;
            padding-top:10px;
            border-top:1px solid rgba(255,255,255,0.06);
            display:flex; align-items:center; justify-content:space-between;
            gap:12px; flex-wrap:wrap;
        }
        .nav-links{ display:flex; align-items:center; gap:2px; flex-wrap:wrap; }
        .nav-links a{
            color:#bbb; text-decoration:none;
            font-size:0.88rem; font-weight:600;
            padding:8px 12px; border-radius:10px;
            transition:all .2s;
        }
        .nav-links a:hover{ color:#5aa2ff; background:rgba(26,109,255,0.10); }
        .btn-whatsapp-top{
            display:inline-flex; align-items:center; gap:8px;
            background:#25D366; color:#111;
            padding:10px 20px; border-radius:24px;
            font-weight:700; font-size:0.85rem;
            text-decoration:none; transition:all .2s;
        }
        .btn-whatsapp-top:hover{ background:#20bd5a; transform:translateY(-1px); }

        .hero-banner{
            position:relative;
            overflow:hidden;
            margin:28px auto;
            max-width:1200px;
            aspect-ratio:1600/420; min-height:240px;
            border-radius:24px;
            border:2px solid rgba(26,109,255,0.30);
            box-shadow:0 8px 40px rgba(26,109,255,0.14);
        }
        .hero-banner img{ width:100%; height:100%; object-fit:cover; object-position:right center; display:block; }
        .hero-overlay{
            position:absolute; inset:0;
            background:linear-gradient(90deg, rgba(7,11,20,0.55) 0%, rgba(7,11,20,0) 60%);
        }
        .hero-content{
            position:absolute; inset:0;
            display:flex; flex-direction:column;
            justify-content:center; align-items:flex-start;
            padding:48px;
        }
        .hero-content h1{
            margin:0; font-size:3.2rem;
            color:#fff; text-transform:uppercase;
            letter-spacing:0.08em; font-weight:900;
            text-shadow:0 4px 20px rgba(0,0,0,0.5);
        }
        .hero-content h1 span{ color:#5aa2ff; }
        .hero-content p{
            margin:16px 0 0; color:rgba(255,255,255,0.85);
            font-size:1.1rem; max-width:560px; line-height:1.5;
        }

        section[id]{ scroll-margin-top:150px; }

        .section{ max-width:1200px; margin:0 auto; padding:56px 16px; }
        .section-head{ text-align:center; margin-bottom:32px; }
        .section-eyebrow{
            color:#5aa2ff; font-size:0.78rem; font-weight:800;
            letter-spacing:0.18em; text-transform:uppercase;
        }
        .section-head h2{
            margin:8px 0 0; font-size:2.1rem; color:#fff;
            font-weight:900; text-transform:uppercase; letter-spacing:0.02em;
        }
        .section-head p{ margin:12px auto 0; color:#888; max-width:620px; line-height:1.6; font-size:0.95rem; }

        .benefits{ max-width:1200px; margin:0 auto; padding:0 16px; }
        .benefits-inner{
            background:#141414;
            border:1px solid rgba(255,255,255,0.06);
            border-radius:18px;
            padding:22px;
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:18px;
        }
        .benefit{ display:flex; align-items:center; gap:14px; }
        .benefit-icon{
            width:44px; height:44px; flex:0 0 44px;
            border-radius:12px;
            background:rgba(26,109,255,0.14);
            border:1px solid rgba(26,109,255,0.30);
            color:#5aa2ff;
            display:flex; align-items:center; justify-content:center;
        }
        .benefit strong{ display:block; color:#fff; font-size:0.95rem; }
        .benefit span{ color:#888; font-size:0.85rem; }

        .cars-grid{
            max-width:1200px; margin:0 auto;
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
            gap:24px;
        }

        .car-card{
            background:#141414;
            border-radius:18px;
            overflow:hidden;
            border:1px solid rgba(255,255,255,0.06);
            transition:all .25s;
            display:flex; flex-direction:column;
        }
        .car-card:hover{
            border-color:rgba(26,109,255,0.45);
            box-shadow:0 8px 32px rgba(26,109,255,0.18);
            transform:translateY(-3px);
        }
        .car-card img{ width:100%; height:200px; object-fit:cover; display:block; }
        .car-card-media{ position:relative; }
        .card-badges{
            position:absolute; top:10px; left:10px; right:10px;
            display:flex; flex-wrap:wrap; gap:6px;
        }
        .card-badge{
            font-size:0.72rem; font-weight:700;
            padding:5px 11px; border-radius:999px;
            background:rgba(10,10,10,0.65);
            border:1px solid rgba(255,255,255,0.14);
            color:#eee;
            backdrop-filter:blur(6px);
        }
        .card-badge.is-warranty{ color:#5aa2ff; border-color:rgba(26,109,255,0.45); margin-left:auto; }
        .card-badge.is-ship{ color:#25D366; border-color:rgba(37,211,102,0.35); }

        .car-card-body{ padding:18px; flex:1; display:flex; flex-direction:column; }
        .car-card-body h2{ margin:0 0 6px; font-size:1.15rem; color:#fff; font-weight:700; }
        .car-card-body .car-sub{ margin:0 0 8px; color:#888; font-size:0.9rem; }
        .car-apps{ margin:0 0 10px; color:#999; font-size:0.88rem; line-height:1.45; }
        .car-apps strong{ color:#ddd; }
        .car-code{
            display:flex; align-items:center; gap:8px;
            margin:0 0 12px; padding:9px 12px;
            border-radius:12px;
            background:rgba(255,255,255,0.04);
            border:1px solid rgba(255,255,255,0.08);
            font-size:0.82rem; color:#999;
        }
        .car-code code{ font-family:ui-monospace,SFMono-Regular,Menlo,monospace; color:#5aa2ff; letter-spacing:0.04em; }
        .car-features{ list-style:none; margin:0 0 14px; padding:0; display:flex; flex-direction:column; gap:6px; }
        .car-features li{ position:relative; padding-left:16px; color:#aaa; font-size:0.87rem; }
        .car-features li::before{
            content:''; position:absolute; left:0; top:7px;
            width:6px; height:6px; border-radius:50%; background:#1a6dff;
        }
        .car-tags{ display:flex; flex-wrap:wrap; gap:6px; margin:0 0 14px; }
        .spec-tag{
            font-size:0.75rem; padding:5px 11px; border-radius:999px;
            background:rgba(255,255,255,0.05);
            border:1px solid rgba(255,255,255,0.1);
            color:#bbb;
        }
        .car-card-body .car-price{
            margin:0 0 12px;
            color:#5aa2ff;
            font-weight:800;
            font-size:1.4rem;
            letter-spacing:-0.02em;
        }
        .car-card-body .car-desc{
            margin:0 0 16px;
            color:#999;
            line-height:1.5;
            font-size:0.92rem;
            flex:1;
        }
        .car-card-actions{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            padding-top:12px;
            border-top:1px solid rgba(255,255,255,0.06);
        }
        .car-card-actions .btn-contact{
            background:#25D366; color:#111; border:none; padding:8px 20px;
            border-radius:24px; font-weight:700; font-size:0.85rem;
            cursor:pointer; transition:all .2s;
            display:flex; align-items:center; gap:6px;
            text-decoration:none;
        }
        .car-card-actions .btn-contact:hover{ background:#20bd5a; transform:translateY(-1px); }
        .car-card-actions .btn-outline{
            background:transparent; color:#ccc; border:1px solid rgba(255,255,255,0.15);
            padding:8px 20px; border-radius:24px; font-weight:600; font-size:0.85rem;
            cursor:pointer; text-decoration:none; transition:all .2s;
            display:inline-flex; align-items:center;
        }
        .car-card-actions .btn-outline:hover{ border-color:#1a6dff; color:#5aa2ff; }

        .empty-state{ text-align:center; padding:80px 24px; }
        .empty-state h2{ margin:0 0 12px; color:#fff; }

        .steps{ display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:20px; }
        .step{
            background:#141414;
            border:1px solid rgba(255,255,255,0.06);
            border-radius:18px;
            padding:24px;
            transition:all .25s;
        }
        .step:hover{ border-color:rgba(26,109,255,0.40); transform:translateY(-3px); }
        .step-num{
            width:40px; height:40px; border-radius:12px;
            background:linear-gradient(135deg,#1a6dff,#5aa2ff);
            color:#fff; font-weight:900;
            display:flex; align-items:center; justify-content:center;
            margin-bottom:14px;
        }
        .step h3{ margin:0 0 8px; color:#fff; font-size:1.05rem; }
        .step p{ margin:0; color:#8f8f8f; font-size:0.9rem; line-height:1.6; }

        .warranty{
            background:linear-gradient(135deg,#141414 0%,#101a26 100%);
            border:1px solid rgba(26,109,255,0.25);
            border-radius:24px;
            padding:36px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:32px;
            align-items:center;
        }
        .warranty-intro h3{ margin:0 0 12px; color:#fff; font-size:1.6rem; font-weight:900; }
        .warranty-intro h3 span{ color:#5aa2ff; }
        .warranty-intro p{ margin:0 0 20px; color:#999; line-height:1.6; font-size:0.95rem; }
        .warranty-list{ list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:14px; }
        .warranty-list li{ display:flex; gap:10px; color:#bbb; font-size:0.92rem; line-height:1.5; }
        .warranty-list svg{ flex:0 0 20px; color:#25D366; margin-top:2px; }

        .reviews{ position:relative; }
        .reviews-track{
            display:flex; gap:18px;
            overflow-x:auto;
            scroll-behavior:smooth;
            scroll-snap-type:x mandatory;
            padding:4px;
            scrollbar-width:none;
        }
        .reviews-track::-webkit-scrollbar{ display:none; }
        .review-card{
            flex:0 0 300px;
            scroll-snap-align:start;
            background:#141414;
            border:1px solid rgba(255,255,255,0.06);
            border-radius:18px;
            padding:20px;
        }
        .review-head{ display:flex; align-items:center; gap:12px; margin-bottom:12px; }
        .review-avatar{
            width:40px; height:40px; flex:0 0 40px;
            border-radius:50%;
            background:rgba(26,109,255,0.16);
            border:1px solid rgba(26,109,255,0.35);
            color:#5aa2ff; font-weight:800;
            display:flex; align-items:center; justify-content:center;
        }
        .review-name{ color:#fff; font-weight:700; font-size:0.92rem; }
        .review-date{ color:#777; font-size:0.78rem; }
        .review-google{ margin-left:auto; color:#4285F4; font-weight:900; font-size:1.15rem; line-height:1; }
        .review-stars{ color:#ffb400; letter-spacing:2px; font-size:0.95rem; margin-bottom:10px; }
        .review-text{ margin:0; color:#9a9a9a; font-size:0.88rem; line-height:1.6; }
        .reviews-arrow{
            position:absolute; top:50%; transform:translateY(-50%);
            width:40px; height:40px; border-radius:50%;
            background:rgba(20,20,20,0.9);
            border:1px solid rgba(255,255,255,0.12);
            color:#ccc; font-size:1.1rem; cursor:pointer;
            display:flex; align-items:center; justify-content:center;
            z-index:2; transition:all .2s;
        }
        .reviews-arrow:hover{ border-color:#1a6dff; color:#5aa2ff; }
        .reviews-arrow.prev{ left:-14px; }
        .reviews-arrow.next{ right:-14px; }
        .reviews-summary{ text-align:center; margin-top:22px; color:#888; font-size:0.9rem; }
        .reviews-summary strong{ color:#fff; }

        .faq-list{ max-width:820px; margin:0 auto; display:flex; flex-direction:column; gap:12px; }
        .faq-item{
            background:#141414;
            border:1px solid rgba(255,255,255,0.06);
            border-radius:14px;
            overflow:hidden;
            transition:border-color .2s;
        }
        .faq-item.open{ border-color:rgba(26,109,255,0.40); }
        .faq-q{
            width:100%; background:none; border:none;
            color:#fff; font-family:inherit; font-weight:700; font-size:0.98rem;
            text-align:left; padding:18px 20px; cursor:pointer;
            display:flex; align-items:center; justify-content:space-between; gap:12px;
        }
        .faq-q .faq-icon{ color:#5aa2ff; font-size:1.4rem; line-height:1; flex:0 0 auto; transition:transform .25s; }
        .faq-item.open .faq-icon{ transform:rotate(45deg); }
        .faq-a{ max-height:0; overflow:hidden; transition:max-height .3s ease; }
        .faq-item.open .faq-a{ max-height:320px; }
        .faq-a p{ margin:0; padding:0 20px 18px; color:#8f8f8f; font-size:0.9rem; line-height:1.65; }

        .site-footer{ border-top:1px solid rgba(255,255,255,0.06); background:#0d0d0d; margin-top:40px; }
        .footer-inner{
            max-width:1200px; margin:0 auto; padding:44px 16px;
            display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:30px;
        }
        .footer-col h4{
            margin:0 0 14px; color:#fff; font-size:0.82rem;
            text-transform:uppercase; letter-spacing:0.14em;
        }
        .footer-col p, .footer-col a{ margin:0 0 8px; color:#888; font-size:0.9rem; line-height:1.6; text-decoration:none; display:block; }
        .footer-col a:hover{ color:#5aa2ff; }
        .footer-bottom{
            text-align:center; padding:18px;
            border-top:1px solid rgba(255,255,255,0.05);
            color:#666; font-size:0.82rem;
        }

        .wa-float{
            position:fixed; right:20px; bottom:20px;
            width:58px; height:58px; border-radius:50%;
            background:#25D366; color:#fff;
            display:flex; align-items:center; justify-content:center;
            box-shadow:0 8px 28px rgba(37,211,102,0.4);
            text-decoration:none; z-index:1500;
            transition:transform .2s;
        }
        .wa-float:hover{ transform:scale(1.08); }

        .aviso-overlay{
            position:fixed; inset:0;
            background:rgba(0,0,0,0.82);
            backdrop-filter:blur(6px);
            z-index:3000;
            display:none; align-items:center; justify-content:center;
            padding:16px;
        }
        .aviso-overlay.open{ display:flex; }
        .aviso-modal{
            position:relative;
            width:520px; max-width:100%;
            max-height:calc(100vh - 40px); overflow-y:auto;
            background:#141414;
            border:1px solid rgba(255,255,255,0.08);
            border-radius:22px;
            padding:34px 28px 26px;
            text-align:center;
            box-shadow:0 24px 70px rgba(0,0,0,0.6);
            animation:modalIn .25s ease;
        }
        .aviso-modal::before{
            content:''; position:absolute; top:0; left:0; right:0; height:5px;
            background:linear-gradient(90deg,#1a6dff,#5aa2ff);
        }
        .aviso-modal h2{
            margin:0 0 8px; color:#5aa2ff;
            font-size:1.35rem; font-weight:900;
            text-transform:uppercase; letter-spacing:0.04em;
        }
        .aviso-modal .aviso-sub{ margin:0 0 20px; color:#999; font-size:0.9rem; }
        .aviso-list{
            list-style:none; margin:0 0 22px; padding:18px;
            text-align:left;
            background:rgba(255,255,255,0.04);
            border:1px solid rgba(255,255,255,0.07);
            border-radius:16px;
            display:flex; flex-direction:column; gap:14px;
        }
        .aviso-list li{ display:flex; gap:10px; color:#bbb; font-size:0.9rem; line-height:1.5; }
        .aviso-list li svg{ color:#ffb400; flex:0 0 auto; margin-top:3px; }
        .btn-aviso{
            width:100%; padding:15px;
            border:none; border-radius:14px;
            background:#2b2b2b; color:#fff;
            font-family:inherit; font-weight:800; font-size:0.9rem;
            text-transform:uppercase; letter-spacing:0.08em;
            cursor:pointer; transition:all .2s;
        }
        .btn-aviso:hover{ background:#1a6dff; color:#fff; }

        .contato-overlay{
            position:fixed; inset:0;
            background:rgba(0,0,0,0.7);
            z-index:2000;
            display:none;
            align-items:center;
            justify-content:center;
            backdrop-filter:blur(4px);
        }
        .contato-overlay.open{ display:flex; }
        .contato-modal{
            background:#1a1a1a;
            border:1px solid rgba(255,255,255,0.1);
            border-radius:24px;
            width:460px;
            max-width:calc(100vw - 32px);
            max-height:calc(100vh - 40px);
            overflow-y:auto;
            padding:28px;
            box-shadow:0 20px 60px rgba(0,0,0,0.5);
            animation:modalIn .25s ease;
        }
        @keyframes modalIn{ from{ opacity:0; transform:scale(0.95) translateY(10px); } to{ opacity:1; transform:scale(1) translateY(0); } }
        .contato-modal-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:20px;
        }
        .contato-modal-header h2{ margin:0; font-size:1.2rem; display:flex; align-items:center; gap:8px; }
        .contato-modal-header .close-btn{
            background:none; border:none; color:#888;
            font-size:1.5rem; cursor:pointer; padding:4px; line-height:1;
        }
        .contato-modal-header .close-btn:hover{ color:#fff; }
        .contato-modal .car-info{
            background:rgba(255,255,255,0.04);
            border-radius:14px;
            padding:14px;
            margin-bottom:20px;
            border:1px solid rgba(255,255,255,0.06);
        }
        .contato-modal .car-info strong{ color:#5aa2ff; }
        .contato-modal .form-group{ margin-bottom:16px; }
        .contato-modal .form-group label{ display:block; margin-bottom:6px; font-size:0.9rem; font-weight:500; }
        .contato-modal .form-group label .required{ color:#5aa2ff; }
        .contato-modal .form-group input,
        .contato-modal .form-group textarea{
            width:100%;
            padding:12px 14px;
            border-radius:12px;
            background:rgba(255,255,255,0.06);
            border:1px solid rgba(255,255,255,0.1);
            color:#fff;
            font-size:0.95rem;
        }
        .contato-modal .form-group input:focus,
        .contato-modal .form-group textarea:focus{
            border-color:#25D366;
            outline:none;
        }
        .contato-modal .form-group textarea{ resize:vertical; min-height:80px; }
        .contato-modal .btn-enviar{
            width:100%;
            padding:14px;
            border:none;
            border-radius:14px;
            background:#25D366;
            color:#111;
            font-weight:700;
            font-size:1rem;
            cursor:pointer;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }
        .contato-modal .btn-enviar:hover{ background:#20bd5a; }
        .contato-modal .btn-enviar:disabled{ opacity:0.5; cursor:not-allowed; }
        .contato-modal .unavailable-msg{
            text-align:center;
            padding:24px;
            color:var(--muted);
        }
        .contato-modal .unavailable-msg span{ font-size:3rem; display:block; margin-bottom:12px; }

        @media(max-width:900px){
            .warranty{ grid-template-columns:1fr; padding:26px; }
        }

        @media(max-width:768px){
            .header-inner{ flex-wrap:wrap; gap:8px; }
            .logo-link img{ height:40px; }
            .logo-text{ font-size:1.05rem; }
            .hero-content h1{ font-size:2rem; }
            .hero-content{ padding:24px; }
            .cars-grid{ grid-template-columns:1fr; }
            .contato-modal{ padding:20px; }
            .header-nav{ justify-content:center; }
            .nav-links{ justify-content:center; }
            .nav-links a{ padding:7px 9px; font-size:0.82rem; }
            .section{ padding:40px 16px; }
            .section-head h2{ font-size:1.6rem; }
            .review-card{ flex:0 0 82%; }
            .reviews-arrow{ display:none; }
            .aviso-modal{ padding:28px 20px 22px; }
        }
    </style>
</head>
<body>
    <header class="header-bar">
        <div class="header-inner">
            <a href="lojaview.php" class="logo-link">
                <img src="img/logom.png" alt="Garagem Motos">
                <span class="logo-text">Garagem <span>Motos</span></span>
            </a>
            <form class="search-form" action="lojaview.php" method="GET" style="flex:1; max-width:400px; margin:0 16px; display:flex; gap:6px;">
                <input
                    type="text"
                    name="search"
                    class="input search-input"
                    placeholder="Buscar por nome, marca ou modelo..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    style="flex:1; padding:10px 16px; border-radius:24px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); color:#fff; font-size:0.9rem;"
                >
                <button type="submit" style="background:#1a6dff; border:none; border-radius:24px; padding:10px 18px; color:#fff; font-size:1rem; cursor:pointer; font-weight:700;">Buscar</button>
            </form>
            <div class="header-actions">
                <?php if ($perfil === 'admin'): ?>
                    <a class="btn btn-ghost" href="index.php?controller=auth&action=dashboard" style="color:#ccc;text-decoration:none;">Admin</a>
                <?php endif; ?>
                <?php if ($usuario): ?>
                    <span style="font-size:0.85rem;color:#888;">Olá, <?= htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') ?></span>
                    <a class="btn-outline" href="index.php?controller=auth&action=logout">Sair</a>
                <?php else: ?>
                    <a class="btn-outline" href="index.php?controller=usuario&action=create">Registro</a>
                    <a class="btn-outline" href="index.php?controller=auth&action=form">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <nav class="header-nav">
            <div class="nav-links">
                <a href="#inicio">Início</a>
                <a href="#catalogo">Catálogo</a>
                <a href="#como-funciona">Como Funciona</a>
                <a href="#garantia">Garantia</a>
                <a href="#depoimentos">Depoimentos</a>
                <a href="#faq">FAQ</a>
                <a href="#contato">Contato</a>
            </div>
            <a class="btn-whatsapp-top" href="<?= $whatsappLink !== '' ? htmlspecialchars($whatsappLink, ENT_QUOTES, 'UTF-8') : '#contato' ?>" <?= $whatsappLink !== '' ? 'target="_blank" rel="noopener"' : '' ?>>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm5.3 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.6-.1-.4-.1-1-.3-1.7-.6-2.9-1.3-4.8-4.3-5-4.5-.1-.2-.8-1.1-.8-2.1 0-1 .5-1.5.7-1.7.2-.2.5-.3.7-.3h.5c.2 0 .4 0 .6.4l.8 1.9c.1.2 0 .4-.1.5l-.3.4c-.1.1-.2.3-.1.5.2.4.7 1.2 1.4 1.8.9.8 1.6 1.1 1.8 1.2.2.1.4 0 .5-.1l.6-.7c.2-.2.4-.1.6 0l1.8.9c.4.2.4.3.4.5s0 .8-.2 1.3Z"/></svg>
                Falar no WhatsApp
            </a>
        </nav>
    </header>

    <section class="hero-banner" id="inicio">
        <img src="<?= htmlspecialchars(bannerImageUrl(), ENT_QUOTES, 'UTF-8') ?>" alt="Banner Garagem Motos">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Loja de <span>Motos</span></h1>
            <p>Encontre as melhores motos à venda, com preços claros e procedência garantida.</p>
        </div>
    </section>

    <div class="benefits">
        <div class="benefits-inner">
            <?php foreach ($beneficios as $beneficio): ?>
                <div class="benefit">
                    <div class="benefit-icon">
                        <?php if ($beneficio['icone'] === 'card'): ?>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="5" width="20" height="14" rx="3"/><path d="M2 10h20"/></svg>
                        <?php elseif ($beneficio['icone'] === 'truck'): ?>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 7h11v9H2z"/><path d="M13 10h4l4 3v3h-8"/><circle cx="6" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>
                        <?php elseif ($beneficio['icone'] === 'shield'): ?>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg>
                        <?php else: ?>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M12 3l4.5 4.5L12 12 7.5 7.5z"/><path d="M12 12l4.5 4.5L12 21l-4.5-4.5z"/><path d="M3.5 12L8 7.5 12.5 12 8 16.5z"/></svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($beneficio['titulo'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <span><?= htmlspecialchars($beneficio['texto'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <section class="section" id="catalogo">
        <div class="section-head">
            <div class="section-eyebrow">Nossos produtos</div>
            <h2>Catálogo</h2>
            <p>Motos revisadas, com código de identificação, garantia e prontas para envio para todo o Brasil.</p>
        </div>

    <?php if (count($carros) === 0): ?>
        <div class="empty-state">
            <h2>Nenhuma moto disponível ainda</h2>
            <p style="color:#888;">Quando o admin adicionar motos, elas aparecerão aqui.</p>
        </div>
    <?php else: ?>
        <div class="cars-grid">
            <?php foreach ($carros as $car): ?>
                <?php
                    $carId = (int)($car['id'] ?? 0);
                    $imgUrl = imagemCarroUrl($carId);
                    $preco = null;
                    if (isset($car['preco_atual']) && $car['preco_atual'] !== null && $car['preco_atual'] !== '') {
                        $preco = (float)$car['preco_atual'];
                    } elseif (isset($car['preco_inicial']) && $car['preco_inicial'] !== null && $car['preco_inicial'] !== '') {
                        $preco = (float)$car['preco_inicial'];
                    } else {
                        $preco = (float)($car['preco'] ?? 0);
                    }
                    $nomeCarro = $car['nome'] ?? '';
                    $marcaCarro = $car['marca'] ?? '';
                    $modeloCarro = $car['modelo'] ?? '';
                    $anoCarro = $car['ano'] ?? '';
                    $corCarro = $car['cor'] ?? '';
                    $codigoCarro = motoCodigo($carId, (string)$marcaCarro);
                    $condicaoCarro = motoCondicao($car);
                    $garantiaCarro = motoGarantiaMeses($car);
                    $destaquesCarro = motoDestaques($car);
                    $tagsCarro = motoTags($car);
                    $aplicacoesCarro = trim($marcaCarro . ' ' . $modeloCarro);
                ?>
                <article class="car-card">
                    <div class="car-card-media">
                        <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($nomeCarro, ENT_QUOTES, 'UTF-8') ?>">
                        <div class="card-badges">
                            <span class="card-badge"><?= htmlspecialchars($condicaoCarro, ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="card-badge is-warranty">Garantia <?= $garantiaCarro ?> meses</span>
                            <span class="card-badge is-ship">Pronto para envio</span>
                        </div>
                    </div>
                    <div class="car-card-body">
                        <h2><?= htmlspecialchars($nomeCarro, ENT_QUOTES, 'UTF-8') ?></h2>
                        <div class="car-sub"><?= htmlspecialchars($marcaCarro, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($modeloCarro, ENT_QUOTES, 'UTF-8') ?> • <?= htmlspecialchars($anoCarro, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php if ($aplicacoesCarro !== ''): ?>
                            <div class="car-apps"><strong>Aplicações:</strong> <?= htmlspecialchars($aplicacoesCarro, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                        <div class="car-code">Código: <code><?= htmlspecialchars($codigoCarro, ENT_QUOTES, 'UTF-8') ?></code></div>
                        <div class="car-price">R$ <?= htmlspecialchars(number_format($preco, 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?></div>
                        <ul class="car-features">
                            <?php foreach ($destaquesCarro as $destaque): ?>
                                <li><?= htmlspecialchars($destaque, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if ($tagsCarro): ?>
                            <div class="car-tags">
                                <?php foreach ($tagsCarro as $tag): ?>
                                    <span class="spec-tag"><?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="car-desc"><?= htmlspecialchars(mb_strimwidth((string)($car['descricao'] ?? ''), 0, 120, '...'), ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="car-card-actions">
                            <button class="btn-contact" onclick="openContato(<?= $carId ?>, '<?= htmlspecialchars($nomeCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars($marcaCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars($modeloCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars($anoCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars($corCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars(number_format($preco, 2, ',', '.'), ENT_QUOTES) ?>', '<?= htmlspecialchars($codigoCarro, ENT_QUOTES) ?>')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                Solicitar Orçamento
                            </button>
                            <?php if ($perfil === 'admin'): ?>
                                <a class="btn-outline" href="index.php?controller=produto&action=index">Admin</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    </section>

    <section class="section" id="como-funciona">
        <div class="section-head">
            <div class="section-eyebrow">Passo a passo</div>
            <h2>Como Funciona</h2>
            <p>Da escolha da moto até a entrega na sua porta, em quatro passos simples.</p>
        </div>
        <div class="steps">
            <?php foreach ($comoFunciona as $indice => $passo): ?>
                <div class="step">
                    <div class="step-num"><?= $indice + 1 ?></div>
                    <h3><?= htmlspecialchars($passo['titulo'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p><?= htmlspecialchars($passo['texto'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section" id="garantia">
        <div class="section-head">
            <div class="section-eyebrow">Compre com segurança</div>
            <h2>Garantia</h2>
        </div>
        <div class="warranty">
            <div class="warranty-intro">
                <h3>Toda moto sai daqui <span>revisada</span></h3>
                <p>Cada moto passa por inspeção mecânica e revisão antes de ser anunciada. Você recebe exatamente o que viu no anúncio, com nota fiscal e prazo de garantia informado no selo do produto.</p>
                <a class="btn-whatsapp-top" href="<?= $whatsappLink !== '' ? htmlspecialchars($whatsappLink, ENT_QUOTES, 'UTF-8') : '#contato' ?>" <?= $whatsappLink !== '' ? 'target="_blank" rel="noopener"' : '' ?>>Tirar dúvidas</a>
            </div>
            <ul class="warranty-list">
                <?php foreach ($garantiaItens as $item): ?>
                    <li>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9.5"/></svg>
                        <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <section class="section" id="depoimentos">
        <div class="section-head">
            <div class="section-eyebrow">Nossos clientes</div>
            <h2>Depoimentos</h2>
        </div>
        <div class="reviews">
            <button class="reviews-arrow prev" onclick="scrollReviews(-1)" aria-label="Depoimentos anteriores">‹</button>
            <div class="reviews-track" id="reviewsTrack">
                <?php foreach ($depoimentos as $depoimento): ?>
                    <article class="review-card">
                        <div class="review-head">
                            <div class="review-avatar"><?= htmlspecialchars(mb_strtoupper(mb_substr($depoimento['nome'], 0, 1)), ENT_QUOTES, 'UTF-8') ?></div>
                            <div>
                                <div class="review-name"><?= htmlspecialchars($depoimento['nome'], ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="review-date"><?= htmlspecialchars($depoimento['data'], ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                            <div class="review-google" title="Avaliação do Google">G</div>
                        </div>
                        <div class="review-stars"><?= str_repeat('★', (int)$depoimento['nota']) ?></div>
                        <p class="review-text"><?= htmlspecialchars($depoimento['texto'], ENT_QUOTES, 'UTF-8') ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            <button class="reviews-arrow next" onclick="scrollReviews(1)" aria-label="Próximos depoimentos">›</button>
        </div>
        <div class="reviews-summary">
            Avaliação totalizada <strong>Google 4.9</strong> de 5, com base em <strong>52 avaliações</strong>
        </div>
    </section>

    <section class="section" id="faq">
        <div class="section-head">
            <div class="section-eyebrow">Perguntas e respostas</div>
            <h2>FAQ</h2>
        </div>
        <div class="faq-list">
            <?php foreach ($faqs as $indice => $faq): ?>
                <div class="faq-item" id="faqItem<?= $indice ?>">
                    <button class="faq-q" onclick="toggleFaq(<?= $indice ?>)" aria-expanded="false" aria-controls="faqAnswer<?= $indice ?>">
                        <?= htmlspecialchars($faq['pergunta'], ENT_QUOTES, 'UTF-8') ?>
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-a" id="faqAnswer<?= $indice ?>">
                        <p><?= htmlspecialchars($faq['resposta'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer class="site-footer" id="contato">
        <div class="footer-inner">
            <div class="footer-col">
                <h4>Garagem Motos</h4>
                <p>Motos revisadas, com garantia e envio para todo o Brasil.</p>
            </div>
            <div class="footer-col">
                <h4>Contato</h4>
                <?php if ($whatsappNumero !== ''): ?>
                    <a href="<?= htmlspecialchars($whatsappLink, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">WhatsApp: <?= htmlspecialchars($whatsappNumero, ENT_QUOTES, 'UTF-8') ?></a>
                <?php else: ?>
                    <p>WhatsApp indisponível no momento.</p>
                <?php endif; ?>
                <p>Atendimento de segunda a sábado, das 8h às 18h.</p>
            </div>
            <div class="footer-col">
                <h4>Navegação</h4>
                <a href="#catalogo">Catálogo</a>
                <a href="#como-funciona">Como Funciona</a>
                <a href="#garantia">Garantia</a>
                <a href="#faq">FAQ</a>
            </div>
            <div class="footer-col">
                <h4>Pagamento</h4>
                <p>Pix com 10% de desconto</p>
                <p>Cartão em até 6x sem juros</p>
                <p>Frete grátis acima de R$ 1.500,00</p>
            </div>
        </div>
        <div class="footer-bottom">© <?= date('Y') ?> Garagem Motos. Todos os direitos reservados.</div>
    </footer>

    <?php if ($whatsappLink !== ''): ?>
        <a class="wa-float" href="<?= htmlspecialchars($whatsappLink, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="Falar no WhatsApp">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm5.3 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.6-.1-.4-.1-1-.3-1.7-.6-2.9-1.3-4.8-4.3-5-4.5-.1-.2-.8-1.1-.8-2.1 0-1 .5-1.5.7-1.7.2-.2.5-.3.7-.3h.5c.2 0 .4 0 .6.4l.8 1.9c.1.2 0 .4-.1.5l-.3.4c-.1.1-.2.3-.1.5.2.4.7 1.2 1.4 1.8.9.8 1.6 1.1 1.8 1.2.2.1.4 0 .5-.1l.6-.7c.2-.2.4-.1.6 0l1.8.9c.4.2.4.3.4.5s0 .8-.2 1.3Z"/></svg>
        </a>
    <?php endif; ?>

    <div class="aviso-overlay" id="avisoOverlay">
        <div class="aviso-modal" role="dialog" aria-modal="true" aria-labelledby="avisoTitulo">
            <h2 id="avisoTitulo">Comunicado Importante</h2>
            <p class="aviso-sub">Proteja-se contra golpes. Leia atentamente:</p>
            <ul class="aviso-list">
                <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.3 3.8L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0z"/><line x1="12" y1="9" x2="12" y2="13"/><circle cx="12" cy="17" r="0.5"/></svg> A Garagem Motos possui apenas um site oficial (este).</li>
                <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.3 3.8L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0z"/><line x1="12" y1="9" x2="12" y2="13"/><circle cx="12" cy="17" r="0.5"/></svg> Todo orçamento é feito pelo WhatsApp oficial informado nesta página.</li>
                <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.3 3.8L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.8a2 2 0 0 0-3.4 0z"/><line x1="12" y1="9" x2="12" y2="13"/><circle cx="12" cy="17" r="0.5"/></svg> Não possuímos vínculo com outros sites ou domínios semelhantes.</li>
            </ul>
            <button class="btn-aviso" onclick="closeAviso()">Li e concordo</button>
        </div>
    </div>

    <div class="contato-overlay" id="contatoOverlay">
        <div class="contato-modal">
            <div class="contato-modal-header">
                <h2><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><rect x="7" y="2" width="10" height="20" rx="2"/><path d="M11 18h2"/></svg> Contato</h2>
                <button class="close-btn" onclick="closeContato()">✕</button>
            </div>

            <div id="contatoCarInfo" class="car-info" style="display:none;">
                <strong id="contatoCarName"></strong><br>
                <span id="contatoCarDetails" style="color:var(--muted); font-size:0.9rem;"></span><br>
                <span id="contatoCarCode" style="color:#888; font-size:0.82rem;"></span>
            </div>

            <?php if ($whatsappNumero === ''): ?>
                <div class="unavailable-msg">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" style="margin-bottom:12px;" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M8 15s1.5-2 4-2 4 2 4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                    <p>O contato via WhatsApp está indisponível no momento.</p>
                    <p style="font-size:0.85rem;">Tente novamente mais tarde.</p>
                </div>
            <?php else: ?>
                <form id="contatoForm" onsubmit="return sendWhatsApp(event)">
                    <input type="hidden" id="fieldCarroId" value="">
                    <input type="hidden" id="fieldMarca" value="">
                    <input type="hidden" id="fieldModelo" value="">
                    <input type="hidden" id="fieldAno" value="">
                    <input type="hidden" id="fieldCor" value="">
                    <input type="hidden" id="fieldPreco" value="">
                    <input type="hidden" id="fieldCodigo" value="">

                    <div class="form-group">
                        <label>Nome <span class="required">*</span></label>
                        <input type="text" id="fieldNome" required placeholder="Seu nome">
                    </div>
                    <div class="form-group">
                        <label>Telefone <span class="required">*</span></label>
                        <input type="text" id="fieldTelefone" required placeholder="(11) 99999-9999" oninput="maskTelefone(this)">
                    </div>
                    <div class="form-group">
                        <label>Mensagem <span style="color:var(--muted); font-size:0.8rem;">(opcional)</span></label>
                        <textarea id="fieldMensagem" placeholder="Digite sua mensagem..."></textarea>
                    </div>

                    <button type="submit" class="btn-enviar" id="btnEnviar">
                        Enviar para WhatsApp
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function setField(id, value) {
        const field = document.getElementById(id);
        if (field) field.value = value;
    }

    function openContato(id, nome, marca, modelo, ano, cor, preco, codigo) {
        document.getElementById('contatoCarInfo').style.display = 'block';
        document.getElementById('contatoCarName').textContent = nome;
        document.getElementById('contatoCarDetails').textContent = marca + ' ' + modelo + ' • ' + ano + ' • ' + cor + ' • R$ ' + preco;
        document.getElementById('contatoCarCode').textContent = 'Código: ' + codigo;

        setField('fieldCarroId', id);
        setField('fieldMarca', marca);
        setField('fieldModelo', modelo);
        setField('fieldAno', ano);
        setField('fieldCor', cor);
        setField('fieldPreco', preco);
        setField('fieldCodigo', codigo);

        document.getElementById('contatoOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeContato() {
        document.getElementById('contatoOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('contatoOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeContato();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeContato();
    });

    function maskTelefone(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value.length > 11) value = value.slice(0, 11);

        if (value.length > 7) {
            value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 7) + '-' + value.slice(7);
        } else if (value.length > 2) {
            value = '(' + value.slice(0, 2) + ') ' + value.slice(2);
        } else if (value.length > 0) {
            value = '(' + value;
        }
        input.value = value;
    }

    function sendWhatsApp(event) {
        event.preventDefault();

        const nome = document.getElementById('fieldNome').value.trim();
        const telefone = document.getElementById('fieldTelefone').value.trim();
        const mensagem = document.getElementById('fieldMensagem').value.trim();

        if (!nome) { alert('Informe seu nome.'); return false; }
        if (!telefone) { alert('Informe seu telefone.'); return false; }

        const marca = document.getElementById('fieldMarca').value;
        const modelo = document.getElementById('fieldModelo').value;
        const ano = document.getElementById('fieldAno').value;
        const cor = document.getElementById('fieldCor').value;
        const preco = document.getElementById('fieldPreco').value;
        const codigo = document.getElementById('fieldCodigo').value;

        const texto = 'Olá! Tenho interesse na moto abaixo.\n\n' +
            '🏍️ Moto: ' + marca + ' ' + modelo + '\n' +
            '🔖 Código: ' + codigo + '\n' +
            '📅 Ano: ' + ano + '\n' +
            '🎨 Cor: ' + cor + '\n' +
            '💰 Preço: R$ ' + preco + '\n\n' +
            '👤 Nome: ' + nome + '\n' +
            '📞 Telefone: ' + telefone + '\n\n' +
            '💬 Mensagem:\n' + (mensagem || '(Não informada)');

        const numero = <?= json_encode($whatsappNumero) ?>;
        window.open('https://wa.me/55' + numero + '?text=' + encodeURIComponent(texto), '_blank');

        closeContato();
        return false;
    }

    function toggleFaq(index) {
        const item = document.getElementById('faqItem' + index);
        const wasOpen = item.classList.contains('open');

        document.querySelectorAll('.faq-item.open').forEach(function (open) {
            open.classList.remove('open');
            open.querySelector('.faq-q').setAttribute('aria-expanded', 'false');
        });

        if (!wasOpen) {
            item.classList.add('open');
            item.querySelector('.faq-q').setAttribute('aria-expanded', 'true');
        }
    }

    function scrollReviews(direction) {
        const track = document.getElementById('reviewsTrack');
        const card = track.querySelector('.review-card');
        const step = card ? card.offsetWidth + 18 : 300;
        track.scrollBy({ left: step * direction, behavior: 'smooth' });
    }

    function closeAviso() {
        document.getElementById('avisoOverlay').classList.remove('open');
        document.body.style.overflow = '';
        try { localStorage.setItem('gbAvisoLido', '1'); } catch (e) {}
    }

    document.addEventListener('DOMContentLoaded', function () {
        let jaLeu = false;
        try { jaLeu = localStorage.getItem('gbAvisoLido') === '1'; } catch (e) {}

        if (!jaLeu) {
            document.getElementById('avisoOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    });
    </script>
</body>
</html>
