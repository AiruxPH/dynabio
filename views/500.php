<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Internal Error - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #050505;
            color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image:
                radial-gradient(circle at 15% 50%, rgba(255, 255, 255, 0.05), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(255, 255, 255, 0.03), transparent 25%);
        }

        .error-container {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            position: relative;
            overflow: hidden;
            margin: 2rem;
        }

        .error-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ef4444, transparent);
        }

        .error-icon {
            font-size: 4rem;
            color: #ef4444;
            margin-bottom: 1.5rem;
            text-shadow: 0 0 20px rgba(239, 68, 68, 0.4);
        }

        h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        p {
            color: #94a3b8;
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s ease;
        }

        .btn-home:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="error-container">
        <i class="fas fa-exclamation-triangle error-icon"></i>
        <h1>Internal Server Error</h1>
        <p>We encountered an unexpected processing issue while loading this page. Our systems have logged the fault.
            Please try returning to the dashboard.</p>

        <?php
        // Try to figure out relation to root dynamically so the button always points to the true index
        $rootDepth = substr_count($_SERVER['REQUEST_URI'], '/') - 1;
        $rootPath = $rootDepth > 0 ? str_repeat('../', $rootDepth) . 'index.php' : '/index.php';
        ?>
        <a href="<?php echo $rootPath; ?>" class="btn-home">
            <i class="fas fa-arrow-left"></i> Return Home
        </a>
    </div>
</body>

</html>