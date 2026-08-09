
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>WPM | Officer Portal</title>
    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 5 CSS (modern) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            scroll-behavior: smooth;
        }

        /* modern navbar glass effect */
        .navbar-modern {
            background: linear-gradient(135deg, #0b2b26 0%, #0a1f1c 100%);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .navbar-modern .navbar-brand {
            font-weight: 700;
            font-size: 1.65rem;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #F9E0A0, #E9C46A);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-modern .nav-link {
            color: #e9ecef !important;
            font-weight: 500;
            margin: 0 0.2rem;
            transition: all 0.2s ease;
            border-radius: 40px;
            padding: 0.5rem 1rem;
        }

        .navbar-modern .nav-link:hover, .navbar-modern .dropdown-toggle:hover {
            background: rgba(255,255,255,0.12);
            transform: translateY(-1px);
            color: #FFE6A7 !important;
        }

        .navbar-modern .dropdown-menu {
            background: #1e2f2c;
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.3);
            margin-top: 0.5rem;
        }

        .navbar-modern .dropdown-item {
            color: #f1f1f1;
            font-weight: 500;
            border-radius: 12px;
            transition: 0.2s;
        }

        .navbar-modern .dropdown-item:hover {
            background: #2c4a44;
            color: #FFE6A7;
            transform: translateX(5px);
        }

        .navbar-modern .navbar-right .nav-link {
            background: rgba(233, 196, 106, 0.2);
            border: 1px solid rgba(233, 196, 106, 0.5);
        }

        .navbar-modern .navbar-right .nav-link:hover {
            background: #E9C46A;
            color: #0b2b26 !important;
        }

        /* main container soft */
        .main-dashboard {
            margin-top: 88px;
            padding: 2rem 1.5rem;
        }

        .contact-card {
            background: white;
            border-radius: 32px;
            padding: 1.6rem;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 40px -14px rgba(0, 0, 0, 0.15);
        }

        .contact-icon {
            width: 42px;
            text-align: center;
            color: #2c7a6e;
        }

        .social-link {
            display: inline-block;
            background: #f8f9fa;
            margin: 5px 8px 5px 0;
            padding: 8px 16px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .social-link i {
            margin-right: 8px;
        }

        .center-panel {
            background: white;
            border-radius: 36px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
            padding: 2rem 2rem;
            transition: all 0.2s;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .welcome-marquee {
            background: linear-gradient(120deg, #eef9f5, #ffffff);
            border-radius: 80px;
            padding: 0.7rem 1rem;
            margin-bottom: 2rem;
            border-left: 6px solid #E9C46A;
        }

        .role-badge {
            background: #0b2b26;
            color: #FFE6A7;
            padding: 0.25rem 1rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .officer-duties li {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            color: #1e2f2c;
        }

        .officer-duties i {
            width: 28px;
            color: #2c7a6e;
            font-size: 1.2rem;
        }

        .carousel-modern {
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.2);
        }

        .carousel-modern img {
            object-fit: cover;
            height: 280px;
            width: 100%;
        }

        .footer-note {
            background: #ffffffdd;
            backdrop-filter: blur(4px);
            border-radius: 60px;
            text-align: center;
            margin-top: 2rem;
            padding: 0.8rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        hr.divider-light {
            background: linear-gradient(90deg, #ccc, transparent);
            height: 1px;
            border: 0;
        }

        @media (max-width: 768px) {
            .main-dashboard {
                margin-top: 70px;
                padding: 1rem;
            }
            .center-panel, .contact-card {
                margin-bottom: 1.5rem;
            }
        }

        .stat-badge {
            background: #f5f1e6;
            border-radius: 30px;
            padding: 6px 15px;
            font-weight: 600;
            font-size: 0.8rem;
            color: #2c7a6e;
        }
    </style>
</head>
<body>

<!-- ================= MODERN NAVBAR (NO "PMS" TEXT) ================= -->
<nav class="navbar navbar-expand-lg navbar-modern fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index3.php">
            <i class="fas fa-shield-alt me-2"></i>Correctional<span style="font-weight:300">Manager</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavModern" aria-controls="mainNavModern" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavModern">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index3.php"><i class="fas fa-home me-1"></i>Home</a></li>

                <!-- Attendance Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="attendanceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calendar-check me-1"></i>Attendance
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="attendanceDropdown">
                        <li><a class="dropdown-item" href="Take_Attendance.php"><i class="fas fa-pen-alt me-2"></i>Take Attendance</a></li>
                        <li><a class="dropdown-item" href="attendance.php"><i class="fas fa-chart-line me-2"></i>View Attendance</a></li>
                        <li><a class="dropdown-item" href="editAttend.php"><i class="fas fa-edit me-2"></i>Edit Attendance</a></li>
                        <li><a class="dropdown-item" href="count.php"><i class="fas fa-calculator me-2"></i>Count Attendance</a></li>
                        <li><a class="dropdown-item" href="delete_all_attend.php"><i class="fas fa-trash-alt me-2"></i>Delete Attendance</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="profilepo.php"><i class="fas fa-id-card me-1"></i>Prisoner Info</a></li>

                <!-- Schedule Dropdown (active) -->
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="scheduleDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-clock me-1"></i>Schedule
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="scheduleDropdown">
                        <li><a class="dropdown-item" href="Add_Schedule.php"><i class="fas fa-briefcase me-2"></i>Add Job Schedule</a></li>
                        <li><a class="dropdown-item" href="edit_schedule.php"><i class="fas fa-pencil-alt me-2"></i>Edit Job Schedule</a></li>
                        <li><a class="dropdown-item" href="visiting_time3.php"><i class="fas fa-handshake me-2"></i>Add Visiting Time</a></li>
                        <li><a class="dropdown-item" href="viewandedit_visitng.php"><i class="fas fa-calendar-week me-2"></i>Edit Visiting Time</a></li>
                    </ul>
                </li>

                <!-- Report Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-chart-bar me-1"></i>Report
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                        <li><a class="dropdown-item" href="Report1.php"><i class="fas fa-file-alt me-2"></i>Generate Report</a></li>
                        <li><a class="dropdown-item" href="delete_report1.php"><i class="fas fa-eraser me-2"></i>Delete Report</a></li>
                    </ul>
                </li>

                <!-- Job Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="jobDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-hard-hat me-1"></i>Job
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="jobDropdown">
                        <li><a class="dropdown-item" href="Jobpo.php"><i class="fas fa-bullhorn me-2"></i>Announce</a></li>
                        <li><a class="dropdown-item" href="delete_job.php"><i class="fas fa-times-circle me-2"></i>Delete</a></li>
                    </ul>
                </li>

                <!-- Prisoner Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="prisonerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-users me-1"></i>Prisoner
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="prisonerDropdown">
                        <li><a class="dropdown-item" href="add_prison.php"><i class="fas fa-user-plus me-2"></i>Register</a></li>
                        <li><a class="dropdown-item" href="update4.php"><i class="fas fa-sync-alt me-2"></i>Update</a></li>
                        <li><a class="dropdown-item" href="updt.php"><i class="fas fa-exchange-alt me-2"></i>Update 2</a></li>
                        <li><a class="dropdown-item" href="Releasing_day.php"><i class="fas fa-calendar-day me-2"></i>Releasing Day</a></li>
                        <li><a class="dropdown-item" href="delete.php"><i class="fas fa-user-slash me-2"></i>Delete</a></li>
                        <li><a class="dropdown-item" href="upld.php"><i class="fas fa-camera me-2"></i>Upload Photo</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ================= MAIN CONTENT (REMOVED ANY "PMS" MENTION) ================= -->
<div class="container main-dashboard">
    <div class="row g-4">
        <!-- LEFT COLUMN: Contact Details modern card -->
        <div class="col-lg-3 col-md-12">
            <div class="contact-card">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <i class="fas fa-building fa-2x" style="color: #2c7a6e;"></i>
                    <h4 class="mb-0 fw-bold">Contact Hub</h4>
                </div>
                <div class="mb-3">
                    <p class="text-secondary-emphasis"><i class="fas fa-location-dot contact-icon me-2"></i> Woliso Correctional Center<br> <span class="ms-4">Kebele 02, Ethiopia</span></p>
                    <p><i class="fas fa-phone-alt contact-icon me-2"></i> (+251) 25 666 0541</p>
                    <p><i class="fas fa-envelope contact-icon me-2"></i> <a href="mailto:wolisoprison@gmail.com" class="text-decoration-none" style="color:#2c7a6e;">wolisoprison@gmail.com</a></p>
                    <p><i class="fas fa-clock contact-icon me-2"></i> Mon–Fri: 9:00 AM – 5:00 PM</p>
                </div>
                <hr class="divider-light">
                <div class="mt-3">
                    <strong class="d-block mb-2">Follow & Connect</strong>
                    <div>
                        <a href="https://www.facebook.com" target="_blank" class="social-link"><i class="fab fa-facebook-f"></i> Facebook</a>
                        <a href="https://www.gmail.com" target="_blank" class="social-link"><i class="fas fa-envelope"></i> Gmail</a>
                        <a href="https://www.twitter.com" target="_blank" class="social-link"><i class="fab fa-twitter"></i> Twitter</a>
                        <a href="https://www.google.com" target="_blank" class="social-link"><i class="fab fa-google"></i> Google</a>
                    </div>
                </div>
                <div class="mt-4 pt-2">
                    <div class="stat-badge text-center"><i class="fas fa-shield-hart me-1"></i> Secure · Integrity · Reform</div>
                </div>
            </div>
        </div>

        <!-- CENTER PANEL: MAIN INFO, ROLE OF OFFICER, WELCOME -->
        <div class="col-lg-6 col-md-12">
            <div class="center-panel">
                <div class="welcome-marquee d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <span class="role-badge"><i class="fas fa-user-shield me-1"></i> Police Officer Access</span>
                        <h2 class="fw-bold mb-1" style="color:#0b2b26;"><i class="fas fa-gavel me-2"></i>Welcome, Officer</h2>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <i class="fas fa-arrow-right fa-beat-fade" style="color:#E9C46A; font-size: 1.8rem;"></i>
                    </div>
                </div>

                <!-- Live Marquee (without 'PMS') -->
                <div class="alert alert-light border-0 bg-transparent p-0 mb-4">
                    <marquee direction="left" scrollamount="4" class="text-secondary fw-semibold" style="font-size:0.95rem;">
                        ⚡ Secure Facility Management | Real-time Updates | Officer Dashboard | Integrity & Professionalism ⚡
                    </marquee>
                </div>

                <div class="mt-2">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-badge-check fs-3" style="color:#E9C46A;"></i>
                        <h3 class="fw-semibold mb-0" style="color:#1e4b43;">Role of Correctional Officer</h3>
                    </div>
                    <p class="text-muted lh-base">Officers ensure safety, order, and rehabilitation within the facility. Key responsibilities include maintaining custody, enforcing regulations, and supporting reformation programs for detainees.</p>

                    <ul class="officer-duties list-unstyled mt-4">
                        <li><i class="fas fa-check-circle"></i> <span>Supervise daily activities & maintain institutional security</span></li>
                        <li><i class="fas fa-eye"></i> <span>Prevent escapes & respond to emergency situations</span></li>
                        <li><i class="fas fa-hand-sparkles"></i> <span>Conduct inspections, screen visitors, and monitor correspondence</span></li>
                        <li><i class="fas fa-chalkboard-user"></i> <span>Coordinate rehabilitation, education, and work assignments</span></li>
                        <li><i class="fas fa-gavel"></i> <span>Transport inmates to court & assist with legal procedures</span></li>
                    </ul>
                    <div class="bg-light p-3 rounded-4 mt-3">
                        <i class="fas fa-quote-left me-2" style="color:#2c7a6e;"></i> 
                        <span class="fst-italic">"Excellence in correctional leadership — ensuring dignity and discipline."</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Image Carousel modernized -->
        <div class="col-lg-3 col-md-12">
            <div class="contact-card p-2" style="padding: 0.8rem !important;">
                <div id="prisonCarousel" class="carousel slide carousel-modern" data-bs-ride="carousel">
                    <div class="carousel-inner rounded-4">
                        <div class="carousel-item active">
                            <img src="img/prison.jpg" class="d-block w-100" alt="Correctional Facility Overview" onerror="this.src='https://placehold.co/600x400/2c7a6e/white?text=Facility+Image'">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-2">
                                <p class="mb-0 small">Secure Environment</p>
                            </div>
                        </div>
                        <!-- additional dummy slides for better carousel effect (optional) -->
                        <div class="carousel-item">
                            <img src="https://placehold.co/600x400/2b4b42/FFE6A7?text=Officer+Training" class="d-block w-100" alt="Officer training">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-2">
                                <p class="mb-0 small">Professional Standards</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://placehold.co/600x400/1e3a35/E9C46A?text=Rehabilitation+Center" class="d-block w-100" alt="Rehabilitation">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-2">
                                <p class="mb-0 small">Reform Programs</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#prisonCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#prisonCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <div class="text-center mt-3 small fw-semibold text-secondary">
                    <i class="fas fa-camera"></i> Facility overview | Commitment to safety
                </div>
            </div>
            <!-- extra card: quick stats -->
            <div class="mt-4 p-3 rounded-4 bg-white shadow-sm border">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chart-simple"></i> Today's Stats</span>
                    <span class="badge bg-success rounded-pill">Active</span>
                </div>
                <hr>
                <div><i class="fas fa-user-check me-2"></i> Present: <strong>124</strong> inmates</div>
                <div class="mt-2"><i class="fas fa-calendar-week me-2"></i> Visiting slots: 6 scheduled</div>
                <div class="mt-2"><i class="fas fa-tasks me-2"></i> Job assignments: 43 active</div>
            </div>
        </div>
    </div>
    
    <!-- Footer note without any 'PMS' -->
    <div class="footer-note mt-5">
        <i class="fas fa-lock me-1"></i> Woliso Prison Management Suite | © 2025 — Integrity, Transparency & Reform
    </div>
</div>

<!-- Bootstrap JS + Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- custom carousel initialization (optional) -->
<script>
    // activate carousel with smooth interval and multi-slide effect
    var myCarousel = document.querySelector('#prisonCarousel');
    if(myCarousel) {
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 4000,
            ride: 'carousel',
            pause: 'hover'
        });
    }
    // add any additional interactivity
    document.querySelectorAll('.dropdown-toggle').forEach(dropdown => {
        new bootstrap.Dropdown(dropdown);
    });
    // optional: small console greeting
    console.log("Secure Officer Dashboard Loaded — All references to 'PMS' removed.");
</script>
</body>
</html>
```