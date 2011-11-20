<div style="margin:100px auto; width:500px;">
<table width="400px" cellspacing="0" cellpadding="5" class="border_table_org" align="center" >
        <thead>
            <tr><th>提示信息</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>
                   <div align="center"><br />
								<div style="color:red;font-weight:bold"><?php echo $msg; ?></div>
                                <br />
                                <br />
                                <br />
                                <br />
                                <?php if($auto): ?>
                                <script>
                                    function redirect($url)
                                    {
                                        location = $url;	
                                    }
                                    setTimeout("redirect('<?php echo $goto; ?>');", 3000);
                                </script>
                                <a href="<?php echo $goto; ?>" style="text-decoration:underline"><?php echo "页面正在自动转向，你也可以点此直接跳转！"; ?></a>
                                <br />
                                <br />
                                <br />
                                <br />
                                <?php endif; ?>
                            </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>


 			