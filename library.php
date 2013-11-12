<?php
/// -------------------------------------------------------------------------------------------------------------------///
global $NOTICE_SUPER_ARRAY;
/// -------------------------------------------------------------------------------------------------------------------///
$or['categoryCreated']			=	'<span class="hubby_success">La cat&eacute;gorie &agrave; &eacute;t&eacute; correctement cr&eacute;e</span>';
$or['categoryAldreadyCreated']	=	'<span class="hubby_error">Cette cat&eacute;gorie existe d&eacute;j&agrave;</span>';
$or['unknowCat']				=	'<span class="hubby_error">Cette cat&eacute;gorie est inexistante</span>';
$or['categoryUpdated']			=	'<span class="hubby_success">La mise &agrave; jour &agrave; r&eacute;ussie</span>';
$or['CatDeleted']				=	'<span class="hubby_success">La cat&eacute;gorie &agrave; &eacute;t&eacute; supprim&eacute; avec succ&egrave;s</span>';
$or['CatNotEmpty']				=	'<span class="hubby_error">Cette cat&eacute;gorie ne peut pas &ecirc;tre supprim&eacute;e, car il existe des publications qui y sont rattach&eacute;es. Changez la cat&eacute;gorie de ces publications avant de supprimer cette cat&eacute;gorie.</span>';
$or['noCategoryCreated']		=	'<span class="hubby_error"><i class="icon-warning"></i> Avant de publier un article, vous devez cr&eacute;er une cat&eacute;gorie.</span>';
$or['connectToComment']			=	'<span class="hubby_error"><i class="icon-warning"></i> Vous devez &ecirc;tre connect&eacute; pour commenter.</span>';
$or['unknowComments']			=	'<span class="hubby_error"><i class="icon-warning"></i> Commentaire introuvable.</span>';
$or['commentDeleted']			=	'<span class="hubby_success"><i class="icon-checkmark"></i> Commentaire supprim&eacute;.</span>';

/// -------------------------------------------------------------------------------------------------------------------///
$NOTICE_SUPER_ARRAY = $or;
/// -------------------------------------------------------------------------------------------------------------------///
	if(class_exists('hubby_admin'))
	{
		class News
		{
			private $data;
			private $user;
			private $core;
			private $hubby;
			private $mod_repo;
			public function __construct($data)
			{
				$this->core		=	Controller::instance();
				$this->data		=	$data;
				$this->user		=&	$this->core->users_global;
				$this->hubby	=&	$this->core->hubby;	
				$this->mod_repo	=	MODULES_DIR.$data['module'][0]['ENCRYPTED_DIR'].'/';
			}
			public function retreiveCat($id)
			{
				$this->core->db			->from('hubby_news_category')
										->where('ID',$id);
				$query					= $this->core->db->get();
				$data					=	$query->result_array();
				if(count($data) == 0)
				{
					return 
					array(
						'name'		=>'Categorie Inconnu',
						'id'		=>0,
						'desc'		=>''
					);
				}
				else
				{
					return array(
						'name'		=>$data[0]['CATEGORY_NAME'],
						'id'		=>$data[0]['ID'],
						'desc'		=>$data[0]['DESCRIPTION']
					);
				}
			}
			public function datetime()
			{
				return $this->hubby->datetime();
			}
			public function getMenu()
			{
				return $this->core->load->view($this->mod_repo.'views/menu.php',$this->data,true,true);
			}
			public function countNews()
			{
				$query = $this->core->db->get('hubby_news');
				return count($query->result_array());
			}
			public function getNews($start,$end)
			{
				$this->core->db	->select('*')
								->from('hubby_news')
								->limit($end,$start);
				$query			=	$this->core->db->get();
				return $query->result_array();
			}
			public function publish_news($title,$content,$state,$image,$cat)
			{
				$content		=	array(
				'TITLE'			=> $title,
				'CONTENT'		=> $content,
				'IMAGE'			=> $image,
				'AUTEUR'		=> $this->user->current('ID'),
				'ETAT'			=> $state,
				'DATE'			=> $this->datetime(),
				'CATEGORY_ID'	=> $cat
				);
				return $this->core->db	->insert('hubby_news',$content);
			}
			public function edit($id,$title,$content,$state,$image,$cat)
			{
				$content	=	array(
					'TITlE'			=> $title,
					'CONTENT'		=> $content,
					'ETAT'			=> $state,
					'IMAGE'			=> $image,
					'AUTEUR'		=> $this->user->current('ID'),
					'DATE'			=> $this->datetime(),
					'CATEGORY_ID'	=> $cat
				);
				$this->core->db->where('ID',$id);
				return $this->core->db->update('hubby_news',$content);
			}
			public function getSpeNews($id)
			{
				$this->core->db	->where(array('ID'=>$id));
				$query			=	$this->core->db->get('hubby_news');
				$result			=	$query->result_array();
				if(count($result) > 0)
				{
					return $result;
				}
				return false;
			}
			public function countCat()
			{
				$query	=	$this->core->db->get('hubby_news_category');
				return count($query->result_array());
			}
			public function deleteSpeNews($id)
			{
				if($this->getSpeNews($id))
				{
					$this->core->db->where('REF_ART',$id)->delete('hubby_comments');
					$this->core->db->where('ID',$id)->delete('hubby_news');
					return true;
				}
				return false;
			}
			public function getCat($start = null,$end = null)
			{
				if($start == null && $end == null)
				{
					$query	=	$this->core->db->get('hubby_news_category');
				}
				else if($start != null && $end == null)
				{
					$query	=	$this->core->db->where('ID',$start)->get('hubby_news_category');
					$ar		=	$query->result_array();
					return $ar[0];
				}
				else
				{
					$query	=	$this->core->db->limit($end,$start)->order_by('ID','desc')->get('hubby_news_category');
				}
				return $query->result_array();
			}
			public function getSpeCat($id)
			{
				$query	=	$this->core->db->where('ID',$id)->get('hubby_news_category');
				$ar		=	$query->result_array();
				if(count($ar) == 0)
				{
					return array('CATEGORY_NAME'=>'Cat&eacute;gorie inconnue');
				}
				return $ar[0];
			}
			public function createCat($name,$description)
			{
				$query  = $this->core->db->where('CATEGORY_NAME',strtolower($name))->get('hubby_news_category');
				if(count($query->result_array()) == 0)
				{
					$array	=	array(
						'CATEGORY_NAME'	=>$name,
						'DESCRIPTION'	=>$description,
						'DATE'			=>$this->hubby->datetime()
					);
					$this->core->db->insert('hubby_news_category',$array);
					return 'categoryCreated';
				}
				return 'categoryAldreadyCreated';
			}
			public function editCat($id,$name,$description)
			{
				$query  = $this->core->db->where('ID',$id)->get('hubby_news_category');
				if(count($query->result_array()) > 0)
				{
					$array	=	array(
						'CATEGORY_NAME'	=>$name,
						'DESCRIPTION'	=>$description,
						'DATE'			=>$this->hubby->datetime()
					);
					$this->core->db->where('ID',$id)->update('hubby_news_category',$array);
					return 'categoryUpdated';
				}
				return 'unknowCat';
			}
			public function deleteCat($id)
			{
				$query	=	$this->core->db->where('CATEGORY_ID',$id)->get('hubby_news');
				if(count($query->result_array()) > 0)
				{
					return 'CatNotEmpty';
				}
				$this->core->db->where('ID',$id)->delete('hubby_news_category');
				return 'CatDeleted';
			}
			public function countComments()
			{
				$query	=	$this->core->db->get('hubby_comments');
				$result	=	$query->result_array();
				return count($result);
			}
			public function getComments($start,$end)
			{
				$query	=	$this->core->db->order_by('ID','desc')->limit($end,$start)->get('hubby_comments');
				$result	=	$query->result_array();
				return $result;
			}
			public function setBlogsterSetting($validateComments,$allowPublicComments)
			{
				/* 
				/*	1 : TRUE; 0 : FALSE
				*/
				$query	=	$this->core->db->get('hubby_news_setting');
				$result	=	$query->result_array();
				if(count($result) > 0)
				{
					if($allowPublicComments)
					{
						$APC	=	1;
					}
					else
					{
						$APC	=	0;
					}
					if($validateComments)
					{
						$VC		=	1;
					}
					else
					{
						$VC		=	0;
					}
					return $this->core->db->update('hubby_news_setting',array(
						'EVERYONEPOST'		=>	$APC,
						'APPROVEBEFOREPOST'	=>	$VC // Vlidate comments
					));
				}
				else
				{
					if($allowPublicComments)
					{
						$APC 		=	1; // Allow pulic comments
					}
					else
					{
						$APC 		=	0; // Allow pulic comments
					}
					if($validateComments)
					{
						$VC		=	1;
					}
					else
					{
						$VC		=	0;
					}
					return $this->core->db->insert('hubby_news_setting',array(
						'EVERYONEPOST'			=>		$APC,	
						'APPROVEBEFOREPOST'		=>		$VC,	
					));
				}
			}
			public function getBlogsterSetting()
			{
				$query	=	$this->core->db->get('hubby_news_setting');
				$result	=	$query->result_array();
				return array_key_exists(0,$result) ? $result[0] : false;
			}
			public function getSpeComment($id)
			{
				$query		=	$this->core->db->where(array('ID'=>$id))->get('hubby_comments');
				$result		=	$query->result_array();
				if(count($result) == 0): return false;endif; // return false if comment doesn't exist
				if($result[0]['AUTEUR'] == '0')
				{
					$result[0]['AUTEUR']	=	$result[0]['OFFLINE_AUTEUR'];
				}
				$article	=	$this->getSpeNews($result[0]['REF_ART']);
				$result[0]['ARTICLE_TITLE']	=	$article[0]['TITLE'];
				return $result[0];
			}
			public function approveComment($id)
			{
				if($comment	=	$this->getSpeComment($id)) // If comment exist
				{
					return $this->core->db->where('ID',$id)->update('hubby_comments',array('SHOW'=>'1'));
				}
				return false;
			}
			public function disapproveComment($id)
			{
				if($comment	=	$this->getSpeComment($id)) // If comment exist
				{
					return $this->core->db->where('ID',$id)->update('hubby_comments',array('SHOW'=>'0'));
				}
				return false;
			}
			public function deleteComment($id)
			{
				if($comment	=	$this->getSpeComment($id)) // If comment exist
				{
					return $this->core->db->where(array('ID'=>$id))->delete('hubby_comments');
				}
				return false;
			}
			public function updateWidgetSetting($option,$value)
			{
				if($option	==	'CAT')
				{
					$query		=	$this->core->db->get('hubby_news_setting');
					$result		=	$query->result_array();
					if($result)
					{
						return	$this->core->db->update('hubby_news_setting',array(
							'WIDGET_CATEGORY_LIMIT'		=>	$value,
						));	
					}
					else
					{
						return $this->core->db->insert('hubby_news_setting',array(
							'WIDGET_CATEGORY_LIMIT'		=>	$value,
						));	
					}
				}
				else if($option	==	'MOSTREADED')
				{
					$query		=	$this->core->db->get('hubby_news_setting');
					$result		=	$query->result_array();
					if($result)
					{
						return	$this->core->db->update('hubby_news_setting',array(
							'WIDGET_MOSTREADED_LIMIT'		=>	$value,
						));	
					}
					else
					{
						return $this->core->db->insert('hubby_news_setting',array(
							'WIDGET_MOSTREADED_LIMIT'		=>	$value,
						));	
					}
				}
				return false;
			}
		}
	}
	if(class_exists('hubby'))
	{
		class News_smart
		{
			private $data;
			private $hubby;
			private $ci;
			public function __construct($data	=	array())
			{
				$this->core		=	Controller::instance();
				$this->data		=&	$data;
				$this->hubby	=&	$this->core->hubby;
				$this->users	=&	$this->core->users_global;
			}
			public function getCat($start = null,$end = null)
			{
				if($start == null && $end == null)
				{
					$query	=	$this->core->db->get('hubby_news_category');
				}
				else if(is_numeric($start) && !is_numeric($end))
				{
					$query	=	$this->core->db->where('ID',$start)->get('hubby_news_category');
					$ar		=	$query->result_array();
					return $ar[0];
				}
				else
				{
					$query	=	$this->core->db->limit($end,$start)->order_by('ID','desc')->get('hubby_news_category');
				}
				return $query->result_array();
			}
			public function retreiveCat($id)
			{
				$this->core->db			->from('hubby_news_category')
										->where('ID',$id);
				$query					= $this->core->db->get();
				$data					=	$query->result_array();
				if(count($data) == 0)
				{
					return 
					array(
						'name'		=>'Categorie Inconnu',
						'url'		=>'#',
						'desc'		=>''
					);
				}
				else
				{
					return array(
						'name'		=>$data[0]['CATEGORY_NAME'],
						'url'		=>$this->core->url->site_url($this->core->url->controller()).'/category/'.$this->core->hubby->urilizeText($data[0]['CATEGORY_NAME']).'/'.$id,
						'desc'		=>$data[0]['DESCRIPTION']
					);
				}
			}
			public function getNews($start,$end)
			{
				$this->core->db			->from('hubby_news')
										->where('ETAT',1)
										->order_by('DATE','DESC')
										->limit($end,$start);
				$query 					= $this->core->db->get();
				return $query->result_array();
			}
			public function countNews()
			{
				$this->core->db			->where(array('ETAT'=>1));
				$query = $this->core->db	->get('hubby_news');
				return count($query->result_array());
			}
			public function getSpeNews($id)
			{
				$this->core->db	->where(array('ETAT'=>1,'ID'=>$id));
				$query			=	$this->core->db->get('hubby_news');
				return $query->result_array();
			}
			public function countComments($id)
			{
				$option			=	$this->getBlogsterSetting();
				if($option['APPROVEBEFOREPOST'] == 1) // Get only approuved comments
				{
					$this->core->db->where('SHOW',1);
				}
				$this->core->db			->where(array('REF_ART'=>$id));
				$query = $this->core->db	->get('hubby_comments');
				return count($query->result_array());
			}
			public function getComments($id,$start,$end)
			{
				$option			=	$this->getBlogsterSetting();
				if($option['APPROVEBEFOREPOST'] == 1) // Get only approuved comments
				{
					$this->core->db->where('SHOW',1);
				}
				$this->core->db			->where(array('REF_ART'=>$id));
				$query = $this->core->db->limit($end,$start)->get('hubby_comments');
				return $query->result_array();
			}
			public function postComment($id,$content,$auteur,$email)
			{
				if(!$this->users->isConnected())
				{
					$user_id 			=	'0';
				}
				else
				{
					$user_id 			=	$this->users->current('ID');
					$auteur				=	'';
					$email				=	$this->users->current('EMAIL');
				}
				$comment					=	array(
					'REF_ART'				=> 	$id,
					'CONTENT'				=> 	$content,
					'AUTEUR'				=> 	$user_id,
					'OFFLINE_AUTEUR'		=>	$auteur,
					'OFFLINE_AUTEUR_EMAIL'	=>	$email,
					'DATE'					=> 	$this->hubby->timestamp()
				);
				return $this->core->db	->insert('hubby_comments',$comment);
			}
			public function countArtFromCat($catid)
			{
				$this->core->db			->where('ETAT',1)
										->where('CATEGORY_ID',$catid);
				$query = $this->core->db	->get('hubby_news');
				return count($query->result_array());
			}
			public function getArtFromCat($catid,$start = null,$end = null)
			{
				$this->core->db			->where('ETAT',1)
										->where('CATEGORY_ID',$catid);
				if(is_numeric($start) && is_numeric($end))
				{
					$this->core->db->order_by('ID','desc')->limit($end,$start);
				}
				$query = $this->core->db	->get('hubby_news');
				return $query->result_array();
			}
			public function getBlogsterSetting()
			{
				$query	=	$this->core->db->get('hubby_news_setting');
				$result	=	$query->result_array();
				return $result[0];
			}
			public function pushView($arid)
			{
				$art	=	$this->getSpeNews($arid);
				if($art)
				{
					return $this->core->db->where('ID',$arid)->update('hubby_news',array(
						'VIEWED'		=>	(int)$art[0]['VIEWED']+1
					));
				}
				return false;
			}
			public function getMostViewed($start,$end)
			{
				$this->core->db			->from('hubby_news')
										->where('ETAT',1)
										->order_by('VIEWED','DESC')
										->limit($end,$start);
				$query 					= $this->core->db->get();
				return $query->result_array();
			}
		}	
	}
