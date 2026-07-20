<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobile Money — Mon solde</title>
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
    <h1>Mon Solde</h1>
  </div>

  <div class="app-content">
    <?php if (session()->getFlashdata('success')) : ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card" style="background: var(--green-dark); color: var(--white);">
      <div class="solde-label" style="color: rgba(255,255,255,0.75);">Solde actuel</div>
      <div class="solde-montant" style="color: var(--white);">
        <?= number_format($compte['solde'] ?? 0, 0, ',', ' ') ?> Ar
      </div>
      <div class="solde-maj" style="color: rgba(255,255,255,0.6);">Mis à jour : <?= date('d/m/Y H:i') ?></div>
    </div>

    <div class="card-white">
      <div class="detail-row"><span>Solde disponible</span><span><?= number_format($compte['solde'] ?? 0, 0, ',', ' ') ?> Ar</span></div>
      <div class="detail-row"><span>Solde en attente</span><span>0 Ar</span></div>
    </div>
  </div>

  <?= view('templates/bottom_nav', ['active' => 'solde']) ?>
</div>

</body>
</html>