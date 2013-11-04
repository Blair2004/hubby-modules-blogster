<h2>Statistiques Press</h2>
<br />
<ul>
	<li>Nombres d'articles : <?php echo count($nbrArticles->result_array());?></li>
    <li>Nombres d'articles publi&eacute;s : <?php echo count($nbrArticlesActvated->result_array());?></li>
    <li>Nombres de brouillon : <?php echo count($draft->result_array());?></li>
</ul>