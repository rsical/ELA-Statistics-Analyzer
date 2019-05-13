<?php
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: deny");
header("X-Content-Type-Options: nosniff");
?>