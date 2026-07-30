<?php
$src = 'C:\Users\ASUS\.gemini\antigravity-ide\brain\5029ac20-c989-400f-9893-7dc37afb58b5\media__1785381061136.png';
$dst = __DIR__ . '/../public/images/logo.png';
if (file_exists($src)) {
    copy($src, $dst);
    echo "Logo successfully updated!";
} else {
    echo "Source file not found.";
}
