<?php
session_start();
session_destroy();
echo "<script type='text/javascript'>alert('logged out');</script>";
header('Location: ../../index.php');
?>
