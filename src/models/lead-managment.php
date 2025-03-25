<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientify - Lead Management</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/lead-management.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <main>
        <div class="main-container">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-container">
                    <div class="nav-logo">
                        <h3>Clientify</h3>
                    </div>
                    <div class="nav-links">
                        <ul>
                            <li><a href="" class="link-active">Lead Management</a></li>
                            <li><a href="">Invoicing</a></li>
                            <li><a href="">Proposal</a></li>
                        </ul>
                    </div>
                    <div class="nav-profile">
                        <button onclick="profileShow()"><img src="../../public/assets/profile.svg" alt="Profile"></button>
                        <ul class="dropdown" id="dropdown">
                            <li><button><i class="ri-settings-line"></i>Settings</button></li>
                            <li><button class="logout"><i class="ri-logout-box-line"></i>Logout</button></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Leads Section -->
            <div class="main-header">
                <div class="header-text">
                    <h1>Leads Management</h1>
                    <p>Organize leads and track their progress effectively.</p>
                </div>
                <div class="header-add">
                    <button class="add-btn" id="addModal" onclick="openAddModal()"><i class="ri-add-line"></i>Add Lead</button>
                </div>
            </div>

            <div class="items">
                <div class="items-container">
                    <div class="items-header">
                        <div class="items-searchbar">
                            <i class="ri-search-line"></i>
                            <input type="text" id="search" placeholder="Search by name or email address" onkeyup="searchLeads()">
                        </div>
                    </div>

                    <div class="items-data">
                        <table>
                            <thead>
                                <tr class="table-header">
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Source</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="dataLeads">
                                <!-- Data Leads akan dimuat melalui JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add Lead -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Add New Lead</h2>
                <form method="POST">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter full name" required>

                    <label for="position">Position</label>
                    <input type="text" id="position" name="position" placeholder="Enter position" required>

                    <label for="company">Company</label>
                    <input type="text" id="company" name="company" placeholder="Enter company name" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter email" required>

                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="closed">Closed</option>
                    </select>

                    <label for="source">Source</label>
                    <input type="text" id="source" name="source" placeholder="Enter lead source">

                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" placeholder="Enter location">

                    <button type="submit" name="add" class="save-btn">Save Lead</button>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="modalEdit" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2>Edit Lead</h2>
                <form method="POST">
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
        <div id="modalDelete" class="modalDelete">
            <div class="modal-content">
                <div class="delete-header">
                    <i class="ri-error-warning-fill"></i>
                    <h3>Delete Leads</h3>
                    <p>You're going to delete the leads. <br> Are you sure?</p>
                </div>
                <div class="delete-btn">
                    <form method="POST">
                        <input type="hidden" name="id" id="deleteId"> <!-- ID Barang yang akan dihapus -->
                        <button type="submit" name="delete" class="btn-danger">Yes, Delete!</button>
                        <button type="button" class="btn-cancel" onclick="closeDeleteModal('modalDelete')">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="../../public/js/leads.js">

    </script>
</body>
</html>