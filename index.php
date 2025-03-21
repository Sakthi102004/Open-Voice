<?php
session_start();

// Redirect to dashboard if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Voice - Virtual Suggestion Box</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e3a8a, #6b7280, #9ca3af); /* Blue to Gray gradient */
            background-size: 200% 200%;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            animation: flowPulse 12s ease infinite;
        }

        @keyframes flowPulse {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; filter: brightness(110%); }
            100% { background-position: 0% 0%; }
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1), rgba(0, 0, 0, 0.3));
            animation: orbitFade 10s ease infinite;
            z-index: -1;
        }

        @keyframes orbitFade {
            0% { transform: rotate(0deg) scale(1); opacity: 0.8; }
            50% { transform: rotate(180deg) scale(1.05); opacity: 1; }
            100% { transform: rotate(360deg) scale(1); opacity: 0.8; }
        }

        /* Container with Compact Grid */
        .landing-container {
            max-width: 900px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: grid;
            grid-template-columns: 1fr 1fr; /* Balanced intro and features */
            gap: 40px;
            position: relative;
            z-index: 1;
            opacity: 0;
            transform: perspective(800px) rotateX(-10deg);
            animation: portalRise 1.2s ease-out forwards;
        }

        @keyframes portalRise {
            0% { opacity: 0; transform: perspective(800px) rotateX(-10deg) scale(0.9); }
            70% { opacity: 1; transform: perspective(800px) rotateX(5deg) scale(1.05); }
            100% { opacity: 1; transform: perspective(800px) rotateX(0deg) scale(1); }
        }

        /* Left Column: Intro */
        .intro {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Logo */
        .logo {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1e40af); /* Blue gradient */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.5);
            opacity: 0;
            animation: spinPop 1s ease forwards 0.2s;
        }

        @keyframes spinPop {
            0% { opacity: 0; transform: rotate(-180deg) scale(0.5); }
            60% { opacity: 1; transform: rotate(10deg) scale(1.1); }
            100% { opacity: 1; transform: rotate(0deg) scale(1); }
        }

        /* Welcome Message */
        h1 {
            font-size: 2rem;
            color: #1e3a8a; /* Deep blue */
            font-weight: 600;
            margin-bottom: 10px;
            text-shadow: 0 0 5px rgba(30, 58, 138, 0.3);
            opacity: 0;
            animation: bounceInText 0.8s ease forwards 0.4s;
        }

        p {
            font-size: 1rem;
            color: #4b5563; /* Dark gray */
            line-height: 1.5;
            margin-bottom: 20px;
            opacity: 0;
            animation: rippleUp 0.8s ease forwards 0.6s;
        }

        @keyframes bounceInText {
            0% { opacity: 0; transform: translateY(-30px) scale(0.8); }
            60% { opacity: 1; transform: translateY(5px) scale(1.1); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes rippleUp {
            0% { opacity: 0; transform: translateY(20px); }
            50% { opacity: 0.5; transform: translateY(-5px) scale(1.05); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Login Button */
        .login-button {
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #3b82f6, #1e40af); /* Blue gradient */
            border: none;
            border-radius: 25px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            animation: swingInBtn 1s ease forwards 0.8s;
        }

        .login-button:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.6);
        }

        .login-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .login-button:hover::before {
            left: 100%;
        }

        @keyframes swingInBtn {
            0% { opacity: 0; transform: translateX(-50px) rotate(-10deg); }
            60% { opacity: 1; transform: translateX(5px) rotate(5deg); }
            100% { opacity: 1; transform: translateX(0) rotate(0deg); }
        }

        /* Right Column: Features */
        .features {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px;
            background: rgba(59, 130, 246, 0.05); /* Light blue tint */
            border-radius: 10px;
        }

        .features h2 {
            font-size: 1.5rem;
            color: #1e40af; /* Deep blue */
            font-weight: 600;
            margin-bottom: 15px;
            text-shadow: 0 0 5px rgba(30, 64, 175, 0.3);
            opacity: 0;
            animation: glowIn 0.8s ease forwards 1s;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            font-size: 0.9rem;
            color: #4b5563; /* Dark gray */
        }

        .feature-list li {
            margin: 10px 0;
            padding: 10px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            animation: flipInItem 0.8s ease forwards;
            animation-delay: calc(0.15s * var(--i));
        }

        .feature-list li:nth-child(1) { --i: 1; }
        .feature-list li:nth-child(2) { --i: 2; }
        .feature-list li:nth-child(3) { --i: 3; }
        .feature-list li:nth-child(4) { --i: 4; }

        .feature-list li:hover {
            transform: scale(1.03) translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        @keyframes glowIn {
            0% { opacity: 0; transform: scale(0.9); }
            50% { opacity: 0.7; transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes flipInItem {
            0% { opacity: 0; transform: rotateY(-90deg); }
            100% { opacity: 1; transform: rotateY(0deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .landing-container {
                grid-template-columns: 1fr;
                max-width: 90%;
                padding: 30px;
            }
            .features { padding: 15px; }
            .logo { width: 80px; height: 80px; font-size: 1.6rem; }
            h1 { font-size: 1.8rem; }
            p { font-size: 0.9rem; }
            .login-button { padding: 10px 25px; font-size: 0.9rem; }
            .features h2 { font-size: 1.3rem; }
            .feature-list li { font-size: 0.85rem; }
        }

        @media (max-width: 480px) {
            .landing-container {
                padding: 20px;
            }
            .features { padding: 10px; }
            .logo { width: 60px; height: 60px; font-size: 1.2rem; }
            h1 { font-size: 1.5rem; }
            p { font-size: 0.8rem; }
            .login-button { padding: 8px 20px; font-size: 0.8rem; }
            .features h2 { font-size: 1.2rem; }
            .feature-list li { font-size: 0.75rem; }
        }
    </style>
</head>
<body>
    <div class="landing-container">
        <!-- Left Column: Intro -->
        <div class="intro">
            <div class="logo"><img src="logo.png" alt=" "></div> <!-- Replace with <img src="logo.png" class="logo" alt="Open Voice Logo"> -->
            <h1>Open Voice</h1>
            <p>A secure platform for anonymous feedback and organizational growth.</p>
            <button class="login-button" onclick="window.location.href='login.php'">Let's Open Voice</button>
        </div>

        <!-- Right Column: Features -->
        <div class="features">
            <h2>Key Features</h2>
            <ul class="feature-list">
                <li>Anonymous Submissions</li>
                <li>Suggestion Tracking</li>
                <li>Feedback Management</li>
                <li>Admin Analytics</li>
            </ul>
        </div>
    </div>
</body>
</html>
