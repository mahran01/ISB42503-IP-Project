<?php 
$page_title="Index";
session_start();
?>

<?php include('../src/includes/header.html');?>
<h1>Index</h1>
<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Debitis et necessitatibus voluptatibus dolore officia nisi illum unde officiis vero. Nemo hic, perferendis sapiente facere corporis saepe libero vitae veniam ea!</p>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos illum, consequatur quas sint nulla nihil dolore aliquam et expedita delectus, in, unde vero omnis itaque cumque. Provident repudiandae perferendis voluptate.</p>
<?php include('../src/includes/footer.html');?>

<?php
//If redirected to here will either mean this link is clicked or form is submitted
if (isset($_POST['url'])) $_SESSION['url'] = $_POST['url'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
<script>
$(document).ready(function() {
    redirectPost("<?php echo $_SESSION['url']; ?>", retainPost = true);
});
</script>
<?php
  // Handle the form submission
  // Perform necessary processing or database operations

  // Redirect back to the same page after form submission

  exit();
endif ?>