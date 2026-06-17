<?php
session_start();
session_destroy();
header("Location: /studio_foto_project/login.php?logout=1");
exit();
?>
