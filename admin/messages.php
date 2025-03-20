<?php
// Start the session
session_start();

// Include the database connection file
include '../components/connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
   // Redirect to the admin login page if not logged in
   header('location:admin_login.php');
   exit(); // Stop further execution
}

// Handle message deletion
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete']; // Get the message ID to delete

   // Prepare and execute the delete query
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);

   // Redirect back to the messages page after deletion
   header('location:messages.php');
   exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>
   <!-- Font Awesome CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Custom Admin CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<!-- Include the admin header -->
<?php include '../components/admin_header.php'; ?>

<!-- Messages Section -->
<section class="contacts">
   <h1 class="heading">Messages</h1>
   <div class="box-container">
      <?php
      // Fetch all messages from the database
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();

      // Check if there are any messages
      if ($select_messages->rowCount() > 0) {
         // Loop through each message and display it
         while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <div class="box">
               <p>User ID: <span><?= $fetch_message['user_id']; ?></span></p>
               <p>Name: <span><?= $fetch_message['name']; ?></span></p>
               <p>Email: <span><?= $fetch_message['email']; ?></span></p>
               <p>Number: <span><?= $fetch_message['number']; ?></span></p>
               <p>Message: <span><?= $fetch_message['message']; ?></span></p>
               <!-- Delete button with confirmation -->
               <a href="messages.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('Are you sure you want to delete this message?');" class="delete-btn">Delete</a>
            </div>
      <?php
         }
      } else {
         // Display a message if no messages are found
         echo '<p class="empty">You have no messages.</p>';
      }
      ?>
   </div>
</section>

<!-- Include the admin script -->
<script src="../js/admin_script.js"></script>

</body>
</html>