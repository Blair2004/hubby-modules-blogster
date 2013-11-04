<?php
class Hubby_installer
{
	private $appType;
	private $appVers;
	private $appHubbyVers;
	private $appTableField;
	private $appSql;
	private $appAdminWidgetTableField;
	private $appAction;
	public function __construct()
	{
		$this->appType		=	'MODULE';
		$this->appTableField	=	array(
			'NAMESPACE'		=> 'news',
			'HUMAN_NAME'	=> 'Gestionnaire d\'articles',
			'AUTHOR'		=> 'Hubby Group',
			'DESCRIPTION'	=> 'Créer, modifier et publié des articles pour votre blog.',
			'TYPE'			=> 'BYPAGE',
			'HUBBY_VERS'	=> 0.9
		);
		$this->appVers		=	0.9;
		$this->appAdminWidgetTableField[]	=	array(
			'FILE_LOCATION'		=>	'/quickpress.php',
			'CLASS_NAME'		=>	'quick_press',
			'HUMAN_NAME'		=>	'Quick Press'
		);
			
		$this->appAdminWidgetTableField[]	=	array(
			'FILE_LOCATION'		=>	'/statistics.php',
			'CLASS_NAME'		=>	'statistics',
			'HUMAN_NAME'		=>	'Statistiques'
		);
		$this->appSql[]	=	
		'CREATE TABLE IF NOT EXISTS `hubby_comments` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `REF_ART` int(11) NOT NULL,
		  `CONTENT` text NOT NULL,
		  `DATE` varchar(200) NOT NULL,
		  `AUTEUR` int(11) NOT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
		$this->appSql[]	=	
		'CREATE TABLE IF NOT EXISTS `hubby_news` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `CATEGORY_ID` int(11) NOT NULL,
		  `TITLE` varchar(200) NOT NULL,
		  `CONTENT` text NOT NULL,
		  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `AUTEUR` int(11) NOT NULL,
		  `ETAT` varchar(5) NOT NULL,
		  `IMAGE` varchar(200) NOT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		';
		$this->appSql[]	=	
		'CREATE TABLE IF NOT EXISTS `hubby_news_category` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `CATEGORY_NAME` varchar(100) NOT NULL,
		  `DESCRIPTION` text NOT NULL,
		  `DATE` datetime NOT NULL,
		  PRIMARY KEY (`ID`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		';
		$this->appAction[]			=	array(
			'action'				=>	'publish_news',
			'action_name'			=>	'Publier les articles',
			'action_description'	=>	'Action qui permet &agrave; tout utilisateur de publier des articles',
			'mod_namespace'			=>	'news'
		);
		$this->appAction[]			=	array(
			'action'				=>	'delete_news',
			'action_name'			=>	'Supprimer les articles',
			'action_description'	=>	'Action qui permet &agrave; tout utilisateur de supprimer des articles',
			'mod_namespace'			=>	'news'
		);
		$this->appAction[]			=	array(
			'action'				=>	'edit_news',
			'action_name'			=>	'Modifier les articles',
			'action_description'	=>	'Action qui permet &agrave; tout utilisateur de modifier des articles',
			'mod_namespace'			=>	'news'
		);
		$this->appAction[]			=	array(
			'action'				=>	'category_manage',
			'action_name'			=>	'Gestion des cat&eacute;gories',
			'action_description'	=>	'Action qui permet &agrave; tout utilisateur de g&eacute;rer les cat&eacute;gories',
			'mod_namespace'			=>	'news'
		);
	}
	public function datas()
	{
		return array(
			'appType'					=>	$this->appType,
			'appVers'					=>	$this->appVers,
			'appHubbyVers'				=>	$this->appHubbyVers,
			'appTableField'				=>	$this->appTableField,
			'appSql'					=>	$this->appSql,
			'appAdminWidgetTableField'	=>	$this->appAdminWidgetTableField,
			'appAction'					=>	$this->appAction
		);
	}
}