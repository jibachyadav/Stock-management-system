<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    $login_path = (defined('IS_ROOT') && IS_ROOT) ? 'pages/login.php' : '../pages/login.php';
    header("Location: " . $login_path);
    exit();
}

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>
<script>
// Prevent caching and "flash of content" on back button
window.addEventListener( "pageshow", function ( event ) {
  var historyTraversal = event.persisted || 
                         ( typeof window.performance != "undefined" && 
                              window.performance.navigation.type === 2 );
  if ( historyTraversal ) {
    // Hide everything immediately
    document.body.style.display = 'none';
    // Force reload
    window.location.reload();
  }
});
</script>
