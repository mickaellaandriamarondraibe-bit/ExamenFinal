<header class="header">

    <div class="header-left">

        <button
            class="btn btn-light d-lg-none"
            data-bs-toggle="offcanvas"
            data-bs-target="#sidebar">

            <i class="bi bi-list fs-4"></i>

        </button>

        <div>

            <h3 class="page-title">

                <?= $title ?? 'Dashboard' ?>

            </h3>

            <small class="text-muted">

                Mobile Money Administration

            </small>

        </div>

    </div>

    <div class="header-right">

        <div class="search-box d-none d-md-flex">

            <i class="bi bi-search"></i>

            <input
                type="text"
                class="form-control border-0 shadow-none"
                placeholder="Rechercher...">

        </div>

        <button class="btn btn-light notification">

            <i class="bi bi-bell"></i>

            <span class="badge bg-danger">2</span>

        </button>

        <div class="user-info">

            <img
                src="<?= base_url('images/avatar.png') ?>"
                alt="Avatar">

            <div>

                <strong>Opérateur</strong>

                <br>

                <small>Administrateur</small>

            </div>

        </div>

    </div>

</header>