<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Username - Dynabio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ef9baa832e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css?v=2.0">
    <link rel="stylesheet" href="../../css/views/auth/set_username.css?v=1.0">
</head>

<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Choose Username</h1>
            <p>Customize how you appear on Dynabio</p>
        </div>
        <form id="usernameForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control" placeholder="Enter you username here..." required
                    autocomplete="off">
            </div>
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span id="btnText">Save Username</span>
            </button>
            <button type="button" id="skipBtn" class="btn skip-btn">
                Skip for now
            </button>
        </form>
    </div>
    <script src="../js/toast.js"></script>
    <script src="../../js/views/auth/set_username.js"></script>
    <script src="../js/background_animation.js"></script>
</body>

</html>