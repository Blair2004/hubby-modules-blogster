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
                        <h2>Modifier un article</h2>
                        <form method="post">
                            <div class="input-control text">
                                <input type="text" name="news_name" value="<?php echo $news[0]['TITLE'];?>" placeholder="Titre de  l'article">
                            </div>
                            <div class="input-control select">
                            	<select name="category">
                                    <option value="">Choisir la cat&eacute;gorie</option>
                                    <?php
                                    foreach($categories as $c)
                                    {
                                    ?>
                                    <option value="<?php echo $c['ID'];?>"><?php echo $c['CATEGORY_NAME'];?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-control select">
                            	<select name="push_directly">
                                    <option value="">Choisir une action</option>
                                    <option value="1">Publier l'article</option>
                                    <option value="2">Enregistrer dans les brouillons</option>
                                </select>
                            </div>
                            <div class="input-control input">
					            <input name="image_link" type="text" value="<?php echo $news[0]['IMAGE'];?>">                            	
                            </div>
                            <div class="input-control textarea">
                            	<?php echo $this->core->hubby->getEditor(array('id'=>'editor','name'=>'news_content','defaultValue'=>$news[0]['CONTENT']));?>
                            </div>
                            <input type="hidden" value="<?php echo $news[0]['ID'];?>" name="article_id">
                            <input type="submit" value="Enregistrer les modifications">
                        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>