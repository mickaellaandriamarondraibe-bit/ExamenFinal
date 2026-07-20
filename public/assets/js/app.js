// ===============================
// Mobile Money - app.js
// ===============================

document.addEventListener("DOMContentLoaded", function () {

    // ===============================
    // Menu actif
    // ===============================

    const currentPath = window.location.pathname;
    const links = document.querySelectorAll(".sidebar-link");

    links.forEach(function (link) {
        const linkPath = new URL(link.href).pathname;

        if (
            currentPath === linkPath ||
            currentPath.startsWith(linkPath + "/")
        ) {
            link.classList.add("active");
        }

        link.addEventListener("click", function () {
            links.forEach(function (item) {
                item.classList.remove("active");
            });

            link.classList.add("active");
        });
    });

    // ===============================
    // Confirmation suppression
    // ===============================

    document.querySelectorAll(".btn-delete").forEach(function (button) {
        button.addEventListener("click", function (event) {
            const confirmation = confirm(
                "Voulez-vous vraiment supprimer cet élément ?"
            );

            if (!confirmation) {
                event.preventDefault();
            }
        });
    });

    // ===============================
    // Animation de chargement
    // ===============================

    document.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            const href = link.getAttribute("href");

            if (
                href &&
                !href.startsWith("#") &&
                !href.startsWith("javascript:")
            ) {
                document.body.classList.add("page-loading");
            }
        });
    });
});

// ===============================
// Toast Bootstrap
// ===============================

function showToast(message, type = "success") {
    const toast = document.createElement("div");

    toast.className =
        "toast align-items-center text-bg-" +
        type +
        " border-0 position-fixed bottom-0 end-0 m-4";

    toast.style.zIndex = "9999";

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>

            <button
                type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast">
            </button>
        </div>
    `;

    document.body.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast);

    bsToast.show();

    toast.addEventListener("hidden.bs.toast", function () {
        toast.remove();
    });
}

// ===============================
// Loader
// ===============================

function showLoader() {
    document.body.classList.add("page-loading");
}

function hideLoader() {
    document.body.classList.remove("page-loading");
}

// ===============================
// Notifications
// ===============================

function success(message) {
    showToast(message, "success");
}

function error(message) {
    showToast(message, "danger");
}

function warning(message) {
    showToast(message, "warning");
}