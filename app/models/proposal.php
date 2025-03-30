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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientify - Proposal Templates</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../../public/css/public.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../public/css/proposal.css?v=<?php echo time(); ?>">


    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="../../public/assets/auth-logo.svg" type="image/x-icon">

</head>
<body>
        <div class="main-container proposal">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-container">
                    <div class="nav-logo">
                        <img src="../../public/assets/auth-logo_glow.svg" alt="">
                    </div>
                        <button class="hamburger" onclick="navMenu()"><i class="ri-menu-2-line"></i></button>
                    <div class="nav-links">
                        <ul>
                            <li><a href="lead-management.php" >Lead Management</a></li>
                            <li><a href="invoicing.php">Invoicing</a></li>
                            <li><a href="" class="link-active">Proposal</a></li>
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

            <div class="main-header">
                <div class="header-text">
                    <h1>Proposal Templates</h1>
                    <p>Browse 500+ professionally designed proposal templates to win more clients and close deals faster. Perfect for freelancers, agencies, and businesses of all sizes.</p>
                </div>
            </div>

            <div class="items">
                <div class="items-container">
                    <h3>Looking for something?</h3>
                    <div class="items-header">
                        <div class="items-searchbar">
                            <i class="ri-search-line"></i>
                            <input type="text" id="search" placeholder="Search Proposal Category" onkeyup="searchProposal()">
                        </div>
                    </div>
                    <div class="slider-container">
                    <button class="arrow left" onclick="scrollCategoryLeft()">&#10094;</button>
                        <div class="items-category__wrapper">
                            <div class="items-category__container">
                                <button onclick="proposalCategory('web_dev')">Web Development Proposal</button>
                                <button onclick="proposalCategory('graphic_design')">Graphic Design Proposal</button>
                                <button onclick="proposalCategory('social_media')">Social Media Proposal</button>
                                <button onclick="proposalCategory('ui_ux')">UI/UX Design Proposal</button>
                                <button onclick="proposalCategory('freelance')">Freelance Work Proposal</button>
                                <button onclick="proposalCategory('copywriting')">Copywriting Proposal</button>
                                <button onclick="proposalCategory('seo_services')">SEO Services Proposal</button>
                                <button onclick="proposalCategory('consulting')">Business Consulting Proposal</button>
                                <button onclick="proposalCategory('marketing')">Marketing Strategy Proposal</button>
                                <button onclick="proposalCategory('brand_identity')">Brand Identity Proposal</button>
                                <button onclick="proposalCategory('software_dev')">Software Development Proposal</button>
                                <button onclick="proposalCategory('app_dev')">Mobile App Development Proposal</button>
                                <button onclick="proposalCategory('finance')">Financial Planning Proposal</button>
                                <button onclick="proposalCategory('video_editing')">Video Editing Proposal</button>
                                <button onclick="proposalCategory('virtual_assistant')">Virtual Assistant Proposal</button>
                                <button onclick="proposalCategory('content_writing')">Content Writing Proposal</button>
                                <button onclick="proposalCategory('voice_over')">Voice Over Proposal</button>
                                <button onclick="proposalCategory('illustration')">Illustration & Animation Proposal</button>
                                <button onclick="proposalCategory('translation')">Translation Services Proposal</button>
                                <button onclick="proposalCategory('data_entry')">Data Entry Proposal</button>
                                <button onclick="proposalCategory('business_plan')">Business Plan Proposal</button>
                                <button onclick="proposalCategory('presentation_design')">Presentation Design Proposal</button>
                            </div>
                        </div>
                        <button class="arrow right" onclick="scrollCategoryRight()">&#10095;</button>
                    </div>
                </div>
            </div>


            <!-- Proposal templates -->
             <div class="proposal-templates">
                <div class="proposal-templates-container">

                <div class="category category-1" data-category="web_dev">
                    <div class="proposal-card__header">
                        <h3 class="proposal-header">Web Development</h3>
                        <div class="slider-category">
                            <button><i class="ri-arrow-left-line"></i></button>
                            <button><i class="ri-arrow-right-line"></i></button>
                        </div>
                    </div>
                    <div class="proposal-card__container">
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Custom Website Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>E-Commerce Website Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Landing Page Development</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Website Redesign Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>WordPress Development Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Web App Development Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Frontend Development Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Full-Stack Web Development</p>
                        </div>
                    </div>
                </div>


                <div class="category category-2" data-category="graphic_design">
                    <div class="proposal-card__header">
                        <h3 class="proposal-header">Graphic Design</h3>
                        <div class="slider-category">
                            <button><i class="ri-arrow-left-line"></i></button>
                            <button><i class="ri-arrow-right-line"></i></button>
                        </div>
                    </div>
                    <div class="proposal-card__container">
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Logo Design Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Brand Identity Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Social Media Design</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Infographic Design Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Marketing Material Design</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>UI/UX Design Proposal</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Presentation Design</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Illustration Design</p>
                        </div>
                    </div>
                </div>


                <div class="category category-3" data-category="social_media">
                    <div class="proposal-card__header">
                        <h3 class="proposal-header">Social Media</h3>
                        <div class="slider-category">
                            <button><i class="ri-arrow-left-line"></i></button>
                            <button><i class="ri-arrow-right-line"></i></button>
                        </div>
                    </div>
                    <div class="proposal-card__container">
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Social Media Strategy</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Content Creation Plan</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Instagram Growth Strategy</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Facebook Ads Campaign</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>TikTok Marketing Plan</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Social Media Management</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>YouTube Channel Growth</p>
                        </div>
                        <div class="proposal-card">
                            <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                            <p>Influencer Collaboration</p>
                        </div>
                    </div>
                </div>


                    <div class="category category-4" data-category="ui_ux">
                        <div class="proposal-card__header">
                            <h3 class="proposal-header">UI/UX</h3>
                            <div class="slider-category">
                                <button><i class="ri-arrow-left-line"></i></button>
                                <button><i class="ri-arrow-right-line"></i></button>
                            </div>
                        </div>
                        <div class="proposal-card__container">
                            <div class="proposal-card">
                                <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                                <p>Website UI Design</p>
                            </div>
                            <div class="proposal-card">
                                <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                                <p>Mobile App UX Design</p>
                            </div>
                            <div class="proposal-card">
                                <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                                <p>Dashboard UI/UX</p>
                            </div>
                            <div class="proposal-card">
                                <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                                <p>Wireframe & Prototyping</p>
                            </div>
                            <div class="proposal-card">
                                <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                                <p>Landing Page Design</p>
                            </div>
                            <div class="proposal-card">
                                <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                                <p>E-Commerce UI/UX</p>
                            </div>
                            <div class="proposal-card">
                                <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                                <p>Design System & Components</p>
                            </div>
                            <div class="proposal-card">
                                <button onclick="openTemplateModal()"><img src="../../public/assets/proposal-cover.svg" alt=""></button>
                                <p>User Research & Testing</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="show-more">
                    <button onclick="showMore()">Show More <i class="ri-arrow-down-long-line"></i></button>
                </div>

                <div class="show-more__modal">
                    <div class="show-more__container">
                        <img src="../../public/assets/smile.png" alt="">
                        <div class="show-more__desc">
                            <h3>Halo semua!</h3>
                            <p>
                                Project ini dibuat secara independen oleh <b>Zacky Raechan</b>. 
                                Project ini bersifat <a href="https://github.com/zackyyr/Clientify">open-source</a> dan hanya digunakan untuk keperluan belajar, 
                                <b>TIDAK untuk diperjualbelikan</b>.  
                                <br><br>
                                Beberapa aset seperti ikon dan gambar bukan sepenuhnya milik saya, 
                                melainkan berasal dari sumber gratis seperti <b>Flaticon</b>,<b>Freepik</b>, dan <b>Visme</b>.  
                                Sementara itu, logo dibuat sendiri untuk keperluan project ini.  
                                <br><br>
                                Terima kasih sudah mampir! 🚀🔥
                            </p>
                            <div class="social-media-links">
                                <strong>Social : </strong>
                                <a href="https://www.instagram.com/zackyraechan/"><i class="ri-instagram-line"></i>@zackyraechan</a>
                                <a href="https://www.linkedin.com/in/zacky-raechan-a889b225a/"><i class="ri-linkedin-box-line"></i>Zacky Raechan</a>
                                <a href="https://github.com/zackyyr"><i class="ri-github-fill"></i>zackyyr</a>
                            </div>

                            <button class="showClose" onclick="showMoreClose()">Okay</button>
                        </div>
                    </div>
                </div>

                <!-- Template Modal -->
                 <div class="template-modal">
                    <div class="template-modal__container">
                        <div class="template-modal__header">
                            <img src="../../public/assets/proposal-cover.svg" alt="">
                        </div>
                        <div class="template-modal__btn">
                            <h3>The title of the template will be here</h3>
                            <button onclick="downloadTemplate()">Use This Template</button>
                        </div>
                    </div>
                 </div>
             </div>
    </main>

    <script src="../../public/js/leads.js"></script>
    <script>
        const cssLink = document.createElement("link");
        cssLink.rel = "stylesheet";
        cssLink.href = "../../public/css/lead-management.css?v=" + new Date().getTime();
        document.head.appendChild(cssLink);

        const container = document.querySelector(".items-category__container");
        let scrollAmount = 0;

        function scrollCategoryLeft() {
            scrollAmount -= 200;
            if (scrollAmount < 0) scrollAmount = 0;
            container.style.transform = `translateX(-${scrollAmount}px)`;
        }

        function scrollCategoryRight() {
            let maxScroll = container.scrollWidth - container.clientWidth;
            scrollAmount += 200;
            if (scrollAmount > maxScroll) scrollAmount = maxScroll;
            container.style.transform = `translateX(-${scrollAmount}px)`;
        }


        document.addEventListener("DOMContentLoaded", function () {
    const categories = document.querySelectorAll(".category");

    categories.forEach((category) => {
        const container = category.querySelector(".proposal-card__container");
        const prevBtn = category.querySelector(".slider-category button:first-child");
        const nextBtn = category.querySelector(".slider-category button:last-child");

        let scrollAmount = 300; // Sesuaikan dengan ukuran card

        nextBtn.addEventListener("click", () => {
            container.scrollLeft += scrollAmount;
        });

        prevBtn.addEventListener("click", () => {
            container.scrollLeft -= scrollAmount;
        });
    });
});

function showMore() {
    document.querySelector(".show-more__modal").classList.add("show");
}

function showMoreClose() {
    document.querySelector(".show-more__modal").classList.remove("show");
}


function searchProposal() {
    let input = document.getElementById("search").value.toLowerCase(); // Ambil input dan ubah ke lowercase
    let categories = document.querySelectorAll(".items-category__container button");

    categories.forEach(button => {
        let text = button.innerText.toLowerCase(); // Ambil teks dalam button
        if (text.includes(input)) {
            button.style.display = "block"; // Tampilkan jika cocok
        } else {
            button.style.display = "none"; // Sembunyikan jika tidak cocok
        }
    });
}

function proposalCategory(category) {
    const categories = document.querySelectorAll(".category");

    // Jika semua kategori sedang ditampilkan, sembunyikan yang lain
    if (![...categories].some(cat => cat.classList.contains("hidden"))) {
        categories.forEach(cat => {
            if (cat.getAttribute("data-category") !== category) {
                cat.classList.add("hidden");
            }
        });
    } else {
        // Jika ada yang tersembunyi, tampilkan semua kembali
        categories.forEach(cat => cat.classList.remove("hidden"));
    }
}


function openTemplateModal() {
    document.querySelector('.template-modal').classList.add('show');
}

function closeTemplateModal() {
    document.querySelector('.template-modal').classList.remove('show');
}

// Tutup modal jika user klik di luar modal
document.querySelector('.template-modal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeTemplateModal();
    }
});

    </script>

</body>
</html>