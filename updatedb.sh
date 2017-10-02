#!/usr/bin/env bash
clear
php vendor/bin/doctrine orm:clear-cache:metadata
php vendor/bin/doctrine orm:clear-cache:query
php vendor/bin/doctrine orm:clear-cache:result
php vendor/bin/doctrine orm:schema-tool:update --dump-sql

