
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page->title ?? 'Privacy Policy'); ?> - <?php echo e(config('app.name')); ?></title>
    <meta name="description" content="Privacy Policy for <?php echo e(config('app.name')); ?>. Learn how we collect, use, and protect your personal information.">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo e($page->title ?? 'Privacy Policy'); ?>">
    <meta property="og:description" content="Privacy Policy for <?php echo e(config('app.name')); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo e($page->title ?? 'Privacy Policy'); ?>">
    <meta name="twitter:description" content="Privacy Policy for <?php echo e(config('app.name')); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons (optional for better icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            min-height: 100vh;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Navigation Breadcrumb */
        .breadcrumb {
            background-color: white;
            padding: 1rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .breadcrumb-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            list-style: none;
            font-size: 0.9rem;
        }

        .breadcrumb-item {
            color: #718096;
        }

        .breadcrumb-item a {
            color: #4299e1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #2b6cb0;
        }

        .breadcrumb-separator {
            margin: 0 0.5rem;
            color: #cbd5e0;
        }

        /* Main Container */
        .main-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .page-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #f7fafc 0%, #ffffff 100%);
            padding: 2.5rem 2rem;
            border-bottom: 3px solid #4299e1;
            text-align: center;
        }

        .page-title {
            color: #2d3748;
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #4299e1, #667eea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .last-updated {
            color: #718096;
            font-style: italic;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .update-badge {
            background-color: #e6fffa;
            color: #234e52;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid #b2f5ea;
        }

        /* Page Content */
        .page-content {
            padding: 2.5rem;
            line-height: 1.8;
        }

        .page-content h2 {
            color: #2d3748;
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            margin-top: 2.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
            position: relative;
        }

        .page-content h2:first-child {
            margin-top: 0;
        }

        .page-content h2::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: linear-gradient(135deg, #4299e1, #667eea);
        }

        .page-content h3 {
            color: #4a5568;
            font-size: 1.35rem;
            font-weight: 500;
            margin-bottom: 1rem;
            margin-top: 2rem;
        }

        .page-content h4 {
            color: #4a5568;
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
            margin-top: 1.5rem;
        }

        .page-content p {
            margin-bottom: 1.25rem;
            text-align: justify;
            color: #4a5568;
        }

        .page-content ul, .page-content ol {
            margin: 1.25rem 0;
            padding-left: 2rem;
        }

        .page-content li {
            margin-bottom: 0.75rem;
            color: #4a5568;
        }

        .page-content li::marker {
            color: #4299e1;
        }

        /* Special Content Boxes */
        .highlight-box {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            border: 1px solid #feb2b2;
            border-left: 4px solid #e53e3e;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 8px;
            position: relative;
        }

        .highlight-box::before {
            content: "⚠️";
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .info-box {
            background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
            border: 1px solid #90cdf4;
            border-left: 4px solid #4299e1;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 8px;
            position: relative;
        }

        .info-box::before {
            content: "ℹ️";
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .success-box {
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
            border: 1px solid #9ae6b4;
            border-left: 4px solid #38a169;
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 8px;
            position: relative;
        }

        .success-box::before {
            content: "✅";
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        /* Contact Section */
        .contact-section {
            background: linear-gradient(135deg, #edf2f7 0%, #e2e8f0 100%);
            border-radius: 12px;
            padding: 2rem;
            margin: 2.5rem 0;
            border: 1px solid #cbd5e0;
        }

        .contact-section h3 {
            color: #2d3748;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background-color: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .contact-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4299e1, #667eea);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        /* Page Actions */
        .page-actions {
            background-color: #f7fafc;
            padding: 2rem;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4299e1, #667eea);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3182ce, #553c9a);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.4);
        }

        .btn-secondary {
            background-color: white;
            color: #4a5568;
            border-color: #e2e8f0;
        }

        .btn-secondary:hover {
            background-color: #f7fafc;
            border-color: #cbd5e0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Footer */
        .footer {
            background-color: #2d3748;
            color: #a0aec0;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .footer p {
            margin-bottom: 0.5rem;
        }

        .footer a {
            color: #4299e1;
            text-decoration: none;
        }

        .footer a:hover {
            color: #63b3ed;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 2rem 1rem;
            }

            .page-container {
                border-radius: 8px;
            }

            .page-header {
                padding: 2rem 1.5rem;
            }

            .page-title {
                font-size: 1.75rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .page-content {
                padding: 2rem 1.5rem;
            }

            .page-content h2 {
                font-size: 1.5rem;
            }

            .contact-info {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }

            .breadcrumb-container {
                padding: 0 1rem;
            }
        }

        /* Print Styles */
        @media print {
            .header,
            .breadcrumb,
            .page-actions,
            .footer {
                display: none;
            }

            .page-container {
                box-shadow: none;
                border: none;
            }

            .main-container {
                margin: 0;
                padding: 0;
            }

            .page-content {
                padding: 1rem;
            }

            body {
                background: white;
            }
        }

        /* Loading Animation */
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid #4299e1;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Focus styles for accessibility */
        .btn:focus,
        a:focus {
            outline: 2px solid #4299e1;
            outline-offset: 2px;
        }

        /* Dark mode support (optional) */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
                color: #e2e8f0;
            }

            .page-container {
                background-color: #2d3748;
                border-color: #4a5568;
            }

            .page-content h2,
            .page-content h3,
            .page-content h4,
            .page-content p,
            .page-content li {
                color: #e2e8f0;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Spinner -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>

    <!-- Header -->
    <header class="header">
        <h1><?php echo e($page->title ?? 'Privacy Policy'); ?></h1>
        <p class="subtitle"><?php echo e(config('app.name')); ?> - Protecting Your Privacy</p>
    </header>


    <!-- Main Content -->
    <main class="main-container">
        <article class="page-container">
            <!-- Page Header -->
            <header class="page-header">
                <h1 class="page-title"><?php echo e($page->title ?? 'Privacy Policy'); ?></h1>
              
            </header>

            <!-- Page Content -->
            <div class="page-content">
                <?php echo $page->content; ?>


               

                <!-- Important Notice -->
                <div class="highlight-box">
                    <strong>Important:</strong> By using our website and services, you acknowledge that you have read, understood, and agree to be bound by this Privacy Policy. If you do not agree with any part of this policy, please do not use our services.
                </div>
            </div>

            <!-- Page Actions -->
            <footer class="page-actions">
                <div class="action-buttons">
                   
                    <a href="<?php echo e(route('terms.conditions')); ?>" class="btn btn-secondary">
                        <i class="bi bi-file-text"></i>
                        Terms & Conditions
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="bi bi-printer"></i>
                        Print Page
                    </button>
                </div>
            </footer>
        </article>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.</p>
        <p>
            <a href="<?php echo e(route('privacy.policy')); ?>">Privacy Policy</a> | 
            <a href="<?php echo e(route('terms.conditions')); ?>">Terms & Conditions</a> | 
        </p>
    </footer>

    <!-- JavaScript -->
    <script>
        // Show loading spinner on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading spinner
            document.getElementById('loading').style.display = 'none';
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add copy to clipboard functionality for contact info
            document.querySelectorAll('a[href^="mailto:"], a[href^="tel:"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    // Optional: Add analytics tracking here
                    console.log('Contact link clicked:', this.href);
                });
            });

            // Add table of contents if the content is long
            generateTableOfContents();
        });

        // Generate table of contents for long content
        function generateTableOfContents() {
            const headings = document.querySelectorAll('.page-content h2');
            if (headings.length > 3) {
                const tocContainer = document.createElement('div');
                tocContainer.className = 'info-box';
                tocContainer.innerHTML = '<h3><i class="bi bi-list"></i> Table of Contents</h3>';
                
                const tocList = document.createElement('ol');
                headings.forEach((heading, index) => {
                    const id = `section-${index + 1}`;
                    heading.id = id;
                    
                    const listItem = document.createElement('li');
                    const link = document.createElement('a');
                    link.href = `#${id}`;
                    link.textContent = heading.textContent;
                    link.style.color = '#4299e1';
                    link.style.textDecoration = 'none';
                    
                    listItem.appendChild(link);
                    tocList.appendChild(listItem);
                });
                
                tocContainer.appendChild(tocList);
                
                const firstHeading = document.querySelector('.page-content h2');
                if (firstHeading) {
                    firstHeading.parentNode.insertBefore(tocContainer, firstHeading);
                }
            }
        }

        // Print functionality
        function printPage() {
            window.print();
        }

        // Back to top functionality (if page is long)
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                if (!document.getElementById('back-to-top')) {
                    const backToTop = document.createElement('button');
                    backToTop.id = 'back-to-top';
                    backToTop.innerHTML = '<i class="bi bi-arrow-up"></i>';
                    backToTop.className = 'btn btn-primary';
                    backToTop.style.position = 'fixed';
                    backToTop.style.bottom = '2rem';
                    backToTop.style.right = '2rem';
                    backToTop.style.zIndex = '1000';
                    backToTop.style.borderRadius = '50%';
                    backToTop.style.width = '50px';
                    backToTop.style.height = '50px';
                    backToTop.style.padding = '0';
                    backToTop.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
                    document.body.appendChild(backToTop);
                }
            } else {
                const backToTop = document.getElementById('back-to-top');
                if (backToTop) {
                    backToTop.remove();
                }
            }
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/privacy.blade.php ENDPATH**/ ?>