<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help Me Find</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('build/assets/welcome.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/bar.css') }}" rel="stylesheet">

    @vite(['resources/css/welcome.css'])
    @vite(['resources/css/bar.css'])

</head>
<body>
    <nav>
    <div class="logo">
        <a href="/">Help-Me-Find</a>
    </div>
    <ul class="options">
        <a href="{{ route('login') }}"> Login </a>
        <a href="{{ route('register') }}"> Register </a>
    </ul>
    </nav>
    <div class="min-h-screen">
        <div class="hero-section">
            <div class="container">
                <div class="hero-content space-y-8">
                    {{-- <div>
                        <div class="hero-badge">
                            <i class="fas fa-hand-holding-hand icon"></i>
                            <span>Help-Me-Find</span>
                        </div>
                    </div> --}}
                    <div class="space-y-4">
                        <h1 class="hero-title">
                                {{-- UPDATED --}}
                            <span><i class="fa-solid fa-hand-paper-o"></i> Help-Me-Find!</span>

                            {{-- <span>Help-Me-Find!</span> --}}
                        </h1>
                        <h2 class="hero-title" style="font-size: 30px">
                            Never Lose Your
                            <br />
                            <span class="text-primary">Valuable Items Again</span>
                        </h2>
                        <p class="hero-subtitle">
                            A modern platform to help you recover lost items through QR codes,
                            AI-powered matching, and direct communication with finders.
                        </p>
                    </div>
                    <div class="hero-buttons">
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            Get Started <i class="fas fa-arrow-right icon"></i>
                        </a>
                        {{-- <a href="#" class="btn btn-outline">
                            Learn More
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="section-header space-y-4">
                <h2 class="section-title">How It Works</h2>
                <p class="section-description">
                    Simple, secure, and effective item recovery in three easy steps.
                </p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-qrcode icon"></i>
                    </div>
                    <h3 class="feature-card-title">Generate Your QR Code</h3>
                    <p class="feature-card-description">
                        Create a unique QR code linked to your contact information.
                        Print and attach it to your valuable items.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-search icon"></i>
                    </div>
                    <h3 class="feature-card-title">Report & Browse Items</h3>
                    <p class="feature-card-description">
                        Found an item? Report it with AI-generated descriptions.
                        Lost something? Search through found items instantly.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-comment icon"></i>
                    </div>
                    <h3 class="feature-card-title">Connect & Recover</h3>
                    <p class="feature-card-description">
                        Message item finders directly through our secure platform
                        and arrange safe returns.
                    </p>
                </div>
            </div>
        </div>

        {{-- <div class="container section-padding">
            <div class="stats-grid">
                <div class="stat-item space-y-2">
                    <div class="stat-number">10K+</div>
                    <p class="stat-label">Items Recovered</p>
                </div>
                <div class="stat-item space-y-2">
                    <div class="stat-number">50K+</div>
                    <p class="stat-label">Active Users</p>
                </div>
                <div class="stat-item space-y-2">
                    <div class="stat-number">98%</div>
                    <p class="stat-label">Success Rate</p>
                </div>
            </div>
        </div> --}}

        {{-- <div class="container section-padding">
            <div class="why-us-card">
                <div class="why-us-grid">
                    <div class="why-us-content space-y-6">
                        <div>
                            <h3 class="why-us-title">Why Choose Help-Me-Find?</h3>
                            <p class="why-us-description">
                                We combine cutting-edge technology with human connection to
                                reunite you with your lost belongings.
                            </p>
                        </div>
                        <div class="why-us-points space-y-4">
                            <div class="point-item">
                                <div class="point-icon-wrapper">
                                    <i class="fas fa-shield-alt icon"></i>
                                </div>
                                <div class="point-content">
                                    <h4>Secure & Private</h4>
                                    <p>Your personal information is protected with end-to-end encryption.</p>
                                </div>
                            </div>
                            <div class="point-item">
                                <div class="point-icon-wrapper">
                                    <i class="fas fa-qrcode icon"></i>
                                </div>
                                <div class="point-content">
                                    <h4>Smart QR Technology</h4>
                                    <p>Easy-to-use QR codes that anyone can scan to help return your items.</p>
                                </div>
                            </div>
                            <div class="point-item">
                                <div class="point-icon-wrapper">
                                    <i class="fas fa-comment icon"></i>
                                </div>
                                <div class="point-content">
                                    <h4>Direct Communication</h4>
                                    <p>Chat directly with finders without sharing personal contact details.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="qr-code-wrapper">
                        <div class="qr-code-box">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=https://help-me-find.app" alt="QR Code Example" />
                            <p class="qr-code-caption">
                                Example QR Code
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="container cta-section space-y-6">
            <h2 class="cta-title">Ready to Get Started?</h2>
            <p class="cta-description">
                Join thousands of users who have successfully recovered their lost items.
                Create your account today and never worry about losing your valuables again.
            </p>
            <a href="{{ route('register') }}" class="btn btn-primary">
                Create Free Account <i class="fas fa-arrow-right icon"></i>
            </a>
        </div> --}}
    </div>

    @include('layouts.footer')
</body>
</html>
