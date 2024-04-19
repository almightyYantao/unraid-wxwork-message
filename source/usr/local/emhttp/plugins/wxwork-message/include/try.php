<?
$cfg = parse_ini_file('/boot/config/plugins/wxwork_message/wxwork_message.cfg');
$save = true;
if ($_POST['ca_active_config'] == "-1") {
   exec("rm -f /boot/config/plugins/wxwork_message/wxwork_message.cfg");
} else {
    $crop_id = $_POST['crop_id'];
    $agent_id = $_POST['agent_id'];
    $secret = $_POST['secret'];
    $proxy_url = $_POST['proxy_url'];
    exec("echo -e \"crop_id=$crop_id\nagent_id=$agent_id\nsecret=$secret\nproxy_url=$proxy_url\" > /boot/config/plugins/wxwork_message/wxwork_message.cfg");
}