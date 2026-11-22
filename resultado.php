<?php
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_samesite', 'Lax');
session_start();
header('Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0');
header('Referrer-Policy: no-referrer');
header('X-Robots-Tag: noindex, nofollow, noarchive');
header('X-Content-Type-Options: nosniff');
header('Content-Security-Policy: frame-ancestors \'none\'');
$r = $_SESSION['alegria_resultado'] ?? null;
$valid = is_array($r) && isset($r['criado']) && time() - $r['criado'] < 1800;
if (isset($_GET['dados'])) {
  header('Content-Type: application/json; charset=utf-8');
  if (!$valid) {
    http_response_code(403);
    echo json_encode(['ok' => false]);
    exit;
  }
  echo json_encode([
    'ok' => true,
    'phq9' => $r['phq9'] ?? null,
    'gad7' => $r['gad7'] ?? null,
    'alerta' => !empty($r['alerta'])
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}
if ($valid) {
  header('Location: clinica.html', true, 302);
  exit;
}
http_response_code(403);
function faixa($kind, $n) {
  if ($kind === 'phq') {
    if ($n <= 4) return ['Sintomas mínimos', 'Suas respostas sobre humor não indicam sinais relevantes nas últimas duas semanas.'];
    if ($n <= 9) return ['Sintomas leves', 'Suas respostas mostram alguns sinais leves de humor mais baixo. Vale continuar de olho em como você tem se sentido.'];
    if ($n <= 14) return ['Sintomas moderados', 'Suas respostas mostram sinais moderados de humor deprimido. Conversar com um profissional de saúde mental pode ajudar a entender melhor o que está acontecendo.'];
    if ($n <= 19) return ['Sintomas moderadamente graves', 'Suas respostas mostram sinais mais intensos, moderadamente graves. Recomendamos buscar avaliação profissional em breve.'];
    return ['Sintomas graves', 'Suas respostas mostram sinais consistentes com sofrimento significativo. Buscar ajuda profissional o quanto antes é o passo mais importante agora.'];
  }
  if ($n <= 4) return ['Ansiedade mínima', 'Suas respostas sobre ansiedade não indicam sinais relevantes nas últimas duas semanas.'];
  if ($n <= 9) return ['Ansiedade leve', 'Suas respostas mostram sinais leves de ansiedade. Vale observar como isso evolui.'];
  if ($n <= 14) return ['Ansiedade moderada', 'Suas respostas mostram sinais moderados de ansiedade. Uma conversa com um profissional pode trazer mais clareza.'];
  return ['Ansiedade grave', 'Suas respostas mostram sinais de ansiedade grave. Recomendamos buscar apoio profissional o quanto antes.'];
}
?>
<!DOCTYPE html><html lang="pt-BR"><head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PRMS5J4L');</script>
<!-- End Google Tag Manager -->
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow,noarchive"><meta name="referrer" content="no-referrer"><title>Seu resultado | Sala de Escuta</title><link rel="icon" type="image/png" href="favicon.png"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="styles.css"></head><body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PRMS5J4L"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<header class="header"><div class="header-inner"><a href="https://drguidoboabaid.com.br/" class="brand" aria-label="Site do Dr. Guido Boabaid"><img src="assets/logo.webp" width="374" height="83" alt="Dr. Guido Boabaid — Psiquiatria de Precisão"></a><span class="header-divider"></span><span class="product-name">Sala de Escuta</span><a class="header-link" href="#clinic">Fale com a clínica <span aria-hidden="true">↓</span></a></div></header>

<main id="main"><section class="experience wrap" aria-label="Seu resultado"><div class="journey-layout"><aside class="journey-aside"><div class="eyebrow">SALA DE ESCUTA</div><h2>Um passo<br>de cada vez.</h2><p>Não existem respostas certas ou erradas. Escolha a que melhor representa seus últimos 14 dias.</p><ol class="journey-steps"><li><span>1</span> Seu questionário</li><li><span>2</span> Seus dados</li><li class="active" aria-current="step"><span>3</span> Seu resultado</li></ol></aside><div class="journey-main" id="stage">
<?php if (!$valid): ?>
<section class="results-panel"><div class="eyebrow">03 / SEU RESULTADO</div><h2>Resultado indisponível</h2><p>Por segurança, este resultado só pode ser consultado durante um período limitado após o envio. Para uma nova avaliação, inicie o questionário novamente.</p><a class="button primary" href="./">Voltar ao questionário</a></section>
<?php else: ?>
<section class="results-panel"><div class="eyebrow">03 / SEU RESULTADO</div><h2 id="result-title">Um olhar para o seu momento.</h2><p class="panel-description">Estas pontuações refletem suas respostas sobre as últimas duas semanas.</p>
<?php if (!empty($r['alerta'])): ?><aside class="safety" role="note"><h3>Você merece cuidado e apoio.</h3><p>Essa resposta merece atenção, independentemente da pontuação. Converse com um profissional de saúde. Se houver risco imediato de você se machucar, procure um serviço de emergência e peça a alguém de confiança para ficar com você. Não espere o contato da clínica.</p><div class="safety-links"><a href="tel:188">CVV · 188</a><a href="https://www.cvv.org.br/chat/" target="_blank" rel="noopener">Conversar com o CVV ↗</a></div><p style="margin-top:10px">Apoio emocional gratuito, 24 horas. Este formulário não é monitorado em tempo real.</p></aside><?php endif; ?>
<?php foreach (['phq9'=>'phq','gad7'=>'gad'] as $field=>$kind): if ($r[$field] === null) continue; $n=(int)$r[$field]; $max=$kind==='phq'?27:21; $b=faixa($kind,$n); ?>
<article class="result-card"><div class="result-card-top"><div><div class="result-kicker"><?= $kind==='phq'?'PHQ-9':'GAD-7' ?></div><h3><?= $kind==='phq'?'Seu humor':'Sua ansiedade' ?></h3></div><div class="score"><?= $n ?><small> / <?= $max ?></small></div></div><span class="result-band"><?= htmlspecialchars($b[0], ENT_QUOTES, 'UTF-8') ?></span><div class="score-bar" aria-hidden="true"><span class="score-pin" style="left:<?= max(1,min(99,$n/$max*100)) ?>%"></span></div><div class="score-labels" aria-hidden="true"><span>0 pontos</span><span><?= $max ?> pontos</span></div><p><?= htmlspecialchars($b[1], ENT_QUOTES, 'UTF-8') ?></p></article>
<?php endforeach; ?>
<p class="result-explainer"><strong>Você é mais do que uma pontuação.</strong><br>Este resultado não confirma nem descarta um diagnóstico. Seu momento de vida, sua história e a avaliação de um profissional ajudam a compreender o que você está sentindo.</p>
<section class="clinic-cta" id="clinic" aria-labelledby="clinic-title">
  <div class="clinic-cta-copy">
    <div class="eyebrow"><span class="small-line"></span> CLÍNICA DR. GUIDO BOABAID</div>
    <h3 id="clinic-title">Cada pessoa tem uma história. O cuidado considera essa história.</h3>
    <p>A psiquiatria de precisão reúne conhecimento científico, escuta qualificada e atenção à história de cada pessoa. A avaliação clínica considera aspectos biológicos, emocionais e contextuais para orientar um plano de cuidado personalizado.</p>
    <div class="clinic-highlights" aria-label="Diferenciais da clínica">
      <span>Equipe médica orientada pelo Dr. Guido</span>
      <span>Casos da equipe supervisionados semanalmente</span>
      <span>Farmacogenética quando indicada pelo médico</span>
    </div>
  </div>
  <div class="clinic-cta-action">
    <span class="clinic-step">SEU PRÓXIMO PASSO</span>
    <h4>Converse com a equipe sobre o agendamento.</h4>
    <p>Chame a clínica pelo WhatsApp para tirar dúvidas e receber orientação sobre como marcar uma consulta.</p>
    <a href="https://api.whatsapp.com/send/?phone=554898510053&amp;text=Ol%C3%A1%2C%20acabei%20de%20fazer%20a%20avalia%C3%A7%C3%A3o%20da%20Sala%20de%20Escuta%20e%20gostaria%20de%20saber%20como%20agendar%20uma%20consulta%20com%20a%20Cl%C3%ADnica%20Dr.%20Guido%20Boabaid." target="_blank" rel="noopener" class="button clinic-button">Falar com a clínica no WhatsApp <span aria-hidden="true">↗</span></a>
    <span class="clinic-note">O contato não confirma um agendamento. A equipe orientará você sobre a disponibilidade.</span>
  </div>
</section><p class="result-status">Seu contato e as pontuações foram encaminhados ao sistema de atendimento. Isso não confirma um agendamento.</p><div class="result-actions"><button class="text-button" id="print-result" onclick="window.print()">Imprimir resultado ↗</button></div><details class="references"><summary>Entenda as faixas de pontuação</summary><p>Humor (PHQ-9): 0–4 mínimos; 5–9 leves; 10–14 moderados; 15–19 moderadamente graves; 20–27 graves.</p><p>Ansiedade (GAD-7): 0–4 mínima; 5–9 leve; 10–14 moderada; 15–21 grave.</p><p>Referências dos instrumentos:</p><ul><li><a href="https://doi.org/10.1046/j.1525-1497.2001.016009606.x" target="_blank" rel="noopener">Kroenke, Spitzer e Williams — PHQ-9 (2001)</a></li><li><a href="https://doi.org/10.1001/archinte.166.10.1092" target="_blank" rel="noopener">Spitzer et al. — GAD-7 (2006)</a></li></ul></details></section>
<?php endif; ?></div></div></section></main>
<footer class="footer"><div class="wrap"><div class="footer-main"><div><img src="assets/logo.webp" class="footer-logo" width="374" height="83" alt="Dr. Guido Boabaid — Psiquiatria de Precisão"><p>Psiquiatria de precisão com cuidado personalizado.</p></div><div class="footer-contact"><span class="eyebrow">FALE COM A EQUIPE</span><a href="https://api.whatsapp.com/send/?phone=554898510053" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" class="contact-icon" fill="currentColor"><path d="M20.52 3.48A11.91 11.91 0 0 0 12.04 0C5.45 0 .08 5.37.08 11.97c0 2.11.55 4.17 1.6 6L0 24l6.2-1.63a11.95 11.95 0 0 0 5.83 1.49h.01C18.64 23.86 24 18.49 24 11.9c0-3.2-1.24-6.2-3.48-8.42ZM12.04 21.84a9.9 9.9 0 0 1-5.06-1.39l-.36-.21-3.68.97.98-3.59-.24-.37a9.9 9.9 0 0 1-1.52-5.28c0-5.49 4.46-9.95 9.95-9.95a9.9 9.9 0 0 1 7.04 2.92 9.86 9.86 0 0 1 2.91 7.04c0 5.49-4.47 9.95-9.96 9.95Zm5.46-7.45c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.39-1.47-.88-.79-1.48-1.77-1.65-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49s1.06 2.89 1.21 3.09c.15.2 2.09 3.19 5.07 4.47.71.3 1.27.49 1.7.63.71.22 1.36.19 1.87.11.57-.09 1.76-.72 2-1.42.25-.7.25-1.3.18-1.42-.08-.12-.28-.2-.57-.35Z"/></svg><span>(48) 99851-0053</span></a><a href="mailto:contato@guidoboabaid.com.br"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" class="contact-icon" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg><span>contato@guidoboabaid.com.br</span></a></div></div><div class="footer-bottom"><span>CRM/SC 5432 · CRM/SP 196454 · RQE 3845</span><span>© <span id="year">2026</span> Dr. Guido Boabaid May</span></div><p class="footer-disclaimer">PHQ-9 e GAD-7 são instrumentos de rastreamento, não de diagnóstico. Esta página e o formulário não oferecem atendimento de urgência nem monitoramento em tempo real.</p></div></footer>

</body></html>