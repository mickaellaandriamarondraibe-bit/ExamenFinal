<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobi Money — Accueil</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<div class="phone">
  <div class="app-header">
    <div>
      <div class="greeting">Bonjour 👋</div>
      <strong><?= esc(session()->get('telephone')) ?></strong>
    </div>
    <div style="flex:1"></div>
    <div class="bell">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
    </div>
  </div>

  <div class="app-content" style="margin-top:-16px;">
    <div class="card">
      <div class="solde-label">Solde actuel</div>
      <div class="solde-montant">
        <?= number_format($compte['solde'] ?? 0, 0, ',', ' ') ?> Ar
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      </div>
      <div class="solde-maj">Mis à jour : <?= date('d/m/Y H:i') ?></div>
    </div>

    <div style="font-size:13px; color:var(--text-muted); margin-bottom:10px;">Actions rapides</div>
    <div class="quick-actions">
      <a href="<?= site_url('depot') ?>">
        <span class="icon-circle">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
        </span>
        Dépôt
      </a>
      <a href="<?= site_url('retrait') ?>">
        <span class="icon-circle">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
        </span>
        Retrait
      </a>
      <a href="<?= site_url('transfert') ?>">
        <span class="icon-circle">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 1l4 4-4 4M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4M21 13v2a4 4 0 0 1-4 4H3"/></svg>
        </span>
        Transfert
      </a>
      <a href="<?= site_url('historique') ?>">
        <span class="icon-circle">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </span>
        Historique
      </a>
    </div>
  </div>

  <?= view('templates/bottom_nav', ['active' => 'accueil']) ?>
</div>

</body>
</html>