#!/usr/bin/env bash
# Idempotent repository bootstrap for the db-to-php Cloud Agent environment.
#
# The project targets PHP 7.x (fzaninotto/faker v1.9.1 in composer.lock does not
# support PHP 8), so we pin PHP 7.4 which satisfies every locked dependency.
# MySQL-only library => a local MariaDB server is provisioned for integration tests.
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$REPO_ROOT"

export DEBIAN_FRONTEND=noninteractive

# --- System packages: PHP 7.4 + extensions and MariaDB (durable, cached in build) ---
if ! command -v php7.4 >/dev/null 2>&1; then
  sudo apt-get update -y
  sudo apt-get install -y software-properties-common
  sudo add-apt-repository -y ppa:ondrej/php
  sudo apt-get update -y
  sudo apt-get install -y \
    php7.4-cli php7.4-mysql php7.4-mbstring php7.4-xml php7.4-curl php7.4-bcmath \
    mariadb-server mariadb-client unzip
fi

# Make PHP 7.4 the default `php` interpreter.
sudo update-alternatives --set php /usr/bin/php7.4

# --- Composer (standalone phar; the apt package drags in PHP 8 Symfony libs) ---
if ! command -v composer >/dev/null 2>&1; then
  EXPECTED_CHECKSUM="$(php -r "copy('https://composer.github.io/installer.sig', 'php://stdout');")"
  php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
  ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"
  if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
    echo "ERROR: Invalid composer installer checksum" >&2
    rm -f /tmp/composer-setup.php
    exit 1
  fi
  sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm -f /tmp/composer-setup.php
fi

# --- MariaDB: resolve 127.0.0.1 clients as 127.0.0.1 (not localhost/unix_socket) ---
printf '[mysqld]\nskip-name-resolve\n' | sudo tee /etc/mysql/mariadb.conf.d/99-cursor-dev.cnf >/dev/null

# Start MariaDB just long enough to create the TCP root user. The account and data
# directory persist into the environment build; the running process does not.
sudo service mariadb start
for _ in $(seq 1 30); do sudo mysqladmin ping >/dev/null 2>&1 && break; sleep 1; done
sudo mysql -e "CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY 'Test123@';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
FLUSH PRIVILEGES;"

# --- PHP dependencies (honours the committed composer.lock) ---
composer install --no-interaction --no-progress

# --- Test environment file (tests/.env is gitignored) ---
if [ ! -f tests/.env ]; then
  cat > tests/.env <<'EOF'
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_db_to_php
DB_USERNAME=root
DB_PASSWORD=Test123@
EOF
fi

echo "install.sh completed successfully"
