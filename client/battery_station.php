<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pin & Trạm Sạc VinFast - Giải Pháp Năng Lượng Xanh</title>
    <?php include('home_css.php'); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            overflow-x: hidden;
            min-height: 100vh;
            position: relative;
        }

        /* Ensure proper scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Fix scroll issues */
        body,
        html {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Hero Section */
        .hero-banner {
            background: linear-gradient(135deg, #0f4c75 0%, #3282b8 50%, #0f4c75 100%);
            padding: 120px 0 100px;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 400"><defs><pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"><path d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
        }

        .hero-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .hero-content h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.02em;
        }

        .hero-content .subtitle {
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            font-weight: 400;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .stat-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Main Content */
        .battery_content {
            padding: 100px 0;
            background: #fafbfc;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            /* Adjusted margin */
        }

        .section-title h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .section-title p {
            font-size: 1.2rem;
            color: #6b7280;
            max-width: 600px;
            /* Increased max-width */
            margin: 0 auto;
            font-weight: 400;
        }

        /* Content Sections */
        .intro-content-left,
        .intro-content-right {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            margin-bottom: 120px;
            background: white;
            border-radius: 30px;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .intro-content-left::before,
        .intro-content-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3282b8, #0f4c75);
            border-radius: 30px 30px 0 0;
        }

        .intro-content-left:hover,
        .intro-content-right:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.12);
        }

        .left-content,
        .right-content {
            padding: 20px 0;
        }

        .content-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1e40af;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 50px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .content-badge i {
            font-size: 0.8rem;
        }

        .left-content h2,
        .right-content h2 {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 24px;
            line-height: 1.3;
            letter-spacing: -0.02em;
        }

        .left-content p,
        .right-content p {
            font-size: 1.1rem;
            color: #4b5563;
            line-height: 1.7;
            font-weight: 400;
        }

        .content-image {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
        }

        .content-image:hover {
            transform: scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .content-image img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: all 0.4s ease;
        }

        .content-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(50, 130, 184, 0.1), rgba(15, 76, 117, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .content-image:hover::after {
            opacity: 1;
        }

        /* Services Grid */
        .services-section {
            padding: 100px 0;
            background: white;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .service-card {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 24px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.4s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(50, 130, 184, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .service-card:hover::before {
            left: 100%;
        }

        .service-card:hover {
            transform: translateY(-10px);
            border-color: #3282b8;
            box-shadow: 0 25px 50px rgba(50, 130, 184, 0.15);
            background: white;
        }

        .service-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #3282b8, #0f4c75);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .service-card h3 {
            font-size: 1.4rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 16px;
            letter-spacing: -0.01em;
        }

        .service-card p {
            color: #6b7280;
            line-height: 1.6;
            font-size: 1rem;
        }

        /* CTA Section */
        .cta-section {
            padding: 100px 0 120px;
            background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%);
            position: relative;
            overflow: hidden;
            margin-bottom: 0;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300"><path fill="rgba(255,255,255,0.05)" d="M0,100 Q250,50 500,100 T1000,100 L1000,0 L0,0 Z"/></svg>') top center/cover no-repeat;
        }

        .cta-content {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .cta-content h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .cta-content p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: white;
            color: #0f4c75;
            border: 2px solid white;
        }

        .btn-primary:hover {
            background: transparent;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: white;
            color: #0f4c75;
            border-color: white;
            transform: translateY(-3px);
        }

        /* --- START NEW CHARGING STATION FINDER UI --- */
        .station-finder-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e5f3ff 100%);
            position: relative;
        }

        .station-finder-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 300"><path fill="rgba(50,130,184,0.03)" d="M0,100 Q250,50 500,100 T1000,100 L1000,200 Q750,250 500,200 T0,200 Z"/></svg>') center/cover no-repeat;
            pointer-events: none;
        }

        .finder-container {
            display: grid;
            grid-template-columns: 350px 1fr;
            /* Smaller sidebar, more space for results */
            gap: 20px;
            background: white;
            border-radius: 28px;
            box-shadow: 0 25px 80px rgba(50, 130, 184, 0.15);
            border: 1px solid #e1f5fe;
            overflow: hidden;
            height: 800px;
            /* Increased height */
            max-height: 90vh;
            min-height: 650px;
            position: relative;
            transition: all 0.3s ease;
        }

        .finder-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 30px 90px rgba(50, 130, 184, 0.2);
        }

        .finder-sidebar {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: linear-gradient(180deg, #fdfdfd 0%, #f8fafc 100%);
            border-right: 1px solid #e1f5fe;
            position: relative;
        }

        .finder-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #3282b8 0%, #0f4c75 100%);
        }

        .search-panel {
            padding: 30px 25px;
            border-bottom: 1px solid #e1f5fe;
            flex-shrink: 0;
            background: white;
            position: relative;
        }

        .search-panel::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25px;
            right: 25px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #3282b8, transparent);
        }

        .search-panel h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-panel .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.9rem;
        }

        .search-panel .search-input {
            width: 100%;
            padding: 14px 18px 14px 45px;
            /* Add padding for icon */
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .search-panel .search-input:focus {
            outline: none;
            border-color: #3282b8;
            box-shadow: 0 0 0 4px rgba(50, 130, 184, 0.15);
        }

        .btn-finder {
            padding: 15px 22px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-size: 1rem;
            white-space: nowrap;
        }

        .search-panel .btn-finder.btn-primary {
            width: 100%;
            background: #3282b8;
            color: white;
            padding: 15px;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .search-panel .btn-finder.btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .search-panel .location-controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .search-panel .location-controls .btn-finder {
            padding: 12px;
        }

        .btn-finder.btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-finder.btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .btn-finder.btn-outline-primary {
            background: transparent;
            color: #3282b8;
            border: 2px solid #3282b8;
        }

        .btn-finder.btn-outline-primary:hover {
            background: #3282b8;
            color: white;
        }

        .btn-finder.btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .results-panel {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
            background: #fafbfc;
        }

        .results-header {
            padding: 20px 25px;
            border-bottom: 2px solid #e1f5fe;
            background: linear-gradient(135deg, #f8fafc 0%, #e3f2fd 100%);
            flex-shrink: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }


        @keyframes pulse {

            0%,
            100% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }
        }

        /* Enhanced scroll indicator fade effects */
        .results-panel::before,
        .results-panel::after {
            content: '';
            position: absolute;
            left: 0;
            right: 15px;
            /* Account for scrollbar */
            height: 20px;
            z-index: 10;
            pointer-events: none;
            transition: opacity 0.4s ease;
            opacity: 0;
        }

        .results-panel::before {
            top: 75px;
            /* After header */
            background: linear-gradient(to bottom, rgba(250, 251, 252, 0.95) 0%, rgba(250, 251, 252, 0.5) 70%, transparent 100%);
        }

        .results-panel::after {
            bottom: 0;
            background: linear-gradient(to top, rgba(250, 251, 252, 0.95) 0%, rgba(250, 251, 252, 0.5) 70%, transparent 100%);
        }

        .results-panel.scroll-top::before {
            opacity: 1;
            animation: fadeInGradient 0.3s ease-in;
        }

        .results-panel.scroll-bottom::after {
            opacity: 1;
            animation: fadeInGradient 0.3s ease-in;
        }

        @keyframes fadeInGradient {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .results-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .station-count {
            font-size: 0.9rem;
            color: #3282b8;
            background: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid #3282b8;
            box-shadow: 0 2px 8px rgba(50, 130, 184, 0.15);
            position: relative;
        }

        /* Scroll Navigation Controls */
        .scroll-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .scroll-btn {
            width: 32px;
            height: 32px;
            border: 2px solid #3282b8;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            color: #3282b8;
            position: relative;
        }

        .scroll-btn:hover {
            background: #3282b8;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(50, 130, 184, 0.3);
        }

        .scroll-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(50, 130, 184, 0.4);
        }

        .scroll-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .scroll-btn:disabled:hover {
            background: white;
            color: #3282b8;
        }

        .scroll-position {
            font-size: 0.75rem;
            color: #6b7280;
            min-width: 50px;
            text-align: center;
            font-weight: 600;
            background: rgba(50, 130, 184, 0.1);
            padding: 4px 8px;
            border-radius: 12px;
        }

        /* Quick scroll buttons */
        .quick-scroll-controls {
            display: flex;
            flex-direction: column;
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 15;
            gap: 4px;
        }

        .quick-scroll-btn {
            width: 24px;
            height: 24px;
            background: rgba(50, 130, 184, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            transition: all 0.2s ease;
            opacity: 0;
            transform: scale(0.8);
        }

        .results-panel.has-scroll .quick-scroll-btn {
            opacity: 1;
            transform: scale(1);
        }

        .quick-scroll-btn:hover {
            background: #2563eb;
            transform: scale(1.1);
        }

        .quick-scroll-btn:active {
            transform: scale(0.95);
        }

        .scroll-hint {
            font-size: 0.8rem;
            color: #6b7280;
            margin-left: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .results-panel.has-scroll .scroll-hint {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .scroll-hint {
                display: none;
            }

            .scroll-controls {
                gap: 4px;
            }

            .scroll-btn {
                width: 28px;
                height: 28px;
                font-size: 0.7rem;
            }
        }

        .stations-list-container {
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 15px 20px 25px;
            scroll-behavior: smooth;
            scrollbar-width: thin;
            scrollbar-color: #3282b8 #f1f5f9;
            height: calc(100% - 70px);
            /* Optimized height calculation */
            max-height: calc(100vh - 320px);
            /* Ensure it doesn't exceed viewport */
            min-height: 450px;
            /* Minimum height to show multiple stations */
            outline: none;
            background: #fafbfc;
            position: relative;
        }

        /* Add scroll progress indicator */
        .stations-list-container::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 3px;
            height: var(--scroll-progress, 0%);
            background: linear-gradient(to bottom, #3282b8, #2563eb);
            z-index: 20;
            border-radius: 2px;
            transition: height 0.2s ease;
        }

        .stations-list-container:focus-visible {
            box-shadow: inset 0 0 0 2px #3282b8;
            border-radius: 4px;
        }

        /* Scroll hint on hover */
        .stations-list-container:not(:focus):hover {
            scrollbar-color: #2563eb #e3f2fd;
        }

        /* Enhanced Custom Scrollbar */
        .stations-list-container::-webkit-scrollbar {
            width: 10px;
        }

        .stations-list-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
            margin: 4px;
            border: 1px solid #e3f2fd;
        }

        .stations-list-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #3282b8 0%, #2563eb 100%);
            border-radius: 8px;
            border: 2px solid #f1f5f9;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(50, 130, 184, 0.2);
        }

        .stations-list-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-color: #e3f2fd;
            transform: scale(1.05);
        }

        .stations-list-container::-webkit-scrollbar-thumb:active {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
        }

        .station-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid #e1f5fe;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(50, 130, 184, 0.08);
            position: relative;
            overflow: hidden;
        }

        .station-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(50, 130, 184, 0.05), transparent);
            transition: left 0.6s ease;
        }

        .station-card:hover::before {
            left: 100%;
        }

        .station-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(50, 130, 184, 0.15);
            border-color: #3282b8;
            background: #fefefe;
        }

        .station-card:first-child {
            margin-top: 0;
        }

        .station-card:last-child {
            margin-bottom: 30px;
            /* Extra space for last card */
        }

        .station-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }

        .station-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0;
            line-height: 1.2;
        }

        .station-distance {
            font-size: 0.8rem;
            color: #3282b8;
            font-weight: 600;
        }

        .station-address {
            color: #6b7280;
            margin-bottom: 8px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .station-info {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
            color: #4b5563;
        }

        .info-item i {
            color: #3282b8;
            width: 12px;
            font-size: 0.75rem;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .status-badge.available {
            background: #d1fae5;
            color: #059669;
        }

        .status-badge.busy {
            background: #fef3c7;
            color: #d97706;
        }

        .status-badge.unavailable {
            background: #fee2e2;
            color: #dc2626;
        }

        .station-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }

        .no-results,
        .loading-message,
        .error-message,
        .info-message {
            text-align: center;
            padding: 40px 20px;
            border-radius: 16px;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .loading-message {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: #0369a1;
            border: 1px solid #7dd3fc;
        }

        .error-message {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .info-message {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #2563eb;
            border: 1px solid #93c5fd;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .info-message i {
            font-size: 2rem;
            opacity: 0.7;
        }

        .info-message p {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
        }

        .no-results {
            color: #6b7280;
            font-style: italic;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border: 2px dashed #e5e7eb;
        }

        .finder-map {
            height: 100%;
            padding: 0;
        }

        .finder-map #chargingStationMap {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* --- END NEW CHARGING STATION FINDER UI --- */

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease-out;
            overflow-y: auto;
        }

        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-dialog {
            position: relative;
            width: 100%;
            max-width: 650px;
            margin: auto;
            animation: slideInUp 0.3s ease-out;
            transform: none;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .modal-header {
            padding: 24px 24px 16px;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3282b8, #0f4c75);
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            padding-right: 12px;
        }

        .modal-title::before {
            content: '⚡';
            font-size: 1.2rem;
            color: #3282b8;
            flex-shrink: 0;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }

        .btn-close:hover {
            background: #f3f4f6;
            color: #374151;
            transform: scale(1.1);
        }

        .btn-close::before {
            content: '×';
            font-size: 1.5rem;
            line-height: 1;
        }

        .modal-body {
            padding: 24px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -12px;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 12px;
        }

        .modal-body h6 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        .modal-body h6 i {
            color: #3282b8;
            font-size: 1rem;
            width: 20px;
            flex-shrink: 0;
        }

        .list-unstyled {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list-unstyled li {
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
            color: #4b5563;
            font-size: 0.9rem;
            display: flex;
            align-items: flex-start;
        }

        .list-unstyled li:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .list-unstyled li strong {
            color: #1f2937;
            font-weight: 600;
            min-width: 110px;
            display: inline-block;
            flex-shrink: 0;
            margin-right: 8px;
        }

        .modal-footer {
            padding: 16px 24px 24px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-primary {
            background: #3282b8;
            color: white;
            border-color: #3282b8;
        }

        .btn-primary:hover {
            background: #2563eb;
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border-color: #e5e7eb;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            color: #1f2937;
            transform: translateY(-1px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Responsive Modal */
        @media (max-width: 768px) {
            .modal {
                padding: 10px;
            }

            .modal-dialog {
                max-width: 100%;
                margin: 0;
            }

            .row {
                flex-direction: column;
                margin: -8px;
            }

            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
                padding: 8px;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding-left: 16px;
                padding-right: 16px;
            }

            .modal-title {
                font-size: 1.2rem;
            }

            .modal-footer {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
                width: 100%;
            }

            .list-unstyled li {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .list-unstyled li strong {
                min-width: auto;
                margin-right: 0;
            }
        }


        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 18px;
            border-radius: 8px;
            color: white;
            z-index: 1000;
            max-width: 300px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.3s ease-out;
            font-size: 0.9rem;
            pointer-events: auto;
        }

        /* Ensure notifications don't interfere with scroll */
        .notification+.notification {
            top: calc(20px + 60px);
        }

        .notification-success {
            background-color: #10b981;
        }

        .notification-error {
            background-color: #ef4444;
        }

        .notification-info {
            background-color: #3b82f6;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .finder-container {
                grid-template-columns: 380px 1fr;
                height: 700px;
            }
        }

        @media (max-width: 768px) {
            .hero-banner {
                padding: 80px 0 60px;
            }

            .battery_content {
                padding: 60px 0;
            }

            .intro-content-left,
            .intro-content-right {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 40px 20px;
                margin-bottom: 60px;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .hero-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            /* Enhanced responsive for Charging Station Finder */
            .station-finder-section {
                padding: 60px 0;
            }

            .finder-container {
                grid-template-columns: 1fr;
                height: auto;
                max-height: 85vh;
                min-height: 600px;
                gap: 0;
            }

            .finder-map {
                height: 300px;
                order: -1;
                /* Show map first on mobile */
            }

            .finder-sidebar {
                border-right: none;
                border-top: 1px solid #e1f5fe;
                height: auto;
                max-height: 600px;
            }

            .finder-sidebar::before {
                width: 100%;
                height: 4px;
                top: 0;
                left: 0;
            }

            .search-panel {
                padding: 20px;
            }

            .search-panel h3 {
                font-size: 1.4rem;
                margin-bottom: 20px;
            }

            .results-header {
                padding: 15px 20px;
            }

            .stations-list-container {
                padding: 12px 15px 20px;
                max-height: 400px;
                min-height: 300px;
            }

            .station-card {
                padding: 16px;
                margin-bottom: 12px;
            }

            .station-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .station-distance {
                align-self: flex-end;
            }

            .station-info {
                flex-direction: column;
                gap: 8px;
            }

            .station-actions {
                flex-direction: column;
                gap: 8px;
            }

            .btn-finder {
                width: 100%;
                justify-content: center;
            }

            /* Fix mobile scroll issues */
            body {
                -webkit-overflow-scrolling: touch;
            }

            /* Mobile notification positioning */
            .notification {
                top: 10px;
                right: 10px;
                left: 10px;
                max-width: none;
                margin: 0 auto;
            }

            /* Ensure proper mobile spacing */
            .cta-section {
                padding: 60px 0 80px;
            }

            /* Fix any potential viewport issues */
            .main-content {
                min-height: calc(100vh - 100px);
            }

            /* Ensure proper bottom spacing */
            .page-wrapper {
                padding-bottom: 20px;
            }

            /* Improve mobile scroll indicators */
            .results-panel::before,
            .results-panel::after {
                height: 15px;
            }

            .scroll-top-btn {
                bottom: 15px !important;
                right: 15px !important;
                width: 36px !important;
                height: 36px !important;
                font-size: 0.8rem !important;
            }
        }

        /* Animation Classes */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Footer spacing fix */
        body::after {
            content: '';
            display: block;
            height: 1px;
            clear: both;
        }

        /* Ensure proper page ending */
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
        }

        /* Fix any floating issues */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="main-content">
            <?php include('header.php'); ?>

            <!-- Hero Section -->
            <section class="hero-banner">
                <div class="hero-content">
                    <h1 class="fade-in-up">Pin & Trạm Sạc VinFast</h1>
                    <p class="subtitle fade-in-up">Hệ sinh thái năng lượng xanh toàn diện, dẫn đầu công nghệ tương lai</p>

                    <div class="hero-stats">
                        <div class="stat-item fade-in-up">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Trạm sạc toàn quốc</div>
                        </div>
                        <div class="stat-item fade-in-up">
                            <div class="stat-number">30</div>
                            <div class="stat-label">Phút sạc nhanh</div>
                        </div>
                        <div class="stat-item fade-in-up">
                            <div class="stat-number">10</div>
                            <div class="stat-label">Năm bảo hành pin</div>
                        </div>
                        <div class="stat-item fade-in-up">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Hỗ trợ kỹ thuật</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Station Finder Section - NEW UI -->
            <section class="station-finder-section">
                <div class="container">
                    <div class="section-title fade-in-up">
                        <h2>Tìm Trạm Sạc VinFast Gần Bạn</h2>
                        <p>Mạng lưới trạm sạc rộng khắp, sẵn sàng phục vụ</p>
                    </div>

                    <div class="finder-container fade-in-up">
                        <!-- Left Panel: Search & Results -->
                        <div class="finder-sidebar">
                            <div class="search-panel">
                                <h3>Tìm kiếm trạm sạc</h3>
                                <div class="input-group">
                                    <i class="fas fa-search input-icon"></i>
                                    <input type="text" id="locationSearch" class="search-input" placeholder="Nhập tỉnh thành, quận huyện...">
                                </div>
                                <button type="button" id="searchBtn" class="btn-finder btn-primary">Tìm kiếm</button>

                                <div class="location-controls">
                                    <button type="button" id="currentLocationBtn" class="btn-finder btn-secondary">
                                        <i class="fas fa-crosshairs"></i>
                                        Dùng vị trí hiện tại
                                    </button>
                                    <button type="button" id="nearbyBtn" class="btn-finder btn-outline-primary">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Trạm gần nhất
                                    </button>
                                </div>
                            </div>

                            <div class="results-panel">
                                <div class="results-header">
                                    <h3 class="results-title">Kết quả</h3>
                                    <!-- <div style="display: flex; align-items: center; gap: 10px;">
                                <span id="stationCount" class="station-count">0 trạm sạc</span>
                                <div class="scroll-controls">
                                    <button id="scrollUpBtn" class="scroll-btn" title="Cuộn lên">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <span id="scrollPosition" class="scroll-position">0/0</span>
                                    
                                </div>
                            </div> -->
                                </div>
                                <div id="stationsList" class="stations-list-container">
                                    <div class="info-message">
                                        <i class="fas fa-info-circle"></i>
                                        <p>Bắt đầu tìm kiếm để xem các trạm sạc khả dụng</p>
                                    </div>

                                    <!-- Quick scroll controls -->
                                    <div class="quick-scroll-controls">
                                        <button class="quick-scroll-btn" id="quickScrollTop" title="Cuộn lên đầu">
                                            <i class="fas fa-angle-double-up"></i>
                                        </button>
                                        <button class="quick-scroll-btn" id="quickScrollBottom" title="Cuộn xuống cuối">
                                            <i class="fas fa-angle-double-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Map -->
                        <div class="finder-map">
                            <iframe id="chargingStationMap" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919064.000000!2d105.8544400!3d21.0285000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab9bd9861ca1%3A0xe7887f7b72ca17a9!2sVietnam!5e0!3m2!1sen!2s!4v1699350000000!5m2!1sen!2s" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Content -->
            <div class="battery_content">
                <div class="container">
                    <!-- Section Title -->
                    <div class="section-title fade-in-up">
                        <h2>Giải Pháp Pin & Sạc Thông Minh</h2>
                        <p>Công nghệ tiên tiến, trải nghiệm vượt trội</p>
                    </div>

                    <!-- Battery Rental Section -->
                    <div class="intro-content-left fade-in-up">
                        <div class="left-content">
                            <div class="content-badge">
                                <i class="fas fa-coins"></i>
                                Tiết kiệm chi phí
                            </div>
                            <h2>Thuê pin ô tô điện linh hoạt</h2>
                            <p>Với phương châm luôn đặt lợi ích Khách hàng lên hàng đầu, VinFast áp dụng chính sách cho thuê pin độc đáo, ưu việt và khác biệt với tất cả các mô hình cho thuê pin từ trước tới nay trên thế giới. Giảm chi phí đầu tư ban đầu, tối ưu hóa hiệu quả sử dụng.</p>
                        </div>
                        <div class="content-image">
                            <img alt="Thuê pin VinFast - Giải pháp linh hoạt" src="https://storage.googleapis.com/vinfast-data-01/pin-tramsac-1_1660273470.png">
                        </div>
                    </div>

                    <!-- Charging Solutions Section -->
                    <div class="intro-content-right fade-in-up">
                        <div class="content-image">
                            <img alt="Trạm sạc VinFast - Công nghệ tiên tiến" src="https://storage.googleapis.com/vinfast-data-01/pin-tramsac-2_1660273363.png">
                        </div>
                        <div class="right-content">
                            <div class="content-badge">
                                <i class="fas fa-bolt"></i>
                                Thuận tiện
                            </div>
                            <h2>Đa dạng giải pháp sạc</h2>
                            <p>VinFast cung cấp đa dạng giải pháp sạc để đáp ứng nhu cầu sử dụng của Khách hàng một cách thuận tiện nhất. Từ sạc tại nhà với Wall Box đến mạng lưới trạm sạc nhanh DC trên toàn quốc, đảm bảo trải nghiệm liền mạch.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Section -->
            <section class="services-section">
                <div class="container">
                    <div class="section-title fade-in-up">
                        <h2>Dịch Vụ Toàn Diện</h2>
                        <p>Hệ sinh thái pin và sạc hoàn chỉnh từ VinFast</p>
                    </div>

                    <div class="services-grid">
                        <div class="service-card fade-in-up">
                            <div class="service-icon">
                                <i class="fas fa-charging-station"></i>
                            </div>
                            <h3>Trạm Sạc Nhanh DC</h3>
                            <p>Mạng lưới 500+ trạm sạc nhanh toàn quốc, sạc đầy 80% pin chỉ trong 30-45 phút với công suất tối đa 150kW.</p>
                        </div>

                        <div class="service-card fade-in-up">
                            <div class="service-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <h3>Sạc Tại Nhà AC</h3>
                            <p>Giải pháp Wall Box chuyên dụng, lắp đặt tại nhà với công suất 7-22kW, an toàn và tiện lợi cho việc sạc hàng đêm.</p>
                        </div>

                        <div class="service-card fade-in-up">
                            <div class="service-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h3>Ứng Dụng VinFast</h3>
                            <p>Quản lý pin, tìm kiếm trạm sạc, đặt chỗ trước, thanh toán không tiền mặt và theo dõi lịch sử sạc một cách dễ dàng.</p>
                        </div>
                        <div class="service-card fade-in-up">
                            <div class="service-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3>Hỗ Trợ 24/7</h3>
                            <p>Tổng đài hỗ trợ khách hàng 24/7, ứng cứu khẩn cấp và tư vấn kỹ thuật từ đội ngũ chuyên gia VinFast.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="cta-section">
                <div class="container">
                    <div class="cta-content fade-in-up">
                        <h2>Trải Nghiệm Ngay Hôm Nay</h2>
                        <p>Tham gia cộng đồng VinFast để khám phá thế giới xe điện và năng lượng xanh với công nghệ tiên tiến nhất</p>
                        <div class="cta-buttons">
                            <a href="oto.php" class="btn btn-primary">
                                <i class="fas fa-car-side"></i>
                                Khám Phá Xe Điện
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <?php include('footer.php'); ?>
        </div> <!-- Close main-content -->
    </div> <!-- Close page-wrapper -->

    <script src="../js/slick.js"></script>
    <script>
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all elements with fade-in-up class
        document.addEventListener('DOMContentLoaded', () => {
            const fadeElements = document.querySelectorAll('.fade-in-up');
            fadeElements.forEach(el => observer.observe(el));

            // Add staggered animation delays
            fadeElements.forEach((el, index) => {
                el.style.transitionDelay = `${index * 0.1}s`;
            });
        });

        // Smooth scrolling for CTA buttons
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
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

        // Add loading animation
        window.addEventListener('load', () => {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });

        // Counter animation for hero stats
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');

            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/\D/g, ''));
                const suffix = counter.textContent.replace(/\d/g, '');
                let current = 0;
                const increment = target / 50;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.floor(current) + suffix;
                        setTimeout(updateCounter, 20);
                    } else {
                        counter.textContent = target + suffix;
                    }
                };

                // Start animation when hero stats become visible
                const heroStats = document.querySelector('.hero-stats');
                const statsObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            setTimeout(updateCounter, 500);
                            statsObserver.unobserve(entry.target);
                        }
                    });
                });

                statsObserver.observe(heroStats);
            });
        }

        // Initialize counter animation
        document.addEventListener('DOMContentLoaded', animateCounters);

        // Fix scroll issues and ensure proper UI behavior
        window.addEventListener('scroll', function() {
            // Debounced scroll handler
            clearTimeout(window.scrollTimeout);
            window.scrollTimeout = setTimeout(function() {
                // Check if we're near the bottom of the page
                const scrollHeight = document.documentElement.scrollHeight;
                const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                const clientHeight = window.innerHeight;

                // If we're within 100px of the bottom, ensure UI is still visible
                if (scrollHeight - scrollTop - clientHeight < 100) {
                    // Remove any potential blocking elements or fix their positions
                    document.body.style.paddingBottom = '20px';
                }
            }, 10);
        });

        // Ensure proper page layout on load and resize
        function ensureProperLayout() {
            // Force repaint to fix any layout issues
            document.body.style.display = 'none';
            document.body.offsetHeight; // Trigger reflow
            document.body.style.display = '';

            // Ensure proper min-height calculation
            const viewport = window.innerHeight;
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.style.minHeight = `${viewport}px`;
            }
        }

        window.addEventListener('load', ensureProperLayout);
        window.addEventListener('resize', ensureProperLayout);

        // Charging Station Finder Class
        class ChargingStationFinder {
            constructor() {
                this.stations = [];
                this.filteredStations = [];
                this.userLocation = null;
                this.currentPage = 0;
                this.itemsPerPage = 20;
                this.totalStations = 0;
                this.loading = false;

                this.init();
            }

            init() {
                this.setupEventListeners();
                this.loadAllStations();
            }

            setupEventListeners() {
                const searchInput = document.getElementById('locationSearch');
                const searchBtn = document.getElementById('searchBtn');
                const currentLocationBtn = document.getElementById('currentLocationBtn');
                const nearbyBtn = document.getElementById('nearbyBtn');
                const scrollUpBtn = document.getElementById('scrollUpBtn');
                const scrollDownBtn = document.getElementById('scrollDownBtn');
                const quickScrollTop = document.getElementById('quickScrollTop');
                const quickScrollBottom = document.getElementById('quickScrollBottom');

                if (searchInput) {
                    searchInput.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            this.searchStations();
                        }
                    });
                }

                if (searchBtn) {
                    searchBtn.addEventListener('click', () => {
                        this.searchStations();
                    });
                }

                if (currentLocationBtn) {
                    currentLocationBtn.addEventListener('click', () => {
                        this.getCurrentLocation();
                    });
                }

                if (nearbyBtn) {
                    nearbyBtn.addEventListener('click', () => {
                        if (this.userLocation) {
                            this.getNearbyStations();
                        } else {
                            this.showMessage('Vui lòng cho phép truy cập vị trí trước');
                            this.getCurrentLocation();
                        }
                    });
                }

                // Add scroll button event listeners
                if (scrollUpBtn) {
                    scrollUpBtn.addEventListener('click', () => {
                        this.scrollResults('up');
                    });
                }

                if (scrollDownBtn) {
                    scrollDownBtn.addEventListener('click', () => {
                        this.scrollResults('down');
                    });
                }

                // Add quick scroll button event listeners
                if (quickScrollTop) {
                    quickScrollTop.addEventListener('click', () => {
                        this.scrollToPosition('top');
                    });
                }

                if (quickScrollBottom) {
                    quickScrollBottom.addEventListener('click', () => {
                        this.scrollToPosition('bottom');
                    });
                }
            }

            async loadAllStations() {
                this.loading = true;
                this.showLoadingMessage('Đang tải danh sách trạm sạc...');

                try {
                    const response = await fetch('api_charging_station.php?action=all');
                    const data = await response.json();

                    if (data.success) {
                        this.stations = data.data;
                        this.filteredStations = [...this.stations];
                        this.totalStations = this.stations.length;
                        this.displayStations();
                        this.updateStationCount();
                    } else {
                        this.showError(data.message || 'Không thể tải danh sách trạm sạc');
                    }
                } catch (error) {
                    console.error('Error loading stations:', error);
                    this.showError('Lỗi kết nối khi tải danh sách trạm sạc');
                } finally {
                    this.loading = false;
                }
            }

            async searchStations() {
                const query = document.getElementById('locationSearch').value.trim();

                if (!query && !this.userLocation) {
                    this.showMessage('Vui lòng nhập tên tỉnh thành hoặc sử dụng vị trí hiện tại');
                    return;
                }

                this.loading = true;
                this.showLoadingMessage('Đang tìm kiếm trạm sạc...');

                try {
                    let url = `api_charging_station.php?action=search&limit=${this.itemsPerPage}&offset=${this.currentPage * this.itemsPerPage}`;

                    if (query) url += `&query=${encodeURIComponent(query)}`;

                    if (this.userLocation) {
                        url += `&lat=${this.userLocation.lat}&lng=${this.userLocation.lng}`;
                    }

                    const response = await fetch(url);
                    const data = await response.json();

                    if (data.success) {
                        this.filteredStations = data.data;
                        this.totalStations = data.total || data.data.length;
                        this.displayStations();
                        this.updateStationCount();

                        if (data.data.length === 0) {
                            this.showMessage(`Không tìm thấy trạm sạc nào với từ khóa "${query}"`);
                        }
                    } else {
                        this.showError(data.message || 'Không thể tìm kiếm trạm sạc');
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    this.showError('Lỗi kết nối khi tìm kiếm');
                } finally {
                    this.loading = false;
                }
            }

            async getNearbyStations() {
                if (!this.userLocation) return;

                this.loading = true;
                this.showLoadingMessage('Đang tìm trạm sạc gần bạn...');

                try {
                    const url = `api_charging_station.php?action=nearby&lat=${this.userLocation.lat}&lng=${this.userLocation.lng}&limit=10`;
                    const response = await fetch(url);
                    const data = await response.json();

                    if (data.success) {
                        this.filteredStations = data.data;
                        this.displayStations();
                        this.updateStationCount();
                        if (data.data.length === 0) {
                            this.showMessage('Không có trạm sạc nào trong bán kính 50km');
                        }
                    } else {
                        this.showError(data.message || 'Không thể tìm trạm gần bạn');
                    }
                } catch (error) {
                    console.error('Error getting nearby stations:', error);
                    this.showError('Lỗi kết nối khi tìm trạm gần bạn');
                } finally {
                    this.loading = false;
                }
            }

            getCurrentLocation() {
                this.showLoadingMessage('Đang xác định vị trí của bạn...');

                if (!navigator.geolocation) {
                    this.showError('Trình duyệt không hỗ trợ định vị GPS');
                    return;
                }

                const options = {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 300000
                };

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                            accuracy: position.coords.accuracy
                        };

                        this.showNotification('Đã xác định vị trí thành công!', 'success');
                        this.getNearbyStations();
                    },
                    (error) => {
                        let errorMessage = 'Không thể xác định vị trí';

                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Bạn đã từ chối quyền truy cập vị trí. Vui lòng cấp quyền và thử lại.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Thông tin vị trí không khả dụng';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Timeout - Không thể xác định vị trí trong thời gian cho phép';
                                break;
                        }

                        this.showError(errorMessage);
                    },
                    options
                );
            }

            displayStations() {
                const container = document.getElementById('stationsList');
                if (!container) return;

                if (this.filteredStations.length === 0) {
                    container.innerHTML = '<div class="no-results">Không tìm thấy trạm sạc nào</div>';
                    this.updateScrollControls();
                    return;
                }

                container.innerHTML = this.filteredStations.map(station => this.createStationCard(station)).join('');

                // Ensure proper container sizing for scroll
                this.ensureProperScrolling(container);

                // Setup scroll indicators after displaying stations
                this.setupScrollIndicators();
                this.updateScrollControls();
            }

            ensureProperScrolling(container) {
                // Force recalculation of scroll height
                container.style.height = 'auto';
                requestAnimationFrame(() => {
                    const parentHeight = container.parentElement.clientHeight;
                    const headerHeight = 70; // Approximate header height
                    const maxHeight = parentHeight - headerHeight;
                    container.style.maxHeight = `${maxHeight}px`;
                    container.style.overflowY = 'auto';

                    const resultsHeader = container.parentElement.querySelector('.results-header');

                    // Add scroll class if content overflows
                    if (container.scrollHeight > container.clientHeight) {
                        container.parentElement.classList.add('has-scroll');
                        if (resultsHeader) {
                            resultsHeader.classList.add('has-scroll');
                        }
                    } else {
                        container.parentElement.classList.remove('has-scroll');
                        if (resultsHeader) {
                            resultsHeader.classList.remove('has-scroll');
                        }
                    }
                });
            }

            scrollResults(direction) {
                const container = document.getElementById('stationsList');
                if (!container) return;

                const scrollAmount = 150; // pixels to scroll
                const currentScroll = container.scrollTop;

                if (direction === 'up') {
                    container.scrollTo({
                        top: Math.max(0, currentScroll - scrollAmount),
                        behavior: 'smooth'
                    });
                } else if (direction === 'down') {
                    const maxScroll = container.scrollHeight - container.clientHeight;
                    container.scrollTo({
                        top: Math.min(maxScroll, currentScroll + scrollAmount),
                        behavior: 'smooth'
                    });
                }

                // Update scroll controls after scrolling
                setTimeout(() => {
                    this.updateScrollControls();
                }, 300);
            }

            scrollToPosition(position) {
                const container = document.getElementById('stationsList');
                if (!container) return;

                if (position === 'top') {
                    container.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                } else if (position === 'bottom') {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }

                // Update scroll controls after scrolling
                setTimeout(() => {
                    this.updateScrollControls();
                }, 500);
            }

            updateScrollControls() {
                const container = document.getElementById('stationsList');
                const scrollUpBtn = document.getElementById('scrollUpBtn');
                const scrollDownBtn = document.getElementById('scrollDownBtn');
                const scrollPosition = document.getElementById('scrollPosition');

                if (!container || !scrollUpBtn || !scrollDownBtn || !scrollPosition) return;

                const {
                    scrollTop,
                    scrollHeight,
                    clientHeight
                } = container;
                const maxScroll = scrollHeight - clientHeight;
                const isScrollable = scrollHeight > clientHeight;

                // Update button states
                scrollUpBtn.disabled = scrollTop <= 0;
                scrollDownBtn.disabled = scrollTop >= maxScroll - 1;

                // Update scroll position indicator
                if (isScrollable && this.filteredStations.length > 0) {
                    const currentPosition = Math.round((scrollTop / maxScroll) * 100);
                    scrollPosition.textContent = `${Math.min(currentPosition, 100)}%`;
                } else {
                    scrollPosition.textContent = '0%';
                }

                // Update scroll progress indicator
                if (isScrollable) {
                    const progress = Math.round((scrollTop / maxScroll) * 100);
                    container.style.setProperty('--scroll-progress', `${progress}%`);
                } else {
                    container.style.setProperty('--scroll-progress', '0%');
                }

                // Show/hide controls based on content
                const scrollControls = scrollUpBtn.closest('.scroll-controls');
                if (scrollControls) {
                    scrollControls.style.opacity = isScrollable ? '1' : '0.3';
                }
            }

            createStationCard(station) {
                const typeText = {
                    'dc-super': 'DC Siêu nhanh',
                    'dc-fast': 'DC Nhanh',
                    'ac-normal': 'AC Thường'
                } [station.station_type] || station.station_type;

                const statusClass = station.status === 'available' ? 'available' :
                    station.status === 'busy' ? 'busy' : 'unavailable';

                const statusText = {
                    'available': 'Sẵn sàng',
                    'busy': 'Đang bận',
                    'unavailable': 'Không khả dụng'
                } [station.status] || station.status;

                return `
                <div class="station-card" data-station-id="${station.station_id}">
                    <div class="station-header">
                        <h4 class="station-name">${station.station_name}</h4>
                        <span class="status-badge ${statusClass}">
                            ${statusText}
                        </span>
                    </div>
                    
                    <div class="station-address">
                        <i class="fas fa-map-marker-alt"></i> ${station.address}
                    </div>

                    <div class="station-info">
                        <div class="info-item">
                            <i class="fas fa-bolt"></i>
                            <span>${typeText}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>${station.power_capacity}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-plug"></i>
                            <span>${station.available_ports}/${station.total_ports}</span>
                        </div>
                        ${station.distance && station.distance > 0 ? 
                            `<div class="station-distance">${parseFloat(station.distance).toFixed(1)} km</div>` : ''}
                    </div>

                    <div class="station-actions">
                        <button onclick="chargingFinder.showStationDetails(${JSON.stringify(station).replace(/"/g, '&quot;')})" 
                                class="btn-finder btn-outline-primary btn-sm">
                            <i class="fas fa-info-circle"></i> Chi tiết
                        </button>
                        <a href="https://maps.google.com/?q=${station.latitude},${station.longitude}" 
                           target="_blank" class="btn-finder btn-primary btn-sm">
                            <i class="fas fa-directions"></i> Chỉ đường
                        </a>
                    </div>
                </div>
            `;
            }

            updateStationCount() {
                const countElement = document.getElementById('stationCount');
                if (countElement) {
                    countElement.textContent = `${this.filteredStations.length} trạm sạc`;
                }
            }

            showLoadingMessage(message) {
                const container = document.getElementById('stationsList');
                if (container) {
                    container.innerHTML = `
                    <div class="loading-message">
                        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 15px;"></i>
                        <p>${message}</p>
                    </div>
                `;
                }
            }

            showError(message) {
                const container = document.getElementById('stationsList');
                if (container) {
                    container.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 15px;"></i>
                        <p>${message}</p>
                    </div>
                `;
                }
            }

            showMessage(message) {
                const container = document.getElementById('stationsList');
                if (container) {
                    container.innerHTML = `
                    <div class="info-message">
                        <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 15px;"></i>
                        <p>${message}</p>
                    </div>
                `;
                }
            }

            showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                notification.textContent = message;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }

            showStationDetails(station) {
                const typeText = {
                    'dc-super': 'DC Siêu nhanh',
                    'dc-fast': 'DC Nhanh',
                    'ac-normal': 'AC Thường'
                } [station.station_type] || station.station_type;

                const statusText = {
                    'available': 'Sẵn sàng',
                    'busy': 'Đang bận',
                    'maintenance': 'Bảo trì',
                    'unavailable': 'Không khả dụng'
                } [station.status] || station.status;

                const modal = document.createElement('div');
                modal.className = 'modal';
                modal.innerHTML = `
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${station.station_name}</h5>
                                <button type="button" class="btn-close" onclick="this.closest('.modal').remove()"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-info-circle"></i> Thông tin cơ bản</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>Địa chỉ:</strong> ${station.address}</li>
                                            <li><strong>Loại sạc:</strong> ${typeText}</li>
                                            <li><strong>Công suất:</strong> ${station.power_capacity || 'Chưa xác định'}</li>
                                            <li><strong>Trạng thái:</strong> ${statusText}</li>
                                            <li><strong>Giờ hoạt động:</strong> ${station.operating_hours || '8:00-18:00'}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-plug"></i> Thông tin sạc</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>Tổng cổng:</strong> ${station.total_ports || 2}</li>
                                            <li><strong>Cổng khả dụng:</strong> ${station.available_ports || 2}</li>
                                            <li><strong>Giá sạc:</strong> ${station.pricing || '3,200 VND/kWh'}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="https://maps.google.com/?q=${station.latitude},${station.longitude}" 
                                   target="_blank" class="btn btn-primary">
                                    <i class="fas fa-directions"></i> Chỉ đường
                                </a>
                                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal').remove()">
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                // Add modal to body
                document.body.appendChild(modal);

                // Show modal with animation
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);

                // Close modal when clicking outside
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.closeModal(modal);
                    }
                });

                // Close modal with Escape key
                const handleEscape = (e) => {
                    if (e.key === 'Escape') {
                        this.closeModal(modal);
                        document.removeEventListener('keydown', handleEscape);
                    }
                };
                document.addEventListener('keydown', handleEscape);
            }

            closeModal(modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    if (modal.parentNode) {
                        modal.remove();
                    }
                }, 300);
            }

            setupScrollIndicators() {
                const container = document.getElementById('stationsList');
                const resultsPanel = container?.closest('.results-panel');

                if (!container || !resultsPanel) return;

                // Enhanced scroll handler with better performance
                const handleScroll = () => {
                    const {
                        scrollTop,
                        scrollHeight,
                        clientHeight
                    } = container;

                    // Check if content is scrollable with better detection
                    const isScrollable = scrollHeight > clientHeight + 10;

                    if (isScrollable) {
                        resultsPanel.classList.add('has-scroll');
                    } else {
                        resultsPanel.classList.remove('has-scroll');
                    }

                    // Check if scrolled from top (enhanced sensitivity)
                    if (scrollTop > 10) {
                        resultsPanel.classList.add('scroll-top');
                    } else {
                        resultsPanel.classList.remove('scroll-top');
                    }

                    // Check if scrolled from bottom (enhanced sensitivity)
                    if (scrollTop + clientHeight < scrollHeight - 10) {
                        resultsPanel.classList.add('scroll-bottom');
                    } else {
                        resultsPanel.classList.remove('scroll-bottom');
                    }

                    // Update scroll controls
                    this.updateScrollControls();
                };

                // Add smooth scrolling behavior with enhanced performance
                container.style.scrollBehavior = 'smooth';

                // Debounced scroll handler for better performance
                let scrollTimeout;
                const debouncedHandleScroll = () => {
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(handleScroll, 10);
                };

                // Add scroll event listener with passive for better performance
                container.addEventListener('scroll', debouncedHandleScroll, {
                    passive: true
                });

                // Enhanced resize observer to recalculate on container size change
                const resizeObserver = new ResizeObserver((entries) => {
                    for (let entry of entries) {
                        setTimeout(() => {
                            handleScroll();
                            this.updateScrollControls();
                        }, 100);
                    }
                });
                resizeObserver.observe(container);

                // Initial check for scroll indicators
                setTimeout(() => {
                    handleScroll();

                    // Auto-scroll to top when new content is loaded
                    if (container.scrollTop > 0) {
                        container.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                }, 100);

                // Enhanced keyboard navigation with smooth scrolling
                container.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowUp' || e.key === 'ArrowDown' || e.key === 'PageUp' || e.key === 'PageDown') {
                        e.preventDefault();

                        let scrollAmount;
                        switch (e.key) {
                            case 'ArrowUp':
                                scrollAmount = -120;
                                break;
                            case 'ArrowDown':
                                scrollAmount = 120;
                                break;
                            case 'PageUp':
                                scrollAmount = -container.clientHeight * 0.8;
                                break;
                            case 'PageDown':
                                scrollAmount = container.clientHeight * 0.8;
                                break;
                        }

                        const newScrollTop = Math.max(0, Math.min(
                            container.scrollHeight - container.clientHeight,
                            container.scrollTop + scrollAmount
                        ));

                        container.scrollTo({
                            top: newScrollTop,
                            behavior: 'smooth'
                        });

                        // Trigger scroll handler manually after keyboard navigation
                        setTimeout(handleScroll, 300);
                    }
                });

                // Make container focusable for keyboard navigation
                if (!container.hasAttribute('tabindex')) {
                    container.setAttribute('tabindex', '0');
                }

                // Enhanced wheel event for better scroll handling
                container.addEventListener('wheel', (e) => {
                    // Allow normal wheel scrolling but ensure our handler runs
                    setTimeout(handleScroll, 50);
                }, {
                    passive: true
                });

                // Add scroll-to-top functionality
                this.addScrollToTop(container);
            }

            addScrollToTop(container) {
                // Add scroll to top button when scrolled down significantly
                const scrollTopBtn = document.createElement('button');
                scrollTopBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
                scrollTopBtn.className = 'scroll-top-btn';
                scrollTopBtn.style.cssText = `
                    position: absolute;
                    bottom: 20px;
                    right: 20px;
                    background: #3282b8;
                    color: white;
                    border: none;
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    cursor: pointer;
                    z-index: 20;
                    opacity: 0;
                    transform: translateY(10px);
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 12px rgba(50, 130, 184, 0.3);
                    font-size: 0.9rem;
                `;

                const resultsPanel = container.closest('.results-panel');
                if (resultsPanel) {
                    resultsPanel.style.position = 'relative';
                    resultsPanel.appendChild(scrollTopBtn);
                }

                scrollTopBtn.addEventListener('click', () => {
                    container.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });

                // Show/hide scroll to top button
                container.addEventListener('scroll', () => {
                    if (container.scrollTop > 200) {
                        scrollTopBtn.style.opacity = '1';
                        scrollTopBtn.style.transform = 'translateY(0)';
                    } else {
                        scrollTopBtn.style.opacity = '0';
                        scrollTopBtn.style.transform = 'translateY(10px)';
                    }
                }, {
                    passive: true
                });
            }
        }

        // Initialize Charging Station Finder
        window.chargingFinder = new ChargingStationFinder();
    </script>
</body>

</html>
<?php ob_end_flush(); ?>