<div class="bottom-nav">
  <a href="<?= site_url('accueil') ?>" class="<?= ($active ?? '') === 'accueil' ? 'active' : '' ?>">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/></svg>
    Accueil
  </a>
  <a href="<?= site_url('depot') ?>" class="<?= ($active ?? '') === 'depot' ? 'active' : '' ?>">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
    Dépôt
  </a>
  <a href="<?= site_url('retrait') ?>" class="<?= ($active ?? '') === 'retrait' ? 'active' : '' ?>">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
    Retrait
  </a>
  <a href="<?= site_url('transfert') ?>" class="<?= ($active ?? '') === 'transfert' ? 'active' : '' ?>">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 1l4 4-4 4M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4M21 13v2a4 4 0 0 1-4 4H3"/></svg>
    Transfert
  </a>
  <a href="<?= site_url('logout') ?>">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 3v18"/></svg>
    Déconnecter
  </a>
</div>
