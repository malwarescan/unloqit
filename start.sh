#!/bin/bash
# Railway start script - expands PORT variable for PHP server

PORT=${PORT:-8080}
php -d variables_order=EGPCS -S 0.0.0.0:$PORT -t public

