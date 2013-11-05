<?php 
	if($section == 'main')
	{
		if(count($getNews) > 0)
		{
			foreach($getNews as $g)
			{
				$userdata		=	$userUtil->getUser($g['AUTEUR']);
				$date			=	$this->core->hubby->time($g['DATE']);
				$Pcategory		=	$news->retreiveCat($g['CATEGORY_ID']);
				$theme->defineBlogPost(
					$title		=	$g['TITLE'],
					$content	=	$g['CONTENT'],
					$thumb		=	$g['IMAGE'],
					$full		=	$g['IMAGE'],
					$author		=	$userdata['PSEUDO'],
					$link		=	$this->core->url->site_url(array($page[0]['PAGE_CNAME'],'read',$g['ID'],$this->core->hubby->urilizeText($g['TITLE']))),
					$timestamp	=	$date,
					$category	=	$Pcategory['name'],
					$categoryLink=	$Pcategory['url']
				);
			}
			$superArray['currentPage']	=	$currentPage;
			$superArray['totalPage']	=	$ttNews;
			$superArray['innerLink']	=	$pagination[4];
	
			$theme->set_pagination_datas($superArray);
			$theme->parseBlog();
		}
		else
		{
			$theme->parseBlog();
		}
	}
	else if($section == 'open')
	{
		// ARTICLE SECTION
		$userdata				=	$userUtil->getUser($GetNews[0]['AUTEUR']);
		$date					=	$this->core->hubby->time($GetNews[0]['DATE'],true);
		$Ccategory				=	$news->retreiveCat($GetNews[0]['CATEGORY_ID']);
		// COMMENT SECTION
		$theme->defineSingleBlogPost(
			$title				=	$GetNews[0]['TITLE'],
			$content			=	$GetNews[0]['CONTENT'],
			$thumb				=	$GetNews[0]['IMAGE'],
			$full				=	$GetNews[0]['IMAGE'],
			$author				=	$userdata['PSEUDO'],
			$timestamp			=	strtotime($GetNews[0]['DATE']),
			$category			=	$Ccategory['name'],
			$categoryLink		=	$this->core->url->site_url($Ccategory['url'])
		);
		$theme->defineTT_SBP_comments($news->countComments($GetNews[0]['ID']));
		// intégration des commentaires
		if(count($Comments) >0)
		{
			foreach($Comments as $c)
			{
				$userdata		=	$this->core->users_global->getUser($c['AUTEUR']);
				$theme->defineSBP_comments(
					$author			=	(count($userdata) == 0) ? 'Utilisateur introuvable' : $userdata['PSEUDO'],
					$authorLink		=	'#',
					$content		=	$c['CONTENT'],
					$timestamp		=	$this->core->hubby->timespan($c['DATE'])
				);
			}
		}
		// Pagination
		$theme->set_pagination_datas(array(
			'innerLink'			=>		$pagination[4],
			'currentPage'		=>		$currentPage
		));
		// Intégration du formulaire de réponse.
		if($this->core->users_global->isConnected())
		{
			$pseudo	=	$this->core->users_global->current('PSEUDO');
			$email	=	$this->core->users_global->current('EMAIL');
		}
		else
		{
			$pseudo	=	'';
			$email	=	'';
		}
		// REPLY FORM
		$theme->defineForm(array(
			'text'			=>	'Pseudo',
			'name'			=>	'pseudo',
			'value'			=>	$pseudo,
			'subtype'		=>	'text',
			'type'			=>	'input'
		));
		$theme->defineForm(array(
			'text'			=>	'Entrer votre mail',
			'name'			=>	'mail',
			'value'			=>	$email,
			'subtype'		=>	'text',
			'type'			=>	'input'
		));
		$theme->defineForm(array(
			'text'			=>	'Entrer votre commentaire',
			'name'			=>	'content',
			'subtype'		=>	'text',
			'type'			=>	'textarea'
		));
		$theme->defineForm(array(
			'subtype'		=>	'submit',
			'value'			=>	'Poster',
			'type'			=>	'input'
		));
		// Affichage du single article
		$theme->parseBlog();
	}
	else if($section == 'category')
	{
		if(count($getNews) > 0)
		{
			foreach($getNews as $g)
			{
				$userdata		=	$userUtil->getUser($g['AUTEUR']);
				$date			=	$this->core->hubby->time($g['DATE'],TRUE);
				$theme->defineBlogPost(
					$title		=	$g['TITLE'],
					$content	=	$g['CONTENT'],
					$thumb		=	$g['IMAGE'],
					$full		=	$g['IMAGE'],
					$author		=	$userdata['PSEUDO'],
					$link		=	$this->core->url->site_url(array($page[0]['PAGE_CNAME'],'read',$g['ID'],$this->core->hubby->urilizeText($g['TITLE']))),
					$timestamp	=	strtotime($g['DATE']),
					$categoryLink=	$category['name'],
					$categoryText=	$category['url']
				);
			}
			$superArray['currentPage']	=	$currentPage;
			$superArray['totalPage']	=	$ttNews;
			$superArray['innerLink']	=	$pagination[4];
	
			$theme->set_pagination_datas($superArray);
		}
		$theme->parseBlog();
	}
	else if($section == 'test')
	{
		$theme->defineBlogPost(
			'This is a sample title',
			'This is a sample content i\'m ready for it',
			'http://localhost/sliders/elastSide/images/large/12.jpg',
			'http://localhost/sliders/elastSide/images/large/12.jpg',
			'John Doe',
			'#',
			'',
			'BLog Post',
			'#'
		);
		$theme->defineBlogPost(
			'This is a sample title',
			'This is a sample content i\'m ready for it',
			'http://localhost/sliders/elastSide/images/large/12.jpg',
			'http://localhost/sliders/elastSide/images/large/12.jpg',
			'John Doe',
			'#',
			'',
			'BLog Post',
			'#'
		);
		
		
		$theme->parseBlog();
	}
	else if($section == 'blogtest')
	{
		$theme->defineContactAddress('Notre contact', 'utilisez ces num&eacute;ros pour nous contacter');
		$theme->defineContactContent('besoin d\'aide ? contactez nous');
		$theme->defineContactAddressItem('phone','3992039394');
		$theme->defineContactAddressItem('phone','2304958576');
		$theme->defineContactAddressItem('email','fakecontact@fakebox.com');
		$theme->defineContactFormHeader();
		$theme->
		$theme->parseContact();
	}