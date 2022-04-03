<?php
$LANG = $module->lang;
$product = isset($product) && $product ? $product : [];
$module_data = isset($product["module_data"]) ? Utility::jdecode($product["module_data"], true) : [];

if ($module_data) {
    $hard = isset($module_data["hard"]) ? $module_data["hard"] : false;
    $ram = isset($module_data["ram"]) ? $module_data["ram"] : false;
    $cpu = isset($module_data["cpu"]) ? $module_data["cpu"] : false;
    $pool = isset($module_data["pool"]) ? $module_data["pool"] : false;
} else {
    $hard = "";
    $ram = "";
    $cpu = "";
    $pool = "";
}

$pools = $module->poolsList()['data'];
?>

<div class="formcon">
    <div class="yuzde30"><?php echo $LANG["disk-space"]; ?> (GB)</div>
    <div class="yuzde70">
        <input type="text" name="module_data[hard]" value="<?php echo $hard; ?>" style="width: 100px;"
               onkeypress='return event.charCode>= 48 &&event.charCode<= 57'>
    </div>
</div>

<div class="formcon">
    <div class="yuzde30">RAM (MB)</div>
    <div class="yuzde70">
        <input type="text" name="module_data[ram]" value="<?php echo $ram; ?>" style="width: 100px;"
               onkeypress='return event.charCode>= 48 &&event.charCode<= 57'>
    </div>
</div>

<div class="formcon">
    <div class="yuzde30">CPU (CORE)</div>
    <div class="yuzde70">
        <input type="text" name="module_data[cpu]" value="<?php echo $cpu; ?>" style="width: 100px;"
               onkeypress='return event.charCode>= 48 &&event.charCode<= 57'>
    </div>
</div>

<div class="formcon">
    <div class="yuzde30">Pool</div>
    <div class="yuzde70">
        <select name="module_data[pool]">
            <?php
            foreach ($pools as $poolItem) {
                ?>
                <option value="<?php echo $poolItem['id']; ?>" <?php echo $poolItem['id'] == $pool ? 'selected' : ''; ?>>
                    <?php echo $poolItem['name']; ?>
                </option>
                <?php
            }
            ?>
        </select>
    </div>
</div>