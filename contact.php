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
<body class="contactb">
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
    <div class="contact">
        <div class="content">
            <h1>Contact Us</h1>
        </div>
        </div>
        <div class="contacts">
            <div class="contact-item">
                <img src="resources/ccis-logo.png" alt="office-contact" class="contact-picture">
                <div class="contact-info">
                    <h2>COLLEGE OF COMPUTING INFORMATION SCIENCES</h2><br>
                    <span class="info">
                        <span class="material-symbols-outlined">map</span>
                        <p>3rd floor, Administrative Building, University of Makati, JP Rizal Ext., West Rembo, Makati City</p>
                    </span>
                    <span class="info">
                        <span class="material-symbols-outlined">mail</span>
                        <p>ccis@umak.edu.ph</p>
                    </span>
                </div>
            </div>
            <div class="contact-item">
                <img src="resources/dev-contact.png" alt="developer-contact" class="contact-picture">
                <div class="contact-info">
                    <h2>RENZJAN MONCINILLA (Administrator)</h2><br>
                    <span class="info">
                        <span class="material-symbols-outlined">public</span>
                        <p>fb.com/renzjan.moncinilla</p>
                    </span>
                    <span class="info">
                        <span class="material-symbols-outlined">mail</span>
                        <p>renzjan.moncinilla@umak.edu.ph</p>
                    </span>
                </div>
            </div>
        </div>
        </div>x
</body>
</html>