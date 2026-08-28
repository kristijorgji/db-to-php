#!/usr/bin/env bash
# Per-boot startup: bring up the local MariaDB server used by integration tests.
set -euo pipefail

sudo service mariadb start

# Wait until the server accepts connections before returning.
for _ in $(seq 1 30); do
  if sudo mysqladmin ping >/dev/null 2>&1; then
    echo "MariaDB is ready"
    exit 0
  fi
  sleep 1
done

echo "ERROR: MariaDB did not become ready in time" >&2
exit 1
