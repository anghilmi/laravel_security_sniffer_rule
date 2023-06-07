## laravel_security_sniffer_rule
Code security sniffer for Laravel based web project (Windows OS only)

## Rules
1. Detect weak hash (ex. md5, sha1)
2. Detect input validation
3. Detect upload file/image code
4. Detect unescaped syntax {!! ... !!}
5. Detect unencrypted ID (url)
6. Detect logic in blade file
7. Detect readonly logic in blade file
8. Detect raw SQL query

## VSCode Extension
Visit on [marketplace](https://marketplace.visualstudio.com/items?itemName=MuhAnisAlHilmi.laravel-php-codesniffer)

## Rule Installation/Update
1. You must have Composer. If not, please download here (https://getcomposer.org/Composer-Setup.exe) and install
2. Open CMD/terminal, copy-paste this command:

```
(IF EXIST "%userprofile%\AppData\Roaming\Composer\vendor\squizlabs\php_codesniffer\src\Standards\laravel_security_sniffer" (RMDIR /S /Q "%userprofile%\AppData\Roaming\Composer\vendor\squizlabs\php_codesniffer\src\Standards\laravel_security_sniffer") ELSE (echo "folder tidak ditemukan, but it's OK")) && composer global require "squizlabs/php_codesniffer=*" && cd %userprofile%\Downloads && curl -L "https://github.com/anghilmi/laravel_security_sniffer_rule/archive/main.tar.gz" | tar -xzf - && MOVE /Y %userprofile%\Downloads\laravel_security_sniffer_rule-main "%userprofile%\AppData\Roaming\Composer\vendor\squizlabs\php_codesniffer\src\Standards\laravel_security_sniffer"
```

## Todo
1. Perbaikan deteksi validasi input
2. Rename nama rule yang lebih baik

## Related Project
[Laravuln](https://github.com/GustiAdithiya/laravuln): Website Perpustakaan Berbasis Laravel 9 yang sengaja dibuat rentan/vulnerable. Dipakai untuk edukasi, simulasi beragam jenis serangan dan antisipasinya.

