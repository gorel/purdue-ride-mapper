<?php
session_start();
session_destroy();
echo "<script type='text/javascript'>alert('logged out'); header("Location:../../index.php");</script>";
?>
