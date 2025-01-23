<?php
    include 'cryption.php';
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
                            <!-- UN > Username | UE > User Email -->
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
    <main id="home">
        <div class="hero">
            <h2 id="ccis">College of Computing and Information Sciences</h2>
            <h1 id="sf">Scholar Finds</h1>
        </div>
        <div class="description">
            <p id="overview">
                The Scholar Finds brings valuable improvements to the thesis process, benefiting students, faculty, and administrators alike. By promoting efficiency, security, organization, and user-centric design, it empowers academic institutions to manage thesis submissions more effectively while providing a smoother, more productive experience for all users.                </p>
        </div>
    </main>
</body>
</html>