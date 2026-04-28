<?php require "config/database.php"; $db = getConnection(); var_dump($db->query("SHOW CREATE TABLE tin_nhan")->fetch_row()[1]); 
