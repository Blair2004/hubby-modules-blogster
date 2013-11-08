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
                	<div class="span8">
						<?php echo validation_errors(); ?>
                        <?php echo $this->core->notice->parse_notice();?>
                        <h2>Param&ecirc;tres avanc&eacute;s</h2>
                        <form method="post">
                            <label  class="input-control switch">
                                <input type="checkbox" name="validateall" value="true" 
                                <?php
                                if($setting['APPROVEBEFOREPOST'] == "1")
                                {
                                    ?>
                                    checked="checked"
                                    <?php
                                }
                                ?> />
                                <span class="helper">Valider chaque commentaire avant de l'afficher </span>
                            </label >
                            <label  class="input-control switch">
                            	<input type="checkbox" name="allowPublicComment" value="true" <?php
								if($setting['EVERYONEPOST'] == "1")
								{
									?>
                                    checked="checked"
                                    <?php
								}
								?> />
                                <span class="helper">Autoriser les membres non inscrit &agrave; commenter</span>
                            </label >
                            <p><input type="submit" value="Enregistrer les modifications" name="update"></p>
                        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>