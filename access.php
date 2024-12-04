<?php
    $conn = new mysqli('localhost', 'root', '', 'scholar_finds');
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    } else {
        logmsg("Database connection successful!");
    }

    // regigas
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
        $email = trim($_POST['remail']);
        $username = trim($_POST['rusername']);
        $password = trim($_POST['rpassword']);

        // validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            logmsg("Invalid email format.");
        } elseif (empty($email) || empty($username) || empty($password)) {
            logmsg("Please fill in all fields.");
        } else {
            logmsg("Email: $email, Username: $username, Password: $password");

            $stmt = $conn->prepare("INSERT INTO user_info (email, username, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $username, $password); 

            if ($stmt->execute()) {
                logmsg("Registration successful!");
            } else {
                logmsg("Error executing query: " . $stmt->error);
            }

            $stmt->close();
        }
    }

    // login
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $email = trim($_POST['lemail']);
        $password = trim($_POST['lpassword']);

        $stmt = $conn->prepare("SELECT password FROM user_info WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($stored_password);
            $stmt->fetch();

            if ($password === $stored_password) {
                logmsg("Login successful.");
                $_SESSION['email'] = $email; 
                header("Location: library.php"); 
                exit();
            } else {
                logmsg("Invalid password.");
            }
        } else {
            logmsg("Email not found.");
        }

        $stmt->close();
    }

    $conn->close();

    function logmsg($message) {
    echo "<script>console.log('" . addslashes($message) . "');</script>";
    }
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
<body>
    <header>
        <div id="logos">
            <img src="resources/umak-logo.png" alt="umak-logo" class="logo">
            <img src="resources/ccis-logo.png" alt="ccis-logo" class="logo">
            <h1>Scholar Finds</h1>
        </div>
        <nav>
            <a href="home.html">Home</a>
            <a href="about.html">About</a>
            <a href="contact.html">Contact</a>
            <a href="library.php">Library</a>
            <div id="profile">
                <button id="menu-button" class="inv" onclick="toggleMenu()"><img src="resources/user.png" alt="profile-picture" class="profile-picture"></button>
                <div class="hidden" id="menu">
                    <button class="inv close" onclick="toggleMenu()"><span class="material-symbols-outlined">close</span></button>
                    <div id="user-info">
                        <img src="resources/user.png" alt="profile-picture" class="profile-picture">
                        <p>
                            <!-- UN > Username | UE > User Email -->
                            <span id="un">Not Signed In</span>
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
                        <a href="access.php">
                            <button class="inv in">
                                <span class="material-symbols-outlined">login</span>
                                <p>Log In</p>
                            </button>
                        </a>
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
        <div id="user-log" class="container">
            <div id="user-log-cover" class=""><span>University of Makati</span></div>
            <script>
                function slideLogCover() {
                    const logCover = document.getElementById('user-log-cover');
                    if (logCover.classList.contains("log") || logCover.classList.contains("reg")) {
                        logCover.classList.toggle("log");
                        logCover.classList.toggle("reg");
                    } else {
                        logCover.classList.add("reg");
                    }
                }
            </script>
            <div id="new-user" class="logform">
                <h1>REGISTRATION</h1>
                <hr>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" id="regis">
                    <label for="remail">UMak Email Address:</label>
                    <input type="email" name="remail" id="remail" autocomplete="off" placeholder="Enter your UMak email address">
                    <p class="fdesc feg">e.g. juandela.cruz@umak.edu.ph</p>
                    <br>
                    <label for="rusername">Username:</label>
                    <input type="text" name="rusername" id="rusername" autocomplete="off" placeholder="Enter your username">
                    <br>
                    <label for="rpassword">Password:</label>
                    <input type="password" name="rpassword" id="rpassword" autocomplete="off" placeholder="Enter your password">
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
                    <input type="email" name="lemail" id="lemail" autocomplete="off" placeholder="Enter your UMak email address">
                    <br>
                    <label for="rpassword">Password:</label>
                    <input type="password" name="lpassword" id="lpassword" autocomplete="off" placeholder="Enter your password">
                    <input type="submit" name="login" value="Login">
                </form>
                <p class="fdesc feg cmode">Don't have an account? <a href="#" onclick="slideLogCover()">Register</a></p>
            </div>
        </div>
    </main>
</body>
</html>