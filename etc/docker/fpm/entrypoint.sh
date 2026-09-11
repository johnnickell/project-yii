#!/usr/bin/env sh
set -eu

error_fifo=/tmp/project-yii-fpm-error.log
if [ -e "$error_fifo" ]; then
    rm "$error_fifo"
fi
mkfifo "$error_fifo"
cat "$error_fifo" &

exec php-fpm --nodaemonize
