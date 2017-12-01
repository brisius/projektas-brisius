<?php
session_start();
session_unset();
session_destroy();
include ('templates/pagrindinis_meniu.html');
?>