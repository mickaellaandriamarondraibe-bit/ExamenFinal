<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobile Money — Accueil</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<?php
$telephone = session()->get('telephone');
$solde = $compte['solde'] ?? 0;
$numeroCompte = $compte['numero_compte'] ?? '-';
$statut = $compte['statut'] ?? 'ACTIF';
?>

<div class="phone">
  <div class="home-header">
    <div class="home-user">
      <span>Compte client</span>
      <strong><?= esc($telephone) ?></strong>
    </div>
    <a href="<?= site_url('historique') ?>" class="home-bell">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
    </a>
  </div>

  <div class="app-content home-content">
    <div class="home-balance-card">
      <div class="home-balance-top">
        <span>Solde disponible</span>
        <span class="status-pill"><?= esc($statut) ?></span>
      </div>
      <div class="home-balance-amount"><?= number_format($solde, 0, ',', ' ') ?> Ar</div>
      <div class="home-balance-footer">
        <span><?= esc($numeroCompte) ?></span>
        <span><?= date('d/m/Y H:i') ?></span>
      </div>
    </div>

    <div class="home-section-title">Actions rapides</div>
    <div class="home-actions">
      <a href="<?= site_url('depot') ?>" class="home-action">
        <span class="home-action-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
        </span>
        <span>
          <strong>Dépôt</strong>
          <small>Ajouter de l'argent</small>
        </span>
      </a>
      <a href="<?= site_url('retrait') ?>" class="home-action">
        <span class="home-action-icon yellow">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
        </span>
        <span>
          <strong>Retrait</strong>
          <small>Sortir du cash</small>
        </span>
      </a>
      <a href="<?= site_url('transfert') ?>" class="home-action">
        <span class="home-action-icon blue">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 1l4 4-4 4M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4M21 13v2a4 4 0 0 1-4 4H3"/></svg>
        </span>
        <span>
          <strong>Transfert</strong>
          <small>Envoyer vite</small>
        </span>
      </a>
      <a href="<?= site_url('transfert-multiple') ?>" class="home-action">
        <span class="home-action-icon red">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 3h5v5"/><path d="M8 3H3v5"/><path d="M21 3l-7 7"/><path d="M3 3l7 7"/><path d="M12 14v7"/><path d="M8 17l4 4 4-4"/></svg>
        </span>
        <span>
          <strong>Multiple</strong>
          <small>Plusieurs numéros</small>
        </span>
      </a>
    </div>

    <div class="home-panel">
      <div class="home-panel-head">
        <span>Dernière opération</span>
        <a href="<?= site_url('historique') ?>">Voir tout</a>
      </div>

      <?php if (empty($derniereTransaction)) : ?>
        <div class="home-empty">Aucune opération pour le moment.</div>
      <?php else : ?>
        <div class="home-last-operation">
          <span class="home-action-icon <?= $derniereTransaction['sens'] === 'in' ? '' : 'red' ?>">
            <?php if ($derniereTransaction['sens'] === 'in') : ?>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            <?php else : ?>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
            <?php endif; ?>
          </span>
          <div>
            <strong><?= esc($derniereTransaction['type_operation']) ?></strong>
            <small>Réf : TRX<?= str_pad($derniereTransaction['id'], 5, '0', STR_PAD_LEFT) ?></small>
          </div>
          <b class="<?= $derniereTransaction['sens'] === 'in' ? 'amount-in' : 'amount-out' ?>">
            <?= $derniereTransaction['sens'] === 'in' ? '+' : '-' ?><?= number_format($derniereTransaction['montant'], 0, ',', ' ') ?> Ar
          </b>
        </div>
      <?php endif; ?>
    </div>

    <a href="<?= site_url('historique') ?>" class="home-history-link">
      <span>
        <span class="icon-circle">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </span>
        Historique
      </span>
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
  </div>

  <?= view('templates/bottom_nav', ['active' => 'accueil']) ?>
</div>

</body>
</html>
