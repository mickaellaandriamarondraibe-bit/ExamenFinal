<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mobi Money — Transfert</title>
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>

<body>

<div class="phone">

  <div class="app-header simple">
    <a href="<?= site_url('accueil') ?>" class="back-btn">←</a>
    <h1>Transfert</h1>
  </div>

  <div class="app-content">

    <?php if (session()->getFlashdata('error')) : ?>
      <div class="alert alert-error">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <form action="<?= site_url('transfert') ?>" method="post">

      <?= csrf_field() ?>

      <div class="field">
        <label for="telephone">Numéro destinataire</label>
        <input
          type="text"
          name="telephone"
          id="telephone"
          value="<?= esc(old('telephone')) ?>"
          required
        >
      </div>

      <div class="field">
        <label for="montant">Montant</label>
        <div class="input-wrap">
          <input
            type="number"
            name="montant"
            id="montant"
            min="1"
            value="<?= esc(old('montant')) ?>"
            required
          >
          <span class="suffix">Ar</span>
        </div>
      </div>

      <div class="field">
        <label for="inclure_frais_retrait">
          <input
            type="checkbox"
            name="inclure_frais_retrait"
            id="inclure_frais_retrait"
            value="1"
            <?= old('inclure_frais_retrait') ? 'checked' : '' ?>
          >
          Inclure les frais de retrait
        </label>
      </div>

      <div class="card">

        <div class="detail-row">
          <span>Frais de transfert</span>
          <span id="frais">0 Ar</span>
        </div>

        <div class="detail-row">
          <span>Frais de retrait</span>
          <span id="frais-retrait">0 Ar</span>
        </div>

        <div class="detail-row">
          <span>Commission</span>
          <span id="commission">0 Ar</span>
        </div>

        <div class="detail-row">
          <span>Montant reçu</span>
          <span id="recevra">0 Ar</span>
        </div>

        <div class="detail-row total">
          <span>Total débité</span>
          <span id="total">0 Ar</span>
        </div>

      </div>

      <button type="submit" class="btn-primary">
        Confirmer
      </button>

      <a href="<?= site_url('transfert-multiple') ?>" class="btn-secondary">
        Transfert multiple
      </a>

    </form>

  </div>

  <?= view('templates/bottom_nav', ['active' => 'transfert']) ?>

</div>

<script>
const montant = document.getElementById('montant');
const telephone = document.getElementById('telephone');
const retrait = document.getElementById('inclure_frais_retrait');

const frais = document.getElementById('frais');
const fraisRetrait = document.getElementById('frais-retrait');
const commission = document.getElementById('commission');
const recevra = document.getElementById('recevra');
const total = document.getElementById('total');

let timer;

function formatAr(valeur) {
  return Number(valeur || 0).toLocaleString('fr-FR') + ' Ar';
}

function reset() {
  frais.textContent = '0 Ar';
  fraisRetrait.textContent = '0 Ar';
  commission.textContent = '0 Ar';
  recevra.textContent = '0 Ar';
  total.textContent = '0 Ar';
}

function calculer() {
  clearTimeout(timer);

  const valeur = Number(montant.value);
  const numero = telephone.value.replace(/\D/g, '');

  if (valeur <= 0 || numero.length !== 10) {
    reset();
    return;
  }

  timer = setTimeout(async () => {
    const data = new URLSearchParams();

    data.append('montant', valeur);
    data.append('telephone', numero);
    data.append('type_operation_id', 3);
    data.append('inclure_frais_retrait', retrait.checked ? 1 : 0);
    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    try {
      const response = await fetch('<?= site_url('calculer-frais') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data.toString()
      });

      const result = await response.json();

      if (result.error) {
        reset();
        return;
      }

      frais.textContent = formatAr(result.frais);
      fraisRetrait.textContent = formatAr(result.frais_retrait);
      commission.textContent = formatAr(result.commission);
      recevra.textContent = formatAr(result.montant_recu);
      total.textContent = formatAr(result.total);

    } catch {
      reset();
    }
  }, 400);
}

montant.addEventListener('input', calculer);
telephone.addEventListener('input', calculer);
retrait.addEventListener('change', calculer);
</script>

</body>
</html>