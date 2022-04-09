<hr>
<div class="" id="block_modulewidth50" style="float: left;">
    <table width="100%">
        <tr>
            <td colspan="2">
                Your Service Information:
            </td>
        </tr>
        <tr>
            <td>Ram: </td>
            <td><?php echo $serverData['memorySize']; ?> MB</td>
        </tr>
        <tr>
            <td>Cpu: </td>
            <td><?php echo $serverData['cpuCore']; ?> core</td>
        </tr>
        <tr>
            <td>Disk: </td>
            <td><?php echo $serverData['diskSize']; ?> GB</td>
        </tr>
        <tr>
            <td>Operation System: </td>
            <td><?php echo $serverData['template']['name']; ?></td>
        </tr>
    </table>
</div>
<div class="" id="block_modulewidth50" style="float: left;">
    <h5 style="text-align: left;">
        Select new os to change:
    </h5>
    <form id="osForm" action=<?php echo $module->area_link; ?>?inc=panel_operation_method&method=change_os"" method="post">
        <select id="osId" name="os">
            <?php
            foreach ($templates as $template) {
                ?>
                <option value="<?php echo $template['id']; ?>">
                    <?php echo $template['name']; ?>
                </option>
                <?php
            }
            ?>
        </select>
        <button style="cursor:pointer;float: left;" type="submit" class="hostbtn red">Change Os</button>
    </form>
</div>
<script>
    $('#osForm').submit(function (e){
        e.preventDefault();
        var confirmForm = confirm('Are you sure to change os?');
        if (confirmForm){
            var osId = $('#osId').val();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: {os : osId},
                dataType: 'json',
                success: function(result){
                    alert(result.message)
                }
            });
        }

        return false;
    });
</script>
