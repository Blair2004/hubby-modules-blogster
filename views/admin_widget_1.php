<h2>Quick Press</h2>
<?php
if(is_array($cat) && count($cat) > 0)
{
	?>
	<form method="post">
	<br />
	<div class="input-control text">
    	<input type="text" name="quick_press_title" placeholder="Titre de l'article" />
	</div>
    <div class="input-control text">
    	<input type="text" name="quick_press_img_link" placeholder="Lien vers l'image" />
	</div>
    <div class="input-control textarea" title="Contenu de l'article">
        <textarea placeholder="Contenu de l'article" type="text" name="quick_press_content"></textarea>
    </div>
    <div class="input-control select">
        <select name="quick_press_cat">
            <option value="">Choisir une cat&eacute;gorie</option>
            <?php
			if(is_array($cat))
			{
				foreach($cat as $c)
				{
					?>
                    <option value="<?php echo $c['ID'];?>"><?php echo $c['CATEGORY_NAME'];?></option>
                    <?php
				}
			}
			?>
        </select>
    </div>
    <div class="input-control select">
        <select name="push_directly">
            <option value="">Choisir une action</option>
            <option value="1">Publier directement l'article</option>
            <option value="2">Enregistrer dans les brouillons</option>
        </select>
    </div>
	<div><input type="submit" name="submit" /></div>
</form>
	<?php
}
else
{
	?>
    <br />
    <p>Pour poster un article, vous devez au moins cr&eacute;er une cat&eacute;gorie. <a  href="<?php echo $this->core->url->site_url(array('admin','open','modules',$getMod[0]['ID'],'category','create'));?>">Cliquez ici</a> pour cr&eacute;er une cat&eacute;gorie.
    <?php
}
notice_from_url_by_modal();
echo validation_errors();