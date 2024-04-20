<?
$cfg = parse_ini_file('/boot/config/plugins/wxwork_message/wxwork_message.cfg');
$save = true;
if ($_POST['ca_active_config'] == "-1") {
    exec("rm -f /boot/config/plugins/wxwork_message/wxwork_message.cfg");
    exec("rm -f /usr/local/emhttp/plugins/wxwork-message");
} else {
    $crop_id = $_POST['crop_id'];
    $agent_id = $_POST['agent_id'];
    $secret = $_POST['secret'];
    $proxy_url = $_POST['proxy_url'];
    $message_type = $_POST['message_type'];
    exec("echo -e \"crop_id=$crop_id\nagent_id=$agent_id\nsecret=$secret\nproxy_url=$proxy_url\nmessage_type=$message_type\" > /boot/config/plugins/wxwork_message/wxwork_message.cfg");
    $scriptContent = <<<EOT
        #!/bin/bash

        # 企业微信相关信息
        CORP_ID="$crop_id"
        APP_SECRET="$secret"
        TO_USER="@all"
        AGENT_ID="$agent_id"

        # 获取 access token
        ACCESS_TOKEN=\$(curl -s -G "$proxy_url/cgi-bin/gettoken" \
            -d "corpid=\$CORP_ID" \
            -d "corpsecret=\$APP_SECRET" | jq -r '.access_token')

        # 发送消息
        send_message() {
            local message="[Unraid]->[\$SUBJECT]\\n\$DESCRIPTION"
            curl -s -H "Content-Type: application/json" -X POST \
                "$proxy_url/cgi-bin/message/send?access_token=\$ACCESS_TOKEN" \
                -d "{
                    \"touser\": \"\$TO_USER\",
                    \"msgtype\": \"text\",
                    \"agentid\": \"\$AGENT_ID\",
                    \"text\": {
                        \"content\": \"\$message\"
                    },
                    \"safe\":0
                }"
        }

        # 调用发送消息函数
        send_message
        EOT;
    $scriptPath = "/boot/config/plugins/dynamix/notifications/agents/$message_type.sh";
    $command = "echo '$scriptContent' > $scriptPath";
    exec($command, $output, $return_var);
    // 检查执行结果
    if ($return_var === 0) {
        return "脚本生成成功！";
    } else {
        return "脚本生成失败！";
    }
}