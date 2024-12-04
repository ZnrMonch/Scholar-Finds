<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "scholar_finds";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-thesis'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $abstract = $conn->real_escape_string($_POST['abstract']);
        
        $keywords = implode(', ', array_map('trim', explode(',', $_POST['keywords'])));
        $published_date = $conn->real_escape_string($_POST['published-date']);
        $course = $conn->real_escape_string($_POST['course']);
        
        $authors = array_filter($_POST, fn($key) => str_starts_with($key, 'author') && !empty($_POST[$key]), ARRAY_FILTER_USE_KEY);
        $authors_str = implode('-', array_values($authors));

        $query = "INSERT INTO theses (title, authors, course, published_date, keywords, abstract) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssssss", $title, $authors_str, $course, $published_date, $keywords, $abstract);

            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                echo "Execution error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Preparation error: " . $conn->error;
        }
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
    <!-- MAIN -->
    <main>
        <div id="cover"></div>
        <div class="bdiv">
            <form action="" method="get" class="search">
                <button class="inv" id="add" type="button" onclick="toggleTC()"><span class="material-symbols-outlined">note_add</span></button>
                <span>
                    <input type="search" name="q_thesis" id="q_thesis" placeholder="Search for a thesis" autocomplete="off">
                    <button type="submit"><span class="material-symbols-outlined">search</span></button>
                </span>
                <select name="sby" id="sby">
                    <option value="title">Title</option>
                    <option value="keyword">Keyword</option>
                    <option value="author">Author</option>
                    <option value="topic">Topic</option>
                    <option value="year">Year</option>
                </select>
            </form>
        </div>
        <!-- THESIS CREATOR -->
        <div class="thesis-creator hidden">
            <div class="barrier"></div>
            <div class="libcontainer" id="creator">
                <div id="tccontent">
                    <button class="inv hide-tc" onclick="toggleTC()"><span class="material-symbols-outlined">close</span></button>
                    <script>
                        function toggleTC() {
                        const tc = document.querySelector('.thesis-creator');
                        tc.classList.toggle('hidden');
                        }
                    </script>
                    <h1>Thesis Creator</h1>
                    <hr>
                    <!-- THESIS FORM -->
                    <form action="" method="post" id="thesis-form">
                        <div id="portion-one">
                            <label for="title">Thesis Title:</label>
                            <input type="text" name="title" id="title" placeholder="Enter the title of the thesis here" autocomplete="off" required maxlength="150">
                            <br>
                            <label for="abstract">Abstract:</label>
                            <textarea type="text" name="abstract" id="abstract" placeholder="Enter the abstract of the thesis here" autocomplete="off" required maxlength="1500"></textarea>
                            <br>
                        </div>
                        <div id="portion-two">
                            <div id="author-list">
                                <div class="author-group">
                                    <label for="author1">Author 1:</label>
                                    <input type="text" name="author1" id="author1" placeholder="Lastname F." autocomplete="off" required>
                                    <button type="button" class="inv add-author" onclick="addAuthor()">
                                        <span class="material-symbols-outlined">group_add</span>
                                    </button>
                                </div>
                                <button type="button" class="inv reset-authors" onclick="resetAuthors()"><span class="material-symbols-outlined">person_cancel</span></button>
                            </div>
                            <div id="other-inputs">
                                <span id="oi-one">
                                    <label for="keywords">Relevant Keywords:</label>
                                    <input type="text" name="keywords" id="keywords" placeholder="Enter the keywords of the thesis here" autocomplete="off" required>
                                    <p class="smalldesc">Separate multiple keywords with a comma ","</p>
                                </span>
                                <span id="oi-two">
                                    <label for="course">Course Program:</label>
                                    <select name="course" id="course">
                                        <option value="bsit">BSIT (Information and Network Security Track)</option>
                                        <option value="bscs">BSCS (App Development Track)</option>
                                    </select>
                                </span>
                                <span id="oi-three">
                                    <label for="published-date">Published Date:</label>
                                    <input type="month" name="published-date" id="published-date" autocomplete="off" required>
                                </span>
                            </div>
                        </div>
                        <input type="submit" name="add-thesis" value="Add Thesis" id="add-thesis">
                    </form>
                    <script>
                        let authorCount = 1;

                        function addAuthor() {
                            // ADD AUTHORS
                            authorCount++;

                            const newAuthorGroup = document.createElement('div');
                            newAuthorGroup.classList.add('author-group');

                            newAuthorGroup.innerHTML = `
                                <label for="author${authorCount}">Author ${authorCount}:</label>
                                <input type="text" name="author${authorCount}" id="author${authorCount}" placeholder="Lastname F." autocomplete="off">
                            `;
                            
                            document.getElementById('author-list').appendChild(newAuthorGroup);
                        }

                        function resetAuthors() {
                            // RESET AUTHORS
                            const authorList = document.getElementById('author-list');
                            authorList.innerHTML = '';

                            authorCount = 1;
                            const initialAuthorGroup = document.createElement('div');
                            initialAuthorGroup.classList.add('author-group');
                            initialAuthorGroup.innerHTML = `
                                <label for="author1">Author 1:</label>
                                <input type="text" name="author1" id="author1" placeholder="Lastname F." autocomplete="off">
                                <button type="button" class="inv add-author" onclick="addAuthor()">
                                    <span class="material-symbols-outlined">group_add</span>
                                </button>
                            `;
                            authorList.appendChild(initialAuthorGroup);

                            // RESET BUTTON
                            const resetButton = document.createElement('button');
                            resetButton.type = 'button';
                            resetButton.classList.add('inv', 'reset-authors');
                            resetButton.onclick = resetAuthors;
                            resetButton.innerHTML = `<span class="material-symbols-outlined">person_cancel</span>`;
                            authorList.appendChild(resetButton);
                        }
                    </script>
                </div>
            </div>
        </div>
        <!-- THESIS LIBRARY -->
        <div class="theses-library">
        <?php
            function generateAPA7Citation($authors, $published_date, $title, $keywords) {
                $author_list = explode('-', $authors);
                $formatted_authors = array_map(function ($author) {
                    $parts = explode(' ', trim($author));
                    $last_name = array_pop($parts);
                    $first_name = array_shift($parts);
                    $middle_initial = !empty($parts) ? strtoupper($parts[0][0]) . '.' : ''; // Get middle initial if exists
                    $formatted_name = $last_name . ', ' . $first_name;
                    if (!empty($middle_initial)) {
                        $formatted_name .= ' ' . $middle_initial;
                    }
                    return $formatted_name;
                }, $author_list);
                $formatted_authors = implode(', ', $formatted_authors);
                $year = (new DateTime($published_date))->format('Y');
                $keywords_list = implode(', ', array_map('trim', explode(',', $keywords)));
                return "{$formatted_authors} ({$year}). *{$title}*. (Unpublished undergraduate thesis). Keywords: {$keywords_list}.";
            }

            $result = $conn->query("SELECT * FROM theses");
            
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $published_date = new DateTime($row['published_date']);
                    $formatted_date = $published_date->format('F Y'); 
                    $apa_citation = generateAPA7Citation($row['authors'], $row['published_date'], $row['title'], $row['keywords']);
            ?>
            <div class="thesis-item" id="thesis-item-<?= $row['thesis_id']; ?>">
                <div class="colorbox <?php echo htmlspecialchars($row['course'])?>">
                    <button class="inv bm"><span class="material-symbols-outlined">bookmark</span></button>
                </div>
                <div class="metadata">
                    <h3><?= htmlspecialchars($row['title']); ?></h3>
                    <ul class="skeywords <?php echo htmlspecialchars($row['course']) ?>">
                        <?php
                        $keywords = explode(',', $row['keywords']);
                        foreach ($keywords as $keyword):
                        ?>
                            <li><?= htmlspecialchars(trim($keyword)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <ul class="sauthors">
                        <b>Authors:</b>
                        <?php
                        $authors = explode('-', $row['authors']);
                        foreach ($authors as $author):
                        ?>
                            <li><?= htmlspecialchars(trim($author)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <span class="bot">
                        <p class="pdate"><?= $formatted_date; ?></p>
                        <hr>
                        <p class="cprogram"><?= htmlspecialchars($row['course']) == 'bsit' ? "<b>BSIT</b> (Information and Network Security Track)" : "<b>BSCS</b> (Application Development Elective Track)"; ?></p>
                    </span>
                    <button class="inv open-item" onclick="toggleTItem(<?= $row['thesis_id']; ?>)">
                        <span class="material-symbols-outlined">zoom_out_map</span>
                    </button>
                </div>
                <div class="item-info hidden" id="item-info-<?= $row['thesis_id']; ?>">
                    <div class="barrier"></div>
                    <div class="libcontainer">
                        <button class="inv close-item" onclick="toggleTItem(<?= $row['thesis_id']; ?>)">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                        <div class="colorbox micb <?php echo htmlspecialchars($row['course']) ?> "></div>
                        <div class="complete-metadata">
                            <h1><?= htmlspecialchars($row['title']); ?></h1>
                            <h2><?= htmlspecialchars($row['course']) == 'bsit' ? "Bachelor of Science in Information Technology (Information and Network Security Track)" : "Bachelor of Science in Computer Science (Application Development Elective Track)"; ?></h2>
                            <ul class="keywords <?php echo htmlspecialchars($row['course']) ?>">
                                <?php
                                foreach ($keywords as $keyword):
                                ?>
                                    <li><?= htmlspecialchars(trim($keyword)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <ul class="authors">
                                <b>Authors:</b>
                                <?php
                                foreach ($authors as $author):
                                ?>
                                    <li><?= htmlspecialchars(trim($author)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <p class="pdate"><?= $formatted_date; ?></p>
                            <hr>
                            <div class="abstract">
                                <p><?= htmlspecialchars($row['abstract']); ?></p>
                            </div>
                            <div class="references">
                                <br>
                                <h3>References:</h3>
                                <div class="apa">
                                    <div id="ref-box">
                                        <?php echo $apa_citation ?>
                                    </div>
                                    <button class="inv copy-ref" onclick="copyReference('thesis-item-<?= $row['thesis_id']; ?>')">
                                        <span class="material-symbols-outlined">content_copy</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                endwhile;
            else:
                echo '<p>No theses available.</p>';
            endif;
            ?>
        </div>
        <script>
            function toggleTItem(thesisId) {
                const itemInfo = document.getElementById(`item-info-${thesisId}`);
                itemInfo.classList.toggle('hidden');
            }
        
            function copyReference(thesisId) {
                const refBox = document.getElementById('ref-box');
                const tempInput = document.createElement('input');
                tempInput.value = refBox.innerText;
        
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);
                document.execCommand('copy');
                document.body.removeChild(tempInput);
        
                alert("APA Reference copied to clipboard!");
            }
        </script>         
    </main>
</body>
</html>