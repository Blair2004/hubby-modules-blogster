<?php
/// -------------------------------------------------------------------------------------------------------------------///
global $NOTICE_SUPER_ARRAY;
/// -------------------------------------------------------------------------------------------------------------------///
$or['categoryCreated']			=	'<span class="success">La cat&eacute;gorie &agrave; &eacute;t&eacute; correctement cr&eacute;e</span>';
$or['categoryAldreadyCreated']	=	'<span class="error">Cette cat&eacute;gorie existe d&eacute;j&agrave;</span>';
$or['unknowCat']				=	'<span class="error">Cette cat&eacute;gorie est inexistante</span>';
$or['categoryUpdated']			=	'<span class="success">La mise &agrave; jour &agrave; r&eacute;ussie</span>';
$or['CatDeleted']				=	'<span class="success">La cat&eacute;gorie &agrave; &eacute;t&eacute; supprim&eacute; avec succ&egrave;s</span>';
$or['CatNotEmpty']				=	'<span class="error">Cette cat&eacute;gorie ne peut pas &ecirc;tre supprim&eacute;e, car il existe des publications qui y sont rattach&eacute;es. Changez la cat&eacute;gorie de ces publications avant de supprimer cette cat&eacute;gorie.</span>';
$or['noCategoryCreated']		=	'<span class="error"><i class="icon-warning"></i> Avant de publier un article, vous devez cr&eacute;er une cat&eacute;gorie.</span>';

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
				return $query->result_array();
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
						'url'		=>$this->core->url->controller().'/category/'.$id,
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
				$this->core->db			->where(array('REF_ART'=>$id));
				$query = $this->core->db	->get('hubby_comments');
				return count($query->result_array());
			}
			public function getComments($id,$start,$end)
			{
				$this->core->db			->where(array('REF_ART'=>$id));
				$query = $this->core->db->limit($end,$start)->get('hubby_comments');
				return $query->result_array();
			}
			public function postComment($id,$content)
			{
				if(!$this->users->isConnected())
				{
					$user_id 			=	'0';
				}
				else
				{
					$user_id 			=	$this->users->current('ID');
				}
				$comment				=	array(
					'REF_ART'			=> $id,
					'CONTENT'			=> $content,
					'AUTEUR'			=> $user_id,
					'DATE'				=> $this->hubby->timestamp()
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
				if(is_finite($start) && is_finite($end))
				{
					$this->core->db->order_by('ID','desc')->limit($end,$start);
				}
				$query = $this->core->db	->get('hubby_news');
				return $query->result_array();
			}
		}	
	}
