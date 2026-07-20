<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobi Money — Dépôt</title>
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
    <h1>Dépôt</h1>
  </div>

  <div class="app-content">
    <?php if (session()->getFlashdata('error')) : ?>
      <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('depot') ?>" method="post">
      <div class="field">
        <label for="montant">Montant à déposer</label>
        <div class="input-wrap">
          <input type="number" name="montant" id="montant" min="1" placeholder="Entrez le montant" value="<?= old('montant') ?>" required>
          <span class="suffix">Ar</span>
        </div>
      </div>

      <div class="card">
        <div class="detail-row"><span>Frais</span><span>0 Ar</span></div>
        <div class="detail-row"><span>Vous allez recevoir</span><span>0 Ar</span></div>
      </div>

      <div style="font-size:13px; color:var(--text-muted); margin-bottom:10px;">Points de dépôt disponibles</div>
      <div class="card-white">
        <div style="font-size:14px; font-weight:600;">Agent Antananarivo 001</div>
        <div style="font-size:12px; color:var(--text-muted);">Andoharanofotsy · 1.2 km</div>
      </div>

      <button type="submit" class="btn-primary">Confirmer le dépôt</button>
    </form>
  </div>

  <?= view('templates/bottom_nav', ['active' => 'depot']) ?>
</div>

</body>
</html>