<?php
// Initialize the session
session_start();

include 'header.php';
include 'menu.php';
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
    <!-- Page content-->
    <div class="container">
        <h1 class="my-5"><i class="fa-sharp fa-solid fa-user-secret"></i> Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
        <?php
        /* Database credentials */
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        define('DB_NAME', 'crud');

        /* Attempt to connect to MySQL database */
        try{
            $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            // Set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            die("ERROR: Could not connect. " . $e->getMessage());
        }

        // Prepare the SQL statement to fetch all users
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll();

        if(isset($_POST['edit'])) {
            $userId = $_POST['edit'];
            // haal de gebruikersnaam op uit de database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
            $stmt->bindValue(':id', $userId);
            $stmt->execute();
            $user = $stmt->fetch();

            // toon het bewerkingsformulier met de huidige gebruikersnaam
            echo "<form method='post'>
            <input type='hidden' name='userId' value='".$user['id']."' />
            <div class='form-group'>
                <label for='username'>Username:</label>
                <input type='text' class='form-control' id='username' name='username' value='".$user['username']."' />
            </div>
            <br>
            <button type='submit'  name='update' class='btn btn-outline-success'>Save</button>
            <br><br>
        </form>";
        }
        // Output the users in an HTML table using Bootstrap classes
        echo "<table  class='table shadow-lg'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>";
        foreach ($users as $user) {
            echo "<tr>
        <td>" . $user['id'] . "</td>
        <td>" . $user['username'] . "</td>
        <td><form method='post'><input type='hidden' name='userId' value='".$user['id']."' /><button type='submit' name='edit' value='".$user['id']."' class='btn btn-outline-secondary'><i class='fa-solid fa-pen-to-square'></i></button></form></td>
        <td><form method='post'><button type='submit' name='delete' value='".$user['id']."' class='btn btn-outline-danger'><i class='fa-regular fa-square-minus'></i></button></form></td>
        </tr>";
        }
        echo "</tbody></table>";

        if(isset($_POST['delete'])) {
            $userId = $_POST['delete'];
            $stmt = $pdo->prepare("DELETE FROM users WHERE id=:id");
            $stmt->bindValue(':id', $userId);
            $stmt->execute();
            // redirect naar dezelfde pagina om de tabel te vernieuwen
            header('Location: '.$_SERVER['PHP_SELF']);
            exit();
        }

        if(isset($_POST['update'])) {
            $userId = $_POST['userId'];
            $username = $_POST['username'];

            // bijwerken van de gebruikersnaam in de database
            $stmt = $pdo->prepare("UPDATE users SET username=:username WHERE id=:id");
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':id', $userId);
            $stmt->execute();

            // redirect naar dezelfde pagina om de tabel te vernieuwen
            header('Location: '.$_SERVER['PHP_SELF']);
            exit();
        }
        ?>

    </div>
    <!-- Bootstrap core JS-->
<?php include 'footer.php' ?>