<aside class="sidebar">
    <div class="sidebar-logo">
        MOBILE MONEY
    </div>

    <nav class="sidebar-menu">
        <a href="<?= base_url('operateur/client') ?>" class="sidebar-link">
            <i class="bi bi-people"></i>
            <span>Clients</span>
        </a>

        <a href="<?= base_url('operateur/compte') ?>" class="sidebar-link">
            <i class="bi bi-wallet2"></i>
            <span>Comptes</span>
        </a>

        <a href="<?= base_url('operateur/transactions') ?>" class="sidebar-link">
            <i class="bi bi-arrow-left-right"></i>
            <span>Transactions</span>
        </a>

        <a href="<?= base_url('operateur/types-operations') ?>" class="sidebar-link">
            <i class="bi bi-list-ul"></i>
            <span>Types d'opérations</span>
        </a>

        <a href="<?= base_url('operateur/baremes-frais') ?>" class="sidebar-link">
            <i class="bi bi-cash-stack"></i>
            <span>Barèmes de frais</span>
        </a>

        <a href="<?= base_url('operateur/prefixes') ?>" class="sidebar-link">
            <i class="bi bi-phone"></i>
            <span>Préfixes</span>
        </a>

        <a href="<?= base_url('operateur/gains') ?>" class="sidebar-link">
            <i class="bi bi-graph-up"></i>
            <span>Situation des gains</span>
        </a>
    </nav>

    <div class="sidebar-logout">
        <a href="<?= base_url('logout') ?>" class="sidebar-link">
            <i class="bi bi-box-arrow-right"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>