<?php
    
    $vpn_onoff = isset($_POST['vpn_onoff']) ? $_POST['vpn_onoff'] : '';
    $onoff = isset($_POST['onoff']) ? $_POST['onoff'] : '';
    if (($onoff == "onoff") ){
    $db = new PDO('sqlite:dbf/nettemp.db');
    $db->exec("UPDATE settings SET vpn='$vpn_onoff' WHERE id='1'") or die ($db->lastErrorMsg());
    if (!empty($vpn_onoff)) {
    shell_exec("sudo sed -i '/iface eth0/a openvpn openvpn' /etc/network/interfaces");
    shell_exec("sudo service openvpn start");
    }
    else {	
    shell_exec("sudo sed -i '/openvpn openvpn/d' /etc/network/interfaces");
    shell_exec("sudo service openvpn stop");
    } 
    header("location: " . $_SERVER['REQUEST_URI']);
    exit();
    }
?>
<?php
$db = new PDO('sqlite:dbf/nettemp.db');
$sth = $db->prepare("select * from settings ");
$sth->execute();
$result = $sth->fetchAll();
foreach ($result as $a) {
$vpn=$a["vpn"];

}
?>

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">VPN</h3>
</div>
<div class="panel-body">


<table>

<tr> <td><h2>OpenVPN</h2></td>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
    <td><input type="checkbox" name="vpn_onoff" value="on" <?php echo $vpn == 'on' ? 'checked="checked"' : ''; ?> onclick="this.form.submit()" /></td>
    <input type="hidden" name="onoff" value="onoff" />
    </form>
</tr> 
</table>
<?php
if ($vpn == "on" ) { 
    include('vpn_add.php');
	 } 
?>

</div>
</div>


