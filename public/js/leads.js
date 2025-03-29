// IMAGE NAVBAR DROPDOWN ACTIVE
function profileShow() {
    const profile = document.querySelector('.nav-profile');
    profile.classList.toggle('active');
}
function navMenu() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
}

// Klik di luar dropdown untuk menutupnya
document.addEventListener("click", function (event) {
    const profile = document.querySelector('.nav-profile');
    const dropdown = document.querySelector('.dropdown');
    const button = document.querySelector('.nav-profile button');

    if (!profile.contains(event.target) && !button.contains(event.target)) {
        profile.classList.remove("active");
    }
});



function openModal(id) {
    document.getElementById(id).classList.add("show");
}
function openAddModal() { 
    let modal = document.getElementById('modal');
    let modalContent = document.querySelector(".modal-content");
    
    modal.style.display = "flex";
    setTimeout(() => { 
        modalContent.classList.add("show");
    }, 10);
    openModal('addModal');
}

function closeModal() {
    let modal = document.getElementById("modal");
    let modalContent = document.querySelector(".modal-content");

    modalContent.classList.remove("show");
    setTimeout(() => {
        modal.style.display = "none";
    }, 300); // Tunggu animasi slide down selesai sebelum hide modal
}


// Edit Modal
function openEditModal(id, name, position, company, email, status, source, location) {
    let modal = document.getElementById("modalEdit");
    let modalContent = modal.querySelector(".modal-content");

    // Set nilai input
    document.getElementById("editId").value = id;
    document.getElementById("editName").value = name;
    document.getElementById("editPosition").value = position;
    document.getElementById("editCompany").value = company;
    document.getElementById("editEmail").value = email;
    document.getElementById("editStatus").value = status;
    document.getElementById("editSource").value = source;
    document.getElementById("editLocation").value = location;

    modal.style.display = "flex";
    setTimeout(() => {
        modalContent.classList.add("show");
    }, 10);
}
function closeEditModal() {
    let modal = document.getElementById("modalEdit");
    let modalContent = modal.querySelector(".modal-content");
    modalContent.classList.remove("show");

    setTimeout(() => {
        modal.style.display = "none";
        document.querySelector("#modalEdit form").reset(); // Reset form saat modal ditutup
    }, 300);
}


window.onclick = function(event) {
    let modal = document.getElementById("modal");
    if (event.target === modal) {
        closeModal();
    }
}

// Delete modal
function deleteLead(id) {
    let modal = document.getElementById("modalDelete");
    let modalContent = modal.querySelector(".modal-content");

    // Set ID yang akan dihapus
    document.getElementById("deleteId").value = id;

    // Tampilkan modal dengan efek animasi
    modal.style.display = "flex";
    setTimeout(() => {
        modalContent.classList.add("show");
    }, 10);
}

function closeDeleteModal(modalId) {
    let modal = document.getElementById(modalId);
    let modalContent = modal.querySelector(".modal-content");

    // Hilangkan efek animasi dulu
    modalContent.classList.remove("show");

    // Tunggu animasi selesai, lalu sembunyikan modal
    setTimeout(() => {
        modal.style.display = "none";
    }, 300);
}

window.onclick = function(event) {
    let modals = document.querySelectorAll(".modal");
    modals.forEach((modal) => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
};

// Search
document.getElementById("search").addEventListener("keyup", function () {
let searchValue = this.value.toLowerCase();
let rows = document.querySelectorAll("#dataLeads tr");

rows.forEach(row => {
    let name = row.querySelector("td:first-child strong").innerText.toLowerCase();
    let email = row.querySelector("td:nth-child(3)").innerText.toLowerCase();

    if (name.includes(searchValue) || email.includes(searchValue)) {
        row.style.display = "";
    } else {
        row.style.display = "none";
    }
 });
});

function searchLeads() {
let input = document.getElementById("search").value.toLowerCase();
let rows = document.querySelectorAll("#dataLeads tr");

rows.forEach(row => {
    let name = row.cells[0].innerText.toLowerCase();
    let email = row.cells[2].innerText.toLowerCase();

    if (name.includes(input) || email.includes(input)) {
        row.style.display = "";
    } else {
        row.style.display = "none";
    }
});
}