#!/bin/sh

directory="/usr/local/emhttp/plugins/wxwork-message"
echo "Set permissions and move into dir $directory"
chmod a+r "$directory"
cd "$directory"

echo "Set read only permissions for other files"
chmod a+r wxowrk-message.page