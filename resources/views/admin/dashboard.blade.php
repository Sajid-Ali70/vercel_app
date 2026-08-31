<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - {{ $settings->app_name ?? 'Alfa Mobiles' }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $settings->app_icon ?? asset('asset/image/01_app_icon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #0b0e14;
            --sidebar-bg: #0f131a;
            --card-bg: #161b22;
            --text-main: #ffffff;
            --text-secondary: #8b949e;
            --accent-blue: #007bff;
            --border-color: #30363d;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            padding: 20px;
            display: flex;
            flex-direction: column;
            z-index: 1200;
            transition: transform 0.3s ease;
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .brand-logo-img {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border-color);
        }

        .brand-name {
            font-size: 1.15rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-badge {
            background: rgba(0, 123, 255, 0.1);
            color: var(--accent-blue);
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid var(--accent-blue);
            margin-left: auto;
        }

        .nav-link {
            color: var(--text-secondary);
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            text-decoration: none;
            cursor: pointer;
        }

        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1rem;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-link.active {
            background: rgba(0, 123, 255, 0.1);
            border: 1px solid rgba(0, 123, 255, 0.2);
            color: var(--accent-blue);
        }

        .logout-btn {
            margin-top: auto;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            background: transparent;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            text-align: left;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #ff4d4d;
            border-color: #ff4d4d;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 40px;
            transition: margin-left 0.3s ease;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .header-app-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid var(--border-color);
        }

        /* Mobile Nav Bar */
        .mobile-nav {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background-color: var(--sidebar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0 20px;
            align-items: center;
            justify-content: space-between;
            z-index: 1100;
        }

        .mobile-logo-img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
        }

        .mobile-toggle {
            background: var(--accent-blue);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1150;
        }

        /* Tabs */
        .custom-tabs {
            display: flex;
            gap: 20px;
            margin: 30px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .custom-tab-item {
            cursor: pointer;
            color: var(--text-secondary);
            padding: 10px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: 0.3s;
            position: relative;
        }

        .custom-tab-item.active {
            color: var(--accent-blue);
        }

        .custom-tab-item.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--accent-blue);
        }

        /* Content Card */
        .admin-card {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .section-desc {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 25px;
        }

        /* Themes */
        .theme-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .theme-option {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: 0.3s;
            position: relative;
        }

        .theme-option.active {
            border-color: var(--accent-blue);
            background: rgba(0, 123, 255, 0.05);
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.1);
        }

        .theme-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .theme-badge.active {
            background: var(--accent-blue);
            color: white;
        }

        .theme-badge.inactive {
            background: rgba(255,255,255,0.1);
            color: var(--text-secondary);
        }

        /* Forms & Inputs */
        .form-label { color: var(--text-secondary); margin-bottom: 8px; font-size: 0.9rem; }
        .form-control {
            background: #0d1117;
            border: 1px solid var(--border-color);
            color: white;
            padding: 12px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            background: #0d1117;
            border-color: var(--accent-blue);
            color: white;
            box-shadow: none;
        }

        /* Screenshot Gallery */
        .screenshot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .screenshot-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }

        .screenshot-preview {
            width: 100%;
            height: 220px;
            background: #0d1117;
            border-radius: 8px;
            margin-bottom: 15px;
            object-fit: contain;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--border-color);
            overflow: hidden;
        }

        .screenshot-preview img {
            max-width: 100%;
            max-height: 100%;
        }

        .btn-remove {
            background: rgba(220, 53, 69, 0.1);
            color: #ff4d4d;
            border: 1px solid #ff4d4d;
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-remove:hover {
            background: #ff4d4d;
            color: white;
        }

        .icon-preview-box {
            width: 60px;
            height: 60px;
            background: #161b22;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .icon-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--accent-blue);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: fit-content;
        }

        .btn-save-main {
            width: 100%;
            margin-top: 10px;
        }

        /* Reviews Table */
        .reviews-table {
            width: 100%;
            border-collapse: collapse;
        }
        .reviews-table th {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-weight: 500;
        }
        .reviews-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }
        .review-text-cell {
            max-width: 300px;
            white-space: normal;
        }

        .d-none { display: none !important; }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 100px 20px 40px;
            }
            .mobile-nav {
                display: flex;
            }
            .sidebar-overlay.show {
                display: block;
            }
            .theme-selector {
                grid-template-columns: 1fr 1fr; /* Force side-by-side on mobile */
                gap: 10px;
            }
            .theme-option {
                padding: 10px;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                min-height: 110px;
            }
            .theme-option .d-flex {
                flex-direction: column;
                gap: 5px !important;
                margin-bottom: 5px !important;
            }
            .theme-option h6 {
                font-size: 0.75rem;
                margin-bottom: 0 !important;
                line-height: 1.2;
            }
            .theme-option p {
                display: none; /* Hide description on mobile */
            }
            .theme-option i {
                font-size: 1.2rem;
            }
            .theme-badge {
                position: relative;
                top: 0;
                right: 0;
                margin-top: 8px;
                font-size: 0.55rem;
                padding: 2px 5px;
                width: fit-content;
                align-self: center;
            }
            .custom-tabs {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 5px;
            }
            .custom-tab-item {
                flex-shrink: 0;
            }
            .admin-card {
                padding: 20px 15px;
            }
        }

    </style>
</head>
<body>
    <!-- Mobile Navigation Bar -->
    <div class="mobile-nav">
        <img src="{{ $settings->app_icon ?? asset('asset/image/01_app_icon.png') }}" alt="Logo" class="mobile-logo-img">
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="sidebar">
        <div class="brand-section">
            <img src="{{ $settings->app_icon ?? asset('asset/image/01_app_icon.png') }}" alt="Logo" class="brand-logo-img">
            <span class="brand-name">{{ $settings->app_name ?? 'Alfa Mobiles' }}</span>
            <div class="admin-badge">Admin</div>
        </div>

        <nav class="nav flex-column">
            <a href="/" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Live Site
            </a>
            <a class="nav-link active" id="nav-themes" onclick="showSection('themes')">
                <i class="fas fa-paint-brush"></i> Theme Changer
            </a>
            <a class="nav-link" id="nav-playstore" onclick="showSection('playstore')">
                <i class="fab fa-google-play"></i> Play Store Details
            </a>
            <a class="nav-link" id="nav-reviews" onclick="showSection('reviews')">
                <i class="fas fa-star"></i> Manage Reviews
            </a>
            <a class="nav-link" id="nav-apk" onclick="showSection('apk')">
                <i class="fas fa-cube"></i> APK Manager
            </a>
            <a class="nav-link" id="nav-security" onclick="showSection('security')">
                <i class="fas fa-shield-alt"></i> Security
            </a>
        </nav>

        <form action="{{ route('admin.logout') }}" method="POST" class="mt-auto">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>

    <main class="main-content">
        <div class="page-header d-flex align-items-center gap-3 mb-4">
            <img src="{{ $settings->app_icon ?? asset('asset/image/01_app_icon.png') }}" alt="App Icon" class="header-app-icon">
            <div>
                <h1 class="mb-0">Site Customizer & Admin Panel</h1>
                <p class="mb-0">Switch themes, edit texts, customize app details, manage reviews or APK downloads.</p>
            </div>
        </div>

        <div class="custom-tabs">
            <div class="custom-tab-item active" id="tab-themes" onclick="switchToTab('themes')">
                <i class="fas fa-palette"></i> Theme Selector
            </div>
            <div class="custom-tab-item" id="tab-playstore" onclick="switchToTab('playstore')">
                <i class="fab fa-google-play"></i> App Settings
            </div>
            <div class="custom-tab-item" id="tab-reviews" onclick="switchToTab('reviews')">
                <i class="fas fa-star"></i> Reviews
            </div>
            <div class="custom-tab-item" id="tab-apk" onclick="switchToTab('apk')">
                <i class="fas fa-cube"></i> APK
            </div>
            <div class="custom-tab-item" id="tab-security" onclick="switchToTab('security')">
                <i class="fas fa-lock"></i> Password
            </div>
        </div>

        <!-- Section: Themes -->
        <section id="themesSection" class="dashboard-section">
            <div class="admin-card">
                <h5 class="section-title">Choose Active Website Theme</h5>
                <p class="section-desc">Select which theme to display to visitors on the main homepage.</p>

                <div class="theme-selector">
                    @php $activeTheme = $settings->active_theme ?? 'playstore'; @endphp
                    <div class="theme-option {{ $activeTheme == 'landing' ? 'active' : '' }}" onclick="selectTheme('landing')">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <i class="fas fa-globe text-info"></i>
                            <h6 class="mb-0">Landing Page</h6>
                            <span class="theme-badge {{ $activeTheme == 'landing' ? 'active' : 'inactive' }} ms-auto">
                                {{ $activeTheme == 'landing' ? 'Active' : 'Select' }}
                            </span>
                        </div>
                        <p class="text-secondary small mb-0">High-converting landing page with hero collage, feature cards, and custom brand colors.</p>
                    </div>

                    <div class="theme-option {{ $activeTheme == 'playstore' ? 'active' : '' }}" onclick="selectTheme('playstore')">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <i class="fab fa-google-play text-white"></i>
                            <h6 class="mb-0">Play Store</h6>
                            <span class="theme-badge {{ $activeTheme == 'playstore' ? 'active' : 'inactive' }} ms-auto">
                                {{ $activeTheme == 'playstore' ? 'Active' : 'Select' }}
                            </span>
                        </div>
                        <p class="text-secondary small mb-0">Authentic Google Play app details screen with app icon, rating stars, and screenshot gallery.</p>
                    </div>
                </div>

                <form id="themeSelectionForm" class="d-none">
                    @csrf
                    <input type="hidden" name="active_theme" id="selectedThemeInput" value="{{ $activeTheme }}">
                </form>

                <button class="btn-primary-custom mt-4 w-100" onclick="saveTheme()">
                    <i class="fas fa-save"></i> Save Theme
                </button>
            </div>
        </section>

        <!-- Section: Play Store Settings -->
        <section id="playstoreSection" class="dashboard-section d-none">
            <form id="playstoreSettingsForm" enctype="multipart/form-data">
                @csrf
                <!-- App Identity & Badges -->
                <div class="admin-card">
                    <h5 class="section-title">Google Play: App Identity & Badges</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">App Title / Name</label>
                            <input type="text" name="app_name" class="form-control" value="{{ $settings->app_name ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Developer Name</label>
                            <input type="text" name="developer" class="form-control" value="{{ $settings->developer ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ $settings->category ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contains Ads / In-app Purchases Tag</label>
                            <input type="text" name="tags" class="form-control" value="{{ $settings->tags ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">App Icon (Square icon on Google Play)</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-preview-box">
                                    <img src="{{ $settings->app_icon ?? '' }}" id="iconPreview" alt="App Icon">
                                </div>
                                <input type="text" name="app_icon" class="form-control" placeholder="Icon URL (optional)" value="{{ $settings->app_icon ?? '' }}">
                                <div class="position-relative">
                                    <input type="file" name="app_icon_file" id="app_icon_file" class="d-none" accept="image/*" onchange="previewIcon(this)">
                                    <button type="button" class="btn btn-primary-custom py-2" onclick="document.getElementById('app_icon_file').click()">
                                        <i class="fas fa-upload"></i> Upload Image
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metrics -->
                <div class="admin-card">
                    <h5 class="section-title">Ratings & Metrics</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Rating Score</label>
                            <input type="text" name="rating_score" class="form-control" value="{{ $settings->rating_score ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reviews Count</label>
                            <input type="text" name="reviews_count" class="form-control" value="{{ $settings->reviews_count ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Downloads Badge</label>
                            <input type="text" name="downloads_count" class="form-control" value="{{ $settings->downloads_count ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Content Rating</label>
                            <input type="text" name="content_rating" class="form-control" value="{{ $settings->content_rating ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Gallery -->
                <div class="admin-card">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="section-title mb-0">App Screenshots Gallery</h5>
                        <div>
                            <input type="file" name="screenshots[]" id="screenshot_upload" class="d-none" multiple accept="image/*" onchange="uploadScreenshots(this)">
                            <button type="button" class="btn btn-primary px-3 py-2" style="font-size: 0.85rem;" onclick="document.getElementById('screenshot_upload').click()">
                                <i class="fas fa-plus me-1"></i> Add New Screenshots
                            </button>
                        </div>
                    </div>
                    <div class="screenshot-grid" id="screenshotGallery">
                        @php $screenshots = json_decode($settings->screenshots ?? '[]', true); @endphp
                        @foreach($screenshots as $index => $ss)
                        <div class="screenshot-card" data-index="{{ $index }}">
                            <div class="screenshot-preview"><img src="{{ $ss }}" alt="Screenshot"></div>
                            <button type="button" class="btn-remove" onclick="removeScreenshot({{ $index }})"><i class="fas fa-trash-alt"></i> Remove</button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Text Content -->
                <div class="admin-card">
                    <h5 class="section-title">About & Release Notes</h5>
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label">Updated On Date</label>
                            <input type="text" name="updated_date" class="form-control" value="{{ $settings->updated_date ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Full Description</label>
                            <textarea name="description" class="form-control" rows="5">{{ $settings->description ?? '' }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">What's New</label>
                            <textarea name="release_notes" class="form-control" rows="5">{{ $settings->release_notes ?? '' }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary-custom btn-save-main py-3 mt-4">
                        <i class="fas fa-save"></i> Save All Settings
                    </button>
                </div>
            </form>
        </section>

        <!-- Section: Reviews -->
        <section id="reviewsSection" class="dashboard-section d-none">
            <div class="admin-card">
                <h5 class="section-title">Add New Review</h5>
                <form id="addReviewForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Reviewer Name</label><input type="text" name="reviewer_name" class="form-control" required></div>
                        <div class="col-md-3"><label class="form-label">Rating</label><select name="rating" class="form-control"><option value="5">5 Stars</option><option value="4">4 Stars</option><option value="3">3 Stars</option></select></div>
                        <div class="col-md-3"><label class="form-label">Date</label><input type="text" name="review_date" class="form-control" value="{{ date('F j, Y') }}" required></div>
                        <div class="col-12"><label class="form-label">Review Text</label><textarea name="review_text" class="form-control" rows="3" required></textarea></div>
                    </div>
                    <button type="submit" class="btn-primary-custom mt-3">Add Review</button>
                </form>
            </div>
            <div class="admin-card">
                <h5 class="section-title">Existing Reviews</h5>
                <div class="table-responsive mt-3">
                    <table class="reviews-table">
                        <thead><tr><th>Name</th><th>Rating</th><th>Date</th><th>Text</th><th>Action</th></tr></thead>
                        <tbody>
                            @foreach($reviews as $rev)
                            <tr>
                                <td>{{ $rev->reviewer_name }}</td>
                                <td>{{ $rev->rating }} <i class="fas fa-star text-warning small"></i></td>
                                <td>{{ $rev->review_date }}</td>
                                <td class="review-text-cell">{{ $rev->review_text }}</td>
                                <td><button class="btn btn-sm btn-danger" onclick="deleteReview({{ $rev->id }})"><i class="fas fa-trash"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Section: APK Management -->
        <section id="apkSection" class="dashboard-section d-none">
            <div class="admin-card">
                <h5 class="section-title">Current APK URL</h5>
                <div class="d-flex gap-2 mb-3">
                    <input type="text" id="currentApkUrl" class="form-control" value="{{ $settings->apk_url ?? '' }}" readonly>
                    <button class="btn btn-primary" onclick="copyApkUrl()">Copy</button>
                </div>

                <h5 class="section-title">Update APK Download Link (Google Drive / External)</h5>
                <p class="section-desc">Paste your Google Drive share link or any external download URL below.</p>
                <div class="d-flex gap-2">
                    <input type="text" id="externalApkUrl" class="form-control" placeholder="https://drive.google.com/..." value="{{ $settings->apk_url ?? '' }}">
                    <button class="btn-primary-custom" onclick="saveExternalApkUrl()">Update Link</button>
                </div>
            </div>
        </section>

        <!-- Section: Security -->
        <section id="securitySection" class="dashboard-section d-none">
            <div class="admin-card">
                <h5 class="section-title">Update Admin Password</h5>
                <form id="passwordUpdateForm">
                    @csrf
                    <div class="mb-3"><label class="form-label">Current Password</label><input type="password" name="current_password" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">New Password</label><input type="password" name="new_password" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Confirm New Password</label><input type="password" name="new_password_confirmation" class="form-control" required></div>
                    <button type="submit" class="btn-primary-custom">Update Password</button>
                </form>
            </div>
        </section>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        function showSection(sectionId) {
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            const navLink = document.getElementById('nav-' + sectionId);
            if (navLink) navLink.classList.add('active');

            document.querySelectorAll('.dashboard-section').forEach(s => s.classList.add('d-none'));
            const targetSection = document.getElementById(sectionId + 'Section');
            if (targetSection) targetSection.classList.remove('d-none');

            document.querySelectorAll('.custom-tab-item').forEach(item => item.classList.remove('active'));
            const tab = document.getElementById('tab-' + sectionId);
            if (tab) tab.classList.add('active');

            if (window.innerWidth <= 992 && document.getElementById('sidebar').classList.contains('show')) {
                toggleSidebar();
            }
        }

        function switchToTab(tabId) {
            showSection(tabId);
        }

        function selectTheme(theme) {
            document.querySelectorAll('.theme-option').forEach(opt => opt.classList.remove('active'));
            document.querySelectorAll('.theme-badge').forEach(b => {
                b.classList.replace('active', 'inactive');
                b.innerText = 'Select';
            });

            const selectedOpt = event.currentTarget;
            selectedOpt.classList.add('active');
            const badge = selectedOpt.querySelector('.theme-badge');
            badge.classList.replace('inactive', 'active');
            badge.innerText = 'Selected';

            document.getElementById('selectedThemeInput').value = theme;
        }

        async function saveTheme() {
            const formData = new FormData(document.getElementById('themeSelectionForm'));
            const res = await fetch("{{ route('admin.settings.update') }}", {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: formData
            });
            if (res.ok) alert("Theme updated successfully!");
        }

        function previewIcon(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('iconPreview').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }

        async function removeScreenshot(index) {
            if (!confirm("Remove this screenshot?")) return;
            const res = await fetch("{{ route('admin.settings.remove_screenshot') }}", {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
                body: JSON.stringify({ index })
            });
            if (res.ok) location.reload();
        }

        document.getElementById('playstoreSettingsForm').onsubmit = async function(e) {
            e.preventDefault();
            const res = await fetch("{{ route('admin.settings.update') }}", {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: new FormData(this)
            });
            if (res.ok) alert("Settings saved!");
        };

        document.getElementById('addReviewForm').onsubmit = async function(e) {
            e.preventDefault();
            const res = await fetch("{{ route('admin.reviews.add') }}", {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: new FormData(this)
            });
            if (res.ok) location.reload();
        };

        async function deleteReview(id) {
            if (!confirm("Delete this review?")) return;
            const res = await fetch("{{ route('admin.reviews.delete') }}", {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
                body: JSON.stringify({ id })
            });
            if (res.ok) location.reload();
        }

        function copyApkUrl() {
            const input = document.getElementById("currentApkUrl");
            input.select();
            document.execCommand("copy");
            alert("Copied!");
        }

        async function saveExternalApkUrl() {
            const url = document.getElementById('externalApkUrl').value;
            if(!url) return alert("Please enter a URL");

            try {
                const res = await fetch("{{ route('admin.apk.update_url') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ apk_url: url })
                });

                const data = await res.json();
                if (res.ok) {
                    alert(data.message || "APK URL updated successfully!");
                    location.reload();
                } else {
                    alert(data.message || "Failed to update URL");
                }
            } catch (error) {
                alert("An error occurred. Please check console.");
                console.error(error);
            }
        }

        document.getElementById('passwordUpdateForm').onsubmit = async function(e) {
            e.preventDefault();
            const res = await fetch("{{ route('admin.password.update') }}", {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'},
                body: new FormData(this)
            });
            if (res.ok) alert("Password updated!");
        };
    </script>
</body>
</html>
