<?php
// Start PHP session to retain user data
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['join_now'])) {
        // Store a session variable
        $_SESSION['welcome_message'] = 'Welcome to Overtime!';

        // Redirect to the same page to show the welcome message
        header('Location: ' . htmlspecialchars($_SERVER["PHP_SELF"]));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Landing Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="landing-page">
        <!-- Other content of your landing page -->

        <!-- The main welcome message -->
        <div class="welcome-to-overtime">WELCOME TO OVERTIME</div>
        
        <!-- The 'Join Now' button -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="rectangle-82">
                <button type="submit" name="join_now" class="join-now">Join Now</button>
            </div>
        </form>

        <!-- Script to show an alert if the session variable is set -->
        <?php if (isset($_SESSION['welcome_message'])): ?>
            <script>alert('<?php echo $_SESSION['welcome_message']; ?>');</script>
            <?php unset($_SESSION['welcome_message']); // Clear the message after displaying it ?>
        <?php endif; ?>
    </div>
</body>
</html>