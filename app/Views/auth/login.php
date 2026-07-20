<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobi Money — Connexion</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<div class="phone">
  <div class="app-content" style="padding-bottom:20px;">
    <div class="login-wrap">
      <div class="logo-circle">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="5" y="2" width="14" height="20" rx="2"/>
          <line x1="12" y1="18" x2="12.01" y2="18"/>
        </svg>
      </div>
      <div class="brand">MOBI MONEY</div>
      <div class="tagline">Votre argent, partout.</div>

      <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form action="<?= site_url('login') ?>" method="post">
        <div class="field">
          <label for="telephone">Entrez votre numéro de téléphone</label>
          <input
            type="text"
            name="telephone"
            id="telephone"
            placeholder="Ex: 033 12 345 67"
            value="<?= old('telephone') ?>"
          >
        </div>
        <button type="submit" class="btn-primary">Se connecter</button>
      </form>

      <p class="legal">
        En continuant, vous acceptez nos<br>
        <strong>Conditions d'utilisation</strong>
      </p>
    </div>
  </div>
</div>

</body>
</html>