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
<body class="about">
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
    <main id="aboutus">
        <div class="ScholarFinds">
            <h1>About the Website</h1>
            <p> The Scholar Finds is a web-based platform Theses Management and Sorting System<br> 
                designed to streamline the process of storing, categorizing, and accessing 4th-year CCIS college students' theses. It addresses the inefficiencies of<br>
                traditional library systems by offering a centralized and organized repository, making academic resources easily accessible to<br>
                CCIS college students, faculty, and administrators.</p>
        </div>
        <div class="college">
            <img src="resources/ccis-logo.png" alt="ccis-logo" class="logos">
            <span>
                <h1>About the College of Computing and Information Sciences</h1>
                <p>
                    The College provides access to new trends and ideas in information systems/technology <br>
                    to keep pace with the innovations and meet the demands of a fast-changing business <br>
                    and technology environment. <br>
                    It aims to develop globally competitive IT graduates who are God-fearing <br>
                    and morally upright from among the marginalized population of the City of Makati.
                </p> 
            </span>
              
        </div>
        <div class="VisionMision">
            <span class="background">
                <img src="resources/Umak_drone.jpg" alt="umak2.0" class="umak2">
                <img src="resources/Umak_drone.jpg" alt="coloralt" class="color">
            </span>
            <div class="vam">
                <span class="vm">
                    <h1>Vision</h1>
                    <p> The College envisions an Information <br>
                    Technology Institution committed to <br>
                    the development and adequate <br>
                    utilization and applications of <br>
                    Information Technology.</p>
                </span>
                <span class="vm">
                    <h1>Mission</h1>
                    <p> Guided by its vision of commitment,<br> 
                    the College shall provide a<br>
                    competitive, relevant and functional IT<br>
                    Curriculum responsive to the needs of<br>
                    the industrial and business<br>
                    organizations of the City of Makati<br>
                    and entire nation.</p>
                </span>
            </div>
        </div>
        
        <div class="devsec">
            <div class="topdevsec">
                <h1>WEB DEVELOPERS</h1>
            </div>
            <div class="upper-dev">
                <div class="dev-container">
                    <div class="green-rectangle"><img src="resources/Renz.png" alt="renz" class="dev" draggable="false"></div>
                    <h2>Renzjan S. Moncinilla</h2>
                    <p class="position">Project Manager and Full-stack Developer</p>
                </div> 
            </div>
            <div class="lower-dev">
                <div class="dev-container">
                    <div class="green-rectangle"><img src="resources/Dhanica.png" alt="renz" class="dev" draggable="false"></div>
                    <h2>Ma. Dhanica S. Ballesteros</h2>
                    <p class="position">UI/UX Designer</p>
                </div>
                <div class="dev-container">
                    <div class="green-rectangle"><img src="resources/Andrei.png" alt="renz" class="dev" draggable="false"></div>
                    <h2>Paul Andrei D. Valencia</h2>
                    <p class="position">Full-stack Developer</p>
                </div>
                <div class="dev-container">
                    <div class="green-rectangle"><img src="resources/Xia.png" alt="renz" class="dev" draggable="false"></div>
                    <h2>Lilxianaze C. Garcia</h2>
                    <p class="position">UI/UX Designer</p>
                </div>
            </div>
        </div>
        <script>
            const binaryContainer = document.body;
            const screenHeight = window.innerHeight;

            function createBinary() {
            for (let i = 0; i < 10; i++) {
                const binaryElement = document.createElement("div");
                binaryElement.classList.add("binary");
                binaryElement.innerText = Math.random() > 0.5 ? "1" : "0";

                binaryElement.style.left = `${Math.random() * 100}vw`;
                binaryElement.style.top = `${Math.random() * screenHeight}px`;
                binaryElement.style.animationDuration = `${Math.random() * 3 + 3}s`;

                binaryContainer.appendChild(binaryElement);

                setTimeout(() => binaryElement.remove(), 5000);
            }
            }

            setInterval(createBinary, 3000);
        </script>
    </main>
</body>
</html>