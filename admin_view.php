<?php 
if(isset($loadSection))
{
	if($loadSection == 'main')
	{
		?>
<div id="body">
    <div class="page secondary with-sidebar">
        <div class="page-header">
            <div class="page-header-content">
                <h1>Gestionnaire d'articles<small></small></h1>
                <a class="back-button big page-back" href="<?php echo $this->core->url->site_url(array('admin','modules'));?>"></a>
            </div>
        </div>
        <?php echo $lmenu;?>          
        <div class="page-region">
            <div class="page-region-content">
                <div class="hub_table">
                	<h2>Liste des articles</h2>
                	<table cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <td>Intitué</td>
                                <td>Cat&eacute;gorie</td>
                                <td>Date de creation</td>
                                <td>Accéssibilité</td>
                                <td>Auteur</td>
                            </tr>
                        </thead>
                        <script>
						$(document).ready(function(){
							$('table .delete').bind('click',function(){
								if(confirm('Cette publication sera supprimé avec tous les commentaires qui y sont attachés. Continuer ?'))
								{
									var current	=	this;
									var items = [];
									$.getJSON($(this).attr('href'), function(data) {
										if(data.requestStatus === true)
										{
											$(current).closest('tr').fadeOut(500,function(){
												$(this).remove();
											})
										}
										else
										{
											alert('La suppréssion à échoué. Cette publication est introuvable, ou vous n\'avez pas le droit d\'effectuer cette suppréssion');
										}
									});
									return false;
								}
							});
						});
						</script>
                        <tbody>
                        <?php
                        if(count($getNews) > 0)
                        {
                            foreach($getNews as $g)
                            {
								$cat_name	=	$news->getSpeCat($g['CATEGORY_ID']);
                        ?>
                            <tr>
                                <td class="action"><a class="view" href="<?php echo $this->core->url->site_url(array('admin','open','modules',$module[0]['ID'],'edit',$g['ID']));?>"><?php echo $g['TITLE'];?></a></td>
                                <td><?php echo $cat_name['CATEGORY_NAME'];?></td>
                                <td><?php echo $g['DATE'];?></td>
                                <td><?php echo $g['ETAT'];?></td>
                                <td><?php echo $g['AUTEUR'];?></td>
                                <td><a class="delete" href="<?php echo $this->core->url->site_url(array('admin','open','modules',$module[0]['ID'],'delete',$g['ID']));?>">Supprimer</a>
                            </tr>
                        <?php
                            }
                        }
                        else
                        {
                            ?>
                            <tr>
                                <td colspan="5">Aucun article publié ou dans les brouillons</td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>
        <?php
	}
	else if($loadSection == 'publish')
	{
		?>
<div id="body">
    <div class="page secondary with-sidebar">
        <div class="page-header">
            <div class="page-header-content">
                <h1>Gestionnaire d'articles<small></small></h1>
            <a class="back-button big page-back" href="<?php echo $this->core->url->site_url(array('admin','modules'));?>"></a></div>
        </div>
        <?php echo $lmenu;?>          
        <div class="page-region">
            <div class="page-region-content">
                <div class="hub_table">
                	<div class="span8">
						<?php echo validation_errors(); ?>
                        <?php echo $this->core->notice->parse_notice();?>
                        <h2>Ecrire un article</h2>
                        <form method="post">
                            <div class="input-control text">
                                <input type="text" name="news_name" placeholder="Titre de l'article">
                            </div>
                            <div class="input-control select">
                            	<select name="push_directly">
                                    <option value="">Choisir une action</option>
                                    <option value="1">Publier directement l'article</option>
                                    <option value="2">Enregistrer dans les brouillons</option>
                                </select>
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
                            <div class="input-control input">
                            	<input name="image_link" type="text" placeholder="Lien vers l'image">
                            </div>
                            <div class="input-control textarea">
                            	<?php echo $this->core->hubby->getEditor(array('id'=>'editor','name'=>'news_content'));?>
                            </div>
                            <input type="submit" value="Enregistrer">
                        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
        <?php
	}
	else if($loadSection == 'edit')
	{
		?>
<div id="body">
    <div class="page secondary with-sidebar">
        <div class="page-header">
            <div class="page-header-content">
                <h1>Gestionnaire d'articles<small></small></h1>
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
        <?php
	}
	else if($loadSection == 'category')
	{
		?>
<div id="body">
    <div class="page secondary with-sidebar">
        <div class="page-header">
            <div class="page-header-content">
                <h1>Gestionnaire d'articles<small></small></h1>
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
	<?php
	}
	else if($loadSection == 'create_cat')
	{
		?>
<div id="body">
    <div class="page secondary with-sidebar">
        <div class="page-header">
            <div class="page-header-content">
                <h1>Gestionnaire d'articles<small></small></h1>
            <a class="back-button big page-back" href="<?php echo $this->core->url->site_url(array('admin','open','modules',$module[0]['ID'],'category'));?>"></a></div>
        </div>
        <?php echo $lmenu;?>          
        <div class="page-region">
            <div class="page-region-content">
                <div class="hub_table">
                	<h2>Cr&eacute;er une cat&eacute;gorie</h2>
                    <form action="" class="jNice" method="post">
                    	<div class="input-control text">
                            <input name="cat_name" type="text" placeholder="Nom de la cat&eacute;gorie" />
                            <button class="btn-clear"></button>
                        </div>
                        <div class="input-control text">
                            <textarea name="cat_description" type="text" placeholder="Description de la cat&eacute;gorie"></textarea>
                        </div>
						<input type="submit" value="Créer une cat&eacute;gorie">
                    </form>
                    <div>
						<?php echo notice_from_url();?>
					</div>
                    <br />
					<div>
                        <?php echo $this->core->notice->parse_notice();?>
                    </div>
                    <br />
                </div>
			</div>
		</div>
	</div>
</div>
	<?php
	}
	else if($loadSection == 'manage_cat')
	{
		?>
<div id="body">
    <div class="page secondary with-sidebar">
        <div class="page-header">
            <div class="page-header-content">
                <h1>Gestionnaire de cat&eacute;gorie<small></small></h1>
            <a class="back-button big page-back" href="<?php echo $this->core->url->site_url(array('admin','open','modules',$module[0]['ID'],'category'));?>"></a></div>
        </div>
        <?php echo $lmenu;?>          
        <div class="page-region">
            <div class="page-region-content">
                <div class="hub_table">
                	<h2>Modifier une cat&eacute;gorie</h2>
                    <form action="" class="jNice" method="post">
                    	<div class="input-control text">
                            <input type="text" name="cat_name" value="<?php echo $cat['name'];?>" placeholder="Nom de la cat&eacute;gorie">
                            <button class="btn-clear"></button>
                        </div>
                        <div class="input-control text">
                        	<textarea name="cat_description" placeholder="Description de la cat&eacute;gorie"><?php echo $cat['desc'];?></textarea>
                        </div>
                        <input type="hidden" name="cat_id" value="<?php echo $cat['id'];?>">
                      <input type="submit" value="Modifier la cat&eacute;gorie">
                      <input type="hidden" value="ALLOWEDITCAT" name="allower">
                    </form>
                    <h2>Supprimer la cat&eacute;gorie</h2>
                    <form action="" class="jNice" method="post">
                    	<div class="input-control text">
                        	<span class="notice">Une cat&eacute;gorie ne peut &ecirc;tre supprim&eacute;e si certaines publications y sont encore attach&eacute;s. Rassurez-vous qu'aucun article n'est rattach&eacute; &agrave; cette cat&eacute;gorie avant de la supprimer</span>
                        </div>
                    	<input type="hidden" value="<?php echo $cat['id'];?>" name="cat_id_for_deletion">
                        <input type="hidden" value="ALLOWCATDELETION" name="allower">
                        <input type="submit" value="supprimer la cat&eacute;gorie">
                    </form>
                    <div>
						<?php echo notice_from_url();?>
					</div>
                    <br />
					<div>
                        <?php echo $this->core->notice->parse_notice();?>
                    </div>
                    <br />
                </div>
			</div>
		</div>
	</div>
</div>
	<?php
	}
	else if($loadSection == 'delete_news')
	{
		if($delete === true)
		{
		?>
{"requestStatus" : true}<?php
		}
		else
		{
		?>
{"requestStatus" : false}<?php
		}
	}
}
else
{
	?>
    Error Occured During loading.
    <?php
}
?>