<div id="body">
    <div class="page secondary with-sidebar">
        <div class="page-header">
            <div class="page-header-content">
                <h1><?php echo $module[0]['HUMAN_NAME'];?><small></small></h1>
            <a class="back-button big page-back" href="<?php echo $this->core->url->site_url(array('admin','open','modules',$module[0]['ID']));?>"></a></div>
        </div>
        <?php echo $lmenu;?>          
        <div class="page-region">
            <div class="page-region-content">
                <div class="hub_table">
                	<h2>Liste des cat&eacute;gories</h2>
                    <table cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <td>Nom</td>
                                <td>Description</td>
                                <td>Date de creation</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(count($getCat) > 0)
                        {
                            foreach($getCat as $g)
                            {
                        ?>
                            <tr>
                                <td class="action"><a class="view" href="<?php echo $this->core->url->site_url(array('admin','open','modules',$module[0]['ID'],'category','manage',$g['ID']));?>"><?php echo $g['CATEGORY_NAME'];?></a></td>
                                <td><?php echo $g['DESCRIPTION'];?></td>
                                <td><?php echo $g['DATE'];?></td>
                            </tr>
                        <?php
                            }
                        }
                        else
                        {
                            ?>
                            <tr>
                                <td colspan="3">Aucune cat&eacute;gorie disponible</td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <div>
					<?php echo $this->core->notice->parse_notice();?>
                    </div>
                    <br />
                    <div>
                    <?php echo notice_from_url();?>
                    </div>
					<br />
                </div>
			</div>
		</div>
	</div>
</div>