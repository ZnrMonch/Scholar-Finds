<?php
    include 'cryption.php';
    $conn = new mysqli('localhost', 'root', '', 'scholar_finds');
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Registration
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
        $email = trim($_POST['remail']);
        $username = trim($_POST['rusername']);
        $password = trim($_POST['rpassword']);

        // Validation for empty fields
        if (empty($email) || empty($username) || empty($password)) {
            notify("Error: One or more fields are empty.");
        } elseif (!str_ends_with($email, "@umak.edu.ph")) { // Validate email domain
            notify("Error: Invalid UMak email account.");
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,30}$/', $password)) { // Validate password
            notify("Error: Invalid password pattern.");
        } else {
            $stmt = $conn->prepare("SELECT email FROM user_info WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) { // Email exists
                notify("Error: Email already exists.");
            } else { // Register new user
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO user_info (email, username, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $email, $username, $password); // Store plain text password

                if ($stmt->execute()) {
                    notify("Success: Registration successful!");
                    setcookie("current_user", encrypt($email), time() + (86400 * 0.5), "/");
                } else {
                    notify("Error: Executing query failed.");
                }
            }
            $stmt->close();
        }
    }

    // Login
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $email = trim($_POST['lemail']);
        $password = trim($_POST['lpassword']);

        // Validation for empty fields
        if (empty($email) || empty($password)) {
            notify("Error: One or more fields are empty.");
        } else {
            $stmt = $conn->prepare("SELECT email, password FROM user_info WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) { // Email exists
                $stmt->bind_result($stored_email, $stored_password);
                $stmt->fetch();

                if ($password === $stored_password) { // Compare plain text passwords
                    notify("Success: Login successful!");
                    setcookie("current_user", encrypt($email), time() + (3 * 3600), "/"); // Store encrypted email in cookie for 3 hours
                    header("Location: library.php");
                    exit();
                } else {
                    notify("Error: Invalid password.");
                }
            } else { // Email does not exist
                notify("Error: No existing email.");
            }
            $stmt->close();
        }
    }

    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholar Finds | CCIS</title>
    <link rel="shortcut icon" href="resources/ccis-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body class="accessb">
    <header>
        <div id="logos">
            <img src="resources/umak-logo.png" alt="umak-logo" class="logo">
            <img src="resources/ccis-logo.png" alt="ccis-logo" class="logo">
            <h1>Scholar Finds</h1>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <a href="library.php">Library</a>
            <div id="profile">
                <button id="menu-button" class="inv" onclick="toggleMenu()"><img src="resources/user.png" alt="profile-picture" class="profile-picture"></button>
                <div class="hidden" id="menu">
                    <button class="inv close" onclick="toggleMenu()"><span class="material-symbols-outlined">close</span></button>
                    <div id="user-info">
                        <img src="resources/user.png" alt="profile-picture" class="profile-picture">
                        <p>
                        <span id="un"><?php echo isset($_COOKIE['current_user']) ? str_replace("@umak.edu.ph", "", decrypt($_COOKIE['current_user'])) : "Not Signed In";?></span>
                            <!-- <span id="ue">Guest</span> -->
                        </p>
                    </div>
                    <hr>
                    <div id="menu-selections">
                        <button class="menu-item">
                            <span class="material-symbols-outlined">person</span>
                            <p>Profile</p>
                        </button>
                        <button class="menu-item">
                            <span class="material-symbols-outlined">bookmarks</span>
                            <p>Bookmarks</p>
                        </button>
                        <button class="menu-item">
                            <span class="material-symbols-outlined">dark_mode</span>
                            <p>Dark Mode</p>
                        </button>
                    </div>
                    <hr>
                    <div id="log">
                    <?php 
                            if (isset($_COOKIE["current_user"])) {
                                echo "
                                <form method='post' action=''>
                                    <button class='inv out' name='logout'>
                                        <span class='material-symbols-outlined'>logout</span>
                                        <p>Log Out</p>
                                    </button>
                                </form>";
                                if (isset($_POST['logout'])) {
                                    setcookie("current_user", "", time() - 3600, "/");
                                    header("Location: index.php");
                                    exit();
                                }
                            } else {
                                echo "
                                <a href='access.php'>
                                    <button class='inv in'>
                                        <span class='material-symbols-outlined'>login</span>
                                        <p>Log In</p>
                                    </button>
                                </a>";
                            }
                        ?>
                    </div>
                </div>
                <script>
                    const menuBody = document.getElementById("menu");
                    function toggleMenu() {
                        menuBody.classList.toggle("hidden");
                    }                    
                </script>
            </div>
        </nav>
    </header>
    <main id="access">
        <?php
            function notify($message) {
                if (str_contains($message, "Error")) {
                    $class = "error";
                } elseif (str_contains($message, "Success")) {
                    $class = "success";
                }
                echo "<div class='nmsg $class'>$message</div>";
            }
        ?>
        <div id="user-log" class="container">
            <div id="user-log-cover" class="<?php echo isset($_COOKIE['logCoverClass']) ? $_COOKIE['logCoverClass'] : ''; ?>"><span>University of Makati</span></div>
            <script>
                function slideLogCover() {
                    const logCover = document.getElementById('user-log-cover');
                    if (logCover.classList.contains("log") || logCover.classList.contains("reg")) {
                        logCover.classList.toggle("log");
                        logCover.classList.toggle("reg");
                    } else {
                        logCover.classList.add("reg");
                    }
                    document.cookie = "logCoverClass=" + logCover.className + "; path=/";
                }
            </script>
            <div id="new-user" class="logform">
                <h1>REGISTRATION</h1>
                <hr>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" id="regis">
                    <label for="remail">UMak Email Address:</label>
                    <input type="email" name="remail" id="remail" autocomplete="off" placeholder="Enter your UMak email address" required>
                    <p class="fdesc feg">e.g. juandela.cruz@umak.edu.ph</p>
                    <br>
                    <label for="rusername">Username:</label>
                    <input type="text" name="rusername" id="rusername" autocomplete="off" placeholder="Enter your username" minlength="4" required>
                    <br>
                    <label for="rpassword">Password:</label>
                    <input type="password" name="rpassword" id="rpassword" autocomplete="off" placeholder="Enter your password" minlength="6" required>
                    <input type="submit" name="register" value="Register">
                </form>
                <p class="fdesc feg cmode">Already have an account? <a href="#" onclick="slideLogCover()">Login</a></p>
            </div>
            <hr>
            <div id="old-user" class="logform">
                <h1>LOGIN</h1>
                <hr>
                <form action="" method="post" id="login">
                    <label for="lemail">UMak Email Address:</label>
                    <input type="email" name="lemail" id="lemail" autocomplete="off" placeholder="Enter your UMak email address" required>
                    <br>
                    <label for="rpassword">Password:</label>
                    <input type="password" name="lpassword" id="lpassword" autocomplete="off" placeholder="Enter your password" required>
                    <input type="submit" name="login" value="Login">
                </form>
                <p class="fdesc feg cmode">Don't have an account? <a href="#" onclick="slideLogCover()">Register</a></p>
            </div>
        </div>
    </main>
</body>
</html>