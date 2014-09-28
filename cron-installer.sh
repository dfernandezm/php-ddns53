#!/bin/sh

# needs to be run as sudo
COMMAND=$1
CRON_PATTERN=$2

chmod +x $COMMAND

# write out current crontab
crontab -l > acron

# echo new cron into cron file
echo "$CRON_PATTERN $COMMAND" >> acron

# install new cron file
crontab acron
rm acron