<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobi Money — Historique</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<div class="phone">
  <div class="app-header simple">
    <a href="<?= site_url('accueil') ?>" class="back-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
    <h1>Historique</h1>
  </div>

  <div class="app-content">
    <?php if (empty($transactions)) : ?>
      <div class="empty-state">Aucune opération pour le moment.</div>
    <?php else : ?>
      <?php foreach ($transactions as $t) : ?>
        <div class="hist-item">
          <span class="icon-circle <?= $t['sens'] ?>">
            <?php if ($t['sens'] === 'in') : ?>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            <?php else : ?>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
            <?php endif; ?>
          </span>
          <div class="info">
            <div class="type"><?= esc($t['type_operation']) ?></div>
            <div class="ref">Réf : TRX<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT) ?></div>
          </div>
          <div class="amount">
            <div class="val <?= $t['sens'] ?>">
              <?= $t['sens'] === 'in' ? '+' : '-' ?><?= number_format($t['montant'], 0, ',', ' ') ?> Ar
            </div>
            <div class="time"><?= date('H:i', strtotime($t['date_transaction'])) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <?= view('templates/bottom_nav', ['active' => 'historique']) ?>
</div>

</body>
</html>