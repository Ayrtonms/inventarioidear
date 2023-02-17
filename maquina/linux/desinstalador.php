<?php
    if (file_exists('/etc/cron.d/inventarioidear')) {
        unlink('/etc/cron.d/inventarioidear');
    }
?>