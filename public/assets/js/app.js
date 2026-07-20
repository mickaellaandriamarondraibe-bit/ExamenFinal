// ===============================
// Mobile Money - app.js
// ===============================

document.addEventListener("DOMContentLoaded", function () {

    // ===============================
    // Active menu
    // ===============================

    const current = window.location.pathname;

    document.querySelectorAll(".sidebar-menu a").forEach(link => {

        if(current === new URL(link.href).pathname){

            link.classList.add("active");

        }

    });

    // ===============================
    // Confirmation suppression
    // ===============================

    document.querySelectorAll(".btn-delete").forEach(btn => {

        btn.addEventListener("click", function(e){

            if(!confirm("Voulez-vous vraiment supprimer cet élément ?")){

                e.preventDefault();

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
        `toast align-items-center text-bg-${type} border-0 position-fixed bottom-0 end-0 m-4`;

    toast.style.zIndex = "9999";

    toast.innerHTML = `

        <div class="d-flex">

            <div class="toast-body">

                ${message}

            </div>

            <button
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast">

            </button>

        </div>

    `;

    document.body.appendChild(toast);

    const bsToast = new bootstrap.Toast(toast);

    bsToast.show();

    toast.addEventListener("hidden.bs.toast", () => {

        toast.remove();

    });

}



// ===============================
// Loader
// ===============================

function showLoader(){

    document.body.style.cursor = "wait";

}

function hideLoader(){

    document.body.style.cursor = "default";

}



// ===============================
// Notifications
// ===============================

function success(message){

    showToast(message,"success");

}

function error(message){

    showToast(message,"danger");

}

function warning(message){

    showToast(message,"warning");

}