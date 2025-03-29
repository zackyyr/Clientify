<?php
session_start();
require_once "../config/config.php";
require_once "../controllers/auth_guard.php"; 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan user sudah login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data user dari session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Query untuk ambil invoice
$query = $conn->prepare("SELECT invoices.id, 
                                invoices.lead_id, 
                                COALESCE(leads.name, invoices.name) AS name, 
                                COALESCE(leads.email, invoices.contact) AS email, 
                                invoices.services, 
                                invoices.invoice_number, 
                                invoices.amount, 
                                invoices.billing_date, 
                                invoices.status, 
                                invoices.due_date 
                         FROM invoices 
                         LEFT JOIN leads ON invoices.lead_id = leads.id 
                         WHERE invoices.user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

// Query untuk ambil leads milik user
$queryLeads = $conn->prepare("SELECT id, name, email FROM leads WHERE user_id = ?");
$queryLeads->bind_param("i", $user_id);
$queryLeads->execute();
$resultLeads = $queryLeads->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientify - Invoicing</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/public.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../public/css/invoicing.css?v=<?php echo time(); ?>">


    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../../public/assets/auth-logo.svg" type="image/x-icon">

</head>
<body>
        <div class="invoicing-wrapper main-container">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-container">
                    <div class="nav-logo">
                        <img src="../../public/assets/auth-logo_glow.svg" alt="">
                    </div>
                        <button class="hamburger" onclick="navMenu()"><i class="ri-menu-2-line"></i></button>
                    <div class="nav-links">
                        <ul>
                            <li><a href="lead-management.php">Lead Management</a></li>
                            <li><a href="" class="link-active">Invoicing</a></li>
                            <li><a href="">Proposal</a></li>
                        </ul>
                    </div>
                    <div class="nav-profile">
                        <p class="username"><?php echo htmlspecialchars($username); ?></p>
                        <button onclick="profileShow()"><img src="../../public/assets/profile.svg" alt="Profile"></button>
                        <ul class="dropdown" id="dropdown">
                            <li><button><i class="ri-settings-line"></i>Settings</button></li>
                            <li><button class="logout"><i class="ri-logout-box-line"></i><a href="../controllers/logout.php">Logout</a></button></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Invoicing Section -->
            <div class="invoicing main-header">
                <div class="header-text">
                    <h1>Invoicing</h1>
                    <p>Effortlessly handle your billing and invoices.</p>
                </div>
                <div class="header-add">
                    <select name="" id="">
                        <option value="">This month</option>
                        <option value="">Last Month</option>
                        <option value="">This year</option>
                    </select>
                    <i class="ri-arrow-down-s-line"></i>
                </div>
            </div>

            <div class="overview">
                <div class="overview-container">
                    <!-- Created Invoices -->
                    <div class="overview__card">
                        <p class="title-card">Created Invoices</p>
                        <div class="overview__data">
                            <h4>160</h4>
                            <p class="plus">+0.91%</p>
                        </div>
                    </div>
                    <!-- Pending Invoices -->
                    <div class="overview__card">
                        <p class="title-card">Pending Invoices</p>
                        <div class="overview__data">
                            <h4>40</h4>
                            <p class="minus">-2.50%</p>
                        </div>
                    </div>
                    <!-- Paid Invoices -->
                    <div class="overview__card">
                        <p class="title-card">Paid Invoices</p>
                        <div class="overview__data">
                            <h4>40</h4>
                            <p class="plus">+0.91%</p>
                        </div>
                    </div>
                    <!-- Overdue Invoices -->
                    <div class="overview__card">
                        <p class="title-card">Overdue Invoices</p>
                        <div class="overview__data">
                            <h4>38</h4>
                            <p class="minus">-0.53%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="items">
                <div class="items-container">
                    <!-- Heading Items -->
                    <div class="items-heading-wrapper">
                        <div class="items-header">
                            <div class="items-searchbar">
                                <i class="ri-search-line"></i>
                                <input type="text" id="search" placeholder="Search by  invoice ID ,name, or email address" onkeyup="searchLeads()">
                            </div>
                        </div>
    
                        <div class="items-actions">
                            <div class="items-actions__filtering">
                                <button class="filter-active">All <span>160</span></button>
                                <button>Pending <span>40</span></button>
                                <button>Paid <span>40</span></button>
                                <button>Overdue <span>38</span></button>
                            </div>

                            <div class="items-actions__actions">
                                <!-- Filter tanggal dari kapan ke kapan -->
                                <div class="date-filter">
                                    <span id="date-range"></span>
                                    <i class="ri-calendar-line"></i>
                                    <input type="date" id="start-date">
                                    <input type="date" id="end-date">
                                </div>

                                <button class="export" onclick="exportAll()"><i class="ri-upload-2-line"></i> Export</button>
                                <button class="add-btn" onclick="addInvoice()"><i class="ri-add-line"></i> Add Invoice</button>
                            </div>

                        </div>
                    </div>

                    <div class="items-data">
                        <table>
                            <thead>
                                <tr class="table-header">
                                    <th>Name</th>
                                    <th>Services</th>
                                    <th>Invoice ID</th>
                                    <th>Amount</th>
                                    <th>Billing Date</th> <!-- ✅ Tambahin ini -->
                                    <th>Due Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="dataLeads">
                                <?php while ($row = $result->fetch_assoc()): 
                                    $statusClass = strtolower(str_replace(' ', '-', $row['status'])); ?>
                                    <tr>
                                        <td>
                                            <div class="name-info">
                                                <strong><?= htmlspecialchars($row['name']) ?></strong>
                                                <span><?= htmlspecialchars($row['email']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($row['services']) ?></td>
                                        <td><?= htmlspecialchars($row['invoice_number']) ?></td>
                                        <td><?= htmlspecialchars($row['amount']) ?></td>
                                        <td><?= htmlspecialchars($row['billing_date']) ?></td> <!-- ✅ Tambahin ini -->
                                        <td><?= htmlspecialchars($row['due_date']) ?></td>
                                        <td><span class='status <?= $statusClass ?>'><?= htmlspecialchars($row['status']) ?></span></td>
                                        <td>
                                            <button class="download" onclick="openDownload()">
                                                <i class="ri-download-2-line"></i>
                                            </button>

                                            <button class='edit' onclick="openEditModal(
                                                '<?= $row['id'] ?>', 
                                                '<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>', 
                                                '<?= htmlspecialchars($row['email'], ENT_QUOTES) ?>', 
                                                '<?= htmlspecialchars($row['services'], ENT_QUOTES) ?>', 
                                                '<?= htmlspecialchars($row['invoice_number'], ENT_QUOTES) ?>', 
                                                '<?= htmlspecialchars($row['amount'], ENT_QUOTES) ?>', 
                                                '<?= htmlspecialchars($row['billing_date'], ENT_QUOTES) ?>', 
                                                '<?= htmlspecialchars($row['status'], ENT_QUOTES) ?>', 
                                                '<?= htmlspecialchars($row['due_date'], ENT_QUOTES) ?>')">
                                                <i class='ri-edit-line'></i>
                                            </button>

                                            <button class='delete' onclick="deleteLead('<?= $row['id'] ?>')">
                                                <i class='ri-delete-bin-7-line'></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add Invoice -->
        <div id="modal" class="modal invoicing-modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Add New Invoice</h2>
                <form action="../controllers/invoice-crud.php" method="POST">
                    <!-- Hidden Input buat User ID -->
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                    <input type="hidden" id="lead_id">

                    <!-- Name -->
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter full name" required>

                    <!-- Email / Phone -->
                    <label for="contact">Email/Phone</label>
                    <div class="input-group contact">
                        <input type="text" id="contact" name="contact" placeholder="Email or Phone Number" required>
                        <button type="button" onclick="addLeads()" class="existing-lead-btn">OR Add Existing Lead</button>
                    </div>

                    <!-- Services -->
                    <label for="services">Services</label>
                    <input type="text" id="services" name="services" placeholder="Enter services" required>

                    <!-- Amount -->
                    <label for="amount">Amount</label>
                    <div class="input-group amount">
                        <div class="currency">
                            <select name="currency" id="currency">
                                <option value="USD">$ (USD)</option>
                                <option value="IDR">Rp (IDR)</option>
                                <option value="EUR">€ (EUR)</option>
                                <option value="GBP">£ (GBP)</option>
                                <option value="JPY">¥ (JPY)</option>
                            </select>
                            <i class="ri-arrow-drop-down-line"></i>
                        </div>
                        <input type="text" id="amount" name="amount" placeholder="Enter amount" required>
                    </div>

                    <!-- Billing Date -->
                    <label for="billing_date">Billing Date</label>
                    <div class="input-group billing_date">
                        <input type="date" id="billing_date" name="billing_date" required>
                        <span class="date-separator">to</span>
                        <input type="date" id="due_date" name="due_date" required>
                    </div>

                    <!-- Status -->
                    <label for="status">Status</label>
                    <div class="status-invoice">
                        <select name="status" id="status">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="overdue">Overdue</option>
                        </select>
                        <i class="ri-arrow-drop-down-line"></i>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="modal-buttons">
                        <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                        <button type="submit" name="add" class="save-btn">Add Invoice</button>
                    </div>
                </form>
            </div>
            <!-- Add Existing Lead Modal -->
            <div class="existing-lead">
                <div class="existing-lead__container">
                    <span class="close" onclick="closeExisting()">&times;</span>
                    <h3 class="existing-lead__title">Add Existing Lead</h3>
                    <div class="leads-info">
                        <?php while ($lead = $resultLeads->fetch_assoc()): ?>
                            <div class="leads-info-item">
                                <div class="leads-contact">
                                    <h4><?= htmlspecialchars($lead['name']) ?></h4>
                                    <p><?= htmlspecialchars($lead['email']) ?></p>
                                </div>
                                <button type="button" onclick="chooseLead(
                                    '<?= $lead['id'] ?>', 
                                    '<?= htmlspecialchars($lead['name'], ENT_QUOTES) ?>', 
                                    '<?= htmlspecialchars($lead['email'], ENT_QUOTES) ?>'
                                )">Choose</button>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

        </div>



        <!-- Edit Modal -->
        <div id="modalEdit" class="modal invoicing-modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2>Edit Lead</h2>
                <form action="../controllers/leads-crud.php" method="POST">
                    <input type="hidden" id="editId" name="id">

                    <label for="editName">Name</label>
                    <input type="text" id="editName" name="name" placeholder="Enter full name" required>

                    <label for="editPosition">Position</label>
                    <input type="text" id="editPosition" name="position" placeholder="Enter position" required>

                    <label for="editCompany">Company</label>
                    <input type="text" id="editCompany" name="company" placeholder="Enter company name" required>

                    <label for="editEmail">Email</label>
                    <input type="email" id="editEmail" name="email" placeholder="Enter email" required>

                    <label for="editStatus">Status</label>
                    <select name="status" id="editStatus">
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="closed">Closed</option>
                    </select>

                    <label for="editSource">Source</label>
                    <input type="text" id="editSource" name="source" placeholder="Enter lead source">

                    <label for="editLocation">Location</label>
                    <input type="text" id="editLocation" name="location" placeholder="Enter location">

                    <button type="submit" name="update" class="save-btn">Update</button>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="modalDelete" class="modalDelete invoicing-modal">
            <div class="modal-content">
                <div class="delete-header">
                    <i class="ri-error-warning-fill"></i>
                    <h3>Delete Leads</h3>
                    <p>You're going to delete the leads. <br> Are you sure?</p>
                </div>
                <div class="delete-btn">
                        <form action="../controllers/leads-crud.php" method="POST">
                        <input type="hidden" name="id" id="deleteId"> <!-- ID Barang yang akan dihapus -->
                        <button type="submit" name="delete" class="btn-danger">Yes, Delete!</button>
                        <button type="button" class="btn-cancel" onclick="closeDeleteModal('modalDelete')">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        
    </main>

    <script src="../../public/js/invoicing.js"></script>
    <script>
        const cssLink = document.createElement("link");
        cssLink.rel = "stylesheet";
        cssLink.href = "../../public/css/lead-management.css?v=" + new Date().getTime();
        document.head.appendChild(cssLink);

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

    function addInvoice() { 
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
        

// Add existing modal  
function addLeads() {
    let modal = document.querySelector(".existing-lead");
    modal.classList.remove("closing"); // Hapus efek closing jika ada
    modal.classList.add("active"); // Tambah class active untuk memunculkan modal
}

function closeExisting() {
    let modal = document.querySelector(".existing-lead");
    modal.classList.add("closing"); // Tambahkan efek closing
    setTimeout(() => {
        modal.classList.remove("active", "closing"); // Hapus modal setelah animasi selesai
    }, 300);
}

// Tutup modal jika klik di luar container
document.addEventListener("click", function (event) {
    let modal = document.querySelector(".existing-lead");
    let container = document.querySelector(".existing-lead__container");

    if (modal.classList.contains("active") && !container.contains(event.target) && event.target !== document.querySelector(".existing-lead-btn")) {
        closeExisting();
    }
});
function chooseLead(id, name, email) {
    document.getElementById('lead_id').value = id; // Set hidden input
    document.getElementById('name').value = name; // Set nama lead
    document.getElementById('contact').value = email; // Set email lead
    
    closeExisting(); // Tutup modal existing lead setelah memilih
}



        /* Date picker */
        document.addEventListener("DOMContentLoaded", function () {
            let startDateInput = document.getElementById("start-date");
            let endDateInput = document.getElementById("end-date");
            let dateRangeDisplay = document.getElementById("date-range");

            // Ambil tanggal hari ini
            let today = new Date();
            let todayFormatted = today.toISOString().split("T")[0];

            // Ambil tanggal awal bulan ini
            let firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            let firstDayFormatted = firstDayOfMonth.toISOString().split("T")[0];

            // Set default value
            startDateInput.value = firstDayFormatted;
            endDateInput.value = todayFormatted;
            dateRangeDisplay.textContent = `${firstDayFormatted} - ${todayFormatted}`;

            document.querySelector(".date-filter").addEventListener("click", function () {
                startDateInput.style.opacity = "0";
                startDateInput.style.pointerEvents = "all";
                startDateInput.showPicker();

                startDateInput.addEventListener("change", function () {
                    endDateInput.style.opacity = "1";
                    endDateInput.style.pointerEvents = "all";
                    endDateInput.showPicker();
                });

                endDateInput.addEventListener("change", function () {
                    if (startDateInput.value && endDateInput.value) {
                        dateRangeDisplay.textContent = `${startDateInput.value} - ${endDateInput.value}`;
                    }
                });
            });
        });
   
        function formatHarga(input) {
    // Ambil angka asli tanpa titik
    let value = input.value.replace(/\D/g, '');

    // Cegah angka kosong jadi NaN
    if (!value) {
        input.value = '';
        return;
    }

    // Format angka dengan titik ribuan
    value = new Intl.NumberFormat('id-ID').format(value);

    // Update input dengan format baru
    input.value = value;
}

document.addEventListener("DOMContentLoaded", function () {
    const amountInput = document.getElementById("amount");

    // Reformat angka saat user mengetik
    amountInput.addEventListener("input", function () {
        formatHarga(this);
    });

    // Pastikan data bersih sebelum dikirim ke backend
    document.querySelector("form").addEventListener("submit", function () {
        amountInput.value = amountInput.value.replace(/\./g, ""); // Hapus titik sebelum submit
    });
});




   </script>
</body>
</html>