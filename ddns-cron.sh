#!/bin/sh

# full path tho the ddns-change.php script
FULL_SCRIPT_PATH=/path/to/script

# full path to the log file
FULL_LOG_PATH=/path/to/logs

php $FULL_SCRIPT_PATH/ddns-change.php >> $FULL_LOG_PATH/ddns.log
