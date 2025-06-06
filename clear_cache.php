<?php
echo "Limpando caches...<br>";

echo shell_exec('php artisan cache:clear 2>&1') . "<br>";
echo shell_exec('php artisan config:clear 2>&1') . "<br>";
echo shell_exec('php artisan route:clear 2>&1') . "<br>";
echo shell_exec('php artisan view:clear 2>&1') . "<br>";

echo "Cache limpo.";

