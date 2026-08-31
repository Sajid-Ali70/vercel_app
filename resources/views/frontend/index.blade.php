<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->app_name ?? 'Alfa Mobiles' }} - Google Play</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $settings->app_icon ?? asset('asset/image/01_app_icon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @php
        $appName = $settings->app_name ?? 'Alfa Mobiles';
    @endphp
    <!-- Google Play Header -->
    <nav class="navbar navbar-play">
        <div class="container d-flex justify-content-between align-items-center flex-nowrap px-2">
            <div class="d-flex align-items-center flex-shrink-1 overflow-hidden">
                <i class="fas fa-arrow-left nav-icon d-md-none me-2 ms-0"></i>
                <a href="{{ url('/') }}" class="d-flex align-items-center">
                    <img src="https://www.gstatic.com/android/market_images/web/play_prism_hlock_2x.png" alt="Google Play" class="play-logo">
                </a>
            </div>
            <div class="d-flex align-items-center flex-shrink-0">
                <i class="fas fa-search nav-icon"></i>
                <i class="fas fa-history nav-icon"></i>
                <div class="profile-circle">U</div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <!-- App Header Section -->
        <div class="app-header">
            <img src="{{ $settings->app_icon ?? asset('asset/image/01_app_icon.png') }}" alt="App Icon" class="app-icon" onerror="this.src='https://placehold.co/96x96?text=App+Icon'">
            <div class="app-title-container">
                <h1>{{ $appName }}</h1>
                <div class="dev-name">{{ $settings->developer ?? ($appName . ' Mart Karachi') }}</div>
                <div class="app-meta">{{ $settings->tags ?? 'Contains ads · In-app purchases' }}</div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-item">
                <span class="stat-value">{{ $settings->rating_score ?? '4.3' }} <i class="fas fa-star small"></i></span>
                <span class="stat-label">{{ $settings->reviews_count ?? '1.9K reviews' }}</span>
            </div>
            <div class="stat-item border-start border-end">
                <span class="stat-value">{{ $settings->downloads_count ?? '3K+' }}</span>
                <span class="stat-label">Downloads</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">
                    <div class="content-rating-box">
                        @if(($settings->content_rating ?? '') == 'Teen')
                            T
                        @else
                            3+
                        @endif
                    </div>
                </span>
                <span class="stat-label">{{ $settings->content_rating ?? 'Rated for 3+' }} <i class="fas fa-info-circle small"></i></span>
            </div>
        </div>

        <!-- Action Buttons -->
        @php
            $apkUrl = $settings->apk_url ?? '#';
            $isExternal = str_starts_with($apkUrl, 'http');
        @endphp
        <a href="{{ $apkUrl }}" {{ $isExternal ? 'target="_blank"' : 'download' }} class="btn btn-install text-decoration-none d-flex align-items-center justify-content-center">Install</a>

        <div class="text-center mb-4">
            <a href="#" class="dev-name text-decoration-none">
                <i class="far fa-plus-square me-1"></i> Add to wishlist
            </a>
        </div>

        <div class="section-content mb-4 text-center">
            <i class="fas fa-tablet-alt me-2" style="color: var(--play-green)"></i>
            This app is compatible with all of your devices.
        </div>

        <!-- Screenshots -->
        <div class="screenshots-container">
            @php
                $screenshots = json_decode($settings->screenshots ?? '[]', true);
            @endphp
            @if(count($screenshots) > 0)
                @foreach($screenshots as $ss)
                    <img src="{{ $ss }}" class="screenshot" alt="Screenshot">
                @endforeach
            @else
                <img src="{{ asset('asset/image/02_shop_front.png') }}" class="screenshot" alt="Screenshot 1">
                <img src="{{ asset('asset/image/03_shop_interior.png') }}" class="screenshot" alt="Screenshot 2">
                <img src="{{ asset('asset/image/04_shop_counter.png') }}" class="screenshot" alt="Screenshot 3">
                <img src="{{ asset('asset/image/05_book_your_order_screen.png') }}" class="screenshot" alt="Screenshot 4">
                <img src="{{ asset('asset/image/06_select_mobile_screen.png') }}" class="screenshot" alt="Screenshot 5">
            @endif
        </div>

        <!-- Category Tag -->
        <div class="mb-4">
            <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-normal">{{ $settings->category ?? 'Shopping' }}</span>
        </div>

        <!-- About App -->
        <div class="section-header">
            <h2>About this app</h2>
            <i class="fas fa-arrow-right section-arrow"></i>
        </div>
        <div class="section-content">
            @php
                $defaultDesc = "$appName is Pakistan's trusted online mobile shopping app. Buy 100% original smartphones on easy monthly installments. No advance payment, 0% markup, no hidden charges. Enjoy a safe, simple & reliable shopping experience with $appName.";
            @endphp
            {!! nl2br(e($settings->description ?? $defaultDesc)) !!}
        </div>

        <div class="d-flex gap-2 mb-4">
            <span class="badge bg-light text-dark border p-2 fw-normal rounded-pill">Updated on {{ $settings->updated_date ?? 'Aug 14, 2026' }}</span>
            <span class="badge bg-light text-dark border p-2 fw-normal rounded-pill">#{{ $settings->category ?? 'Shopping' }}</span>
        </div>

        <!-- What's New -->
        <div class="section-header">
            <h2>What's new</h2>
            <i class="fas fa-arrow-right section-arrow"></i>
        </div>
        <div class="section-content">
            {!! nl2br(e($settings->release_notes ?? '• Bug fixes and overall user experience improvements')) !!}
        </div>

        <!-- Data Safety -->
        <div class="section-header">
            <h2>Data safety</h2>
            <i class="fas fa-arrow-right section-arrow"></i>
        </div>
        <div class="section-content">
            At {{ $appName }}, your privacy and data security is our top priority. We do not share your personal information with any third party.
        </div>
        <div class="data-safety-box">
            <div class="safety-item">
                <i class="fas fa-user-shield"></i>
                <div>
                    <span class="safety-item-title">No data shared with third parties</span>
                    <span class="safety-item-desc">{{ $appName }} does not share your personal data with any other companies or organizations.</span>
                </div>
            </div>
            <div class="safety-item">
                <i class="fas fa-lock"></i>
                <div>
                    <span class="safety-item-title">Data is encrypted in transit</span>
                    <span class="safety-item-desc">Your data is transferred over a secure, encrypted HTTPS connection.</span>
                </div>
            </div>
            <div class="safety-item">
                <i class="fas fa-trash-alt"></i>
                <div>
                    <span class="safety-item-title">You can request that data be deleted</span>
                    <span class="safety-item-desc">You can contact us anytime to request deletion of your personal data.</span>
                </div>
            </div>
        </div>

        <!-- Ratings and Reviews -->
        <div class="section-header">
            <h2>Ratings and reviews</h2>
            <i class="fas fa-arrow-right section-arrow"></i>
        </div>
        <div class="ratings-container">
            <div class="rating-big">{{ $settings->rating_score ?? '4.3' }}</div>
            <div class="rating-bars">
                <div class="rating-bar-row">
                    <span class="bar-num">5</span>
                    <div class="bar-bg"><div class="bar-fill" style="width: 75%"></div></div>
                </div>
                <div class="rating-bar-row">
                    <span class="bar-num">4</span>
                    <div class="bar-bg"><div class="bar-fill" style="width: 15%"></div></div>
                </div>
                <div class="rating-bar-row">
                    <span class="bar-num">3</span>
                    <div class="bar-bg"><div class="bar-fill" style="width: 5%"></div></div>
                </div>
                <div class="rating-bar-row">
                    <span class="bar-num">2</span>
                    <div class="bar-bg"><div class="bar-fill" style="width: 2%"></div></div>
                </div>
                <div class="rating-bar-row">
                    <span class="bar-num">1</span>
                    <div class="bar-bg"><div class="bar-fill" style="width: 3%"></div></div>
                </div>
                <div class="text-end small text-muted mt-1">{{ $settings->reviews_count ?? '1.9K reviews' }}</div>
            </div>
        </div>

        <!-- Dynamic Reviews -->
        @php
            $reviews = \Illuminate\Support\Facades\DB::table('app_reviews')->orderBy('id', 'desc')->get();
        @endphp

        @forelse($reviews as $review)
        <div class="review-item">
            <div class="reviewer-header">
                <div class="reviewer-avatar">{{ $review->avatar_letter }}</div>
                <span class="reviewer-name">{{ $review->reviewer_name }}</span>
                <i class="fas fa-ellipsis-v ms-auto text-secondary small"></i>
            </div>
            <div class="review-stars">
                @for($i = 0; $i < 5; $i++)
                    <i class="fas fa-star {{ $i < $review->rating ? '' : 'text-muted' }}"></i>
                @endfor
                <span class="review-date">{{ $review->review_date }}</span>
            </div>
            <div class="section-content mb-2">
                {{ $review->review_text }}
            </div>
            <div class="small text-muted mb-3 d-flex align-items-center">
                Was this review helpful?
                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-0 ms-2" style="font-size: 11px;">Yes</button>
                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-0 ms-1" style="font-size: 11px;">No</button>
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-muted">No reviews yet.</div>
        @endforelse

        <button class="btn btn-outline-success w-100 rounded-pill mb-5 py-2 fw-medium" style="font-size: 14px; border-color: #dadce0; color: var(--play-green)">See all reviews</button>

    </main>

    <footer class="footer-play">
        <div class="container text-center">
            <div class="footer-links">
                <a href="#">Google Play</a>
                <a href="#">Play Pass</a>
                <a href="#">Play Points</a>
                <a href="#">Gift cards</a>
                <a href="#">Redeem</a>
                <a href="#">Refund policy</a>
            </div>
            <div class="mt-3">
                &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/firebase-config.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
