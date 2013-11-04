<?php
class News_admin_controller
{
	private $moduleData;
	private $data;
	private $news;
	private $news_smart;
	private $hubby_admin;
	private $hubby;
	private $notice;
	public function __construct($data)
	{
		$this->core						=	Controller::instance();
		$this->hubby					=&	$this->core->hubby;
		$this->hubby_admin				=&	$this->core->hubby_admin;
		$this->data						=&	$data;
		$this->notice					=&	$this->core->notice;
		
		$this->moduleData				=	$this->data['module'][0];
		$this->news						=	new News($this->data);
		$this->data['news']				=&	$this->news;
		
		$this->hubby_admin->menuExtendsBefore($this->news->getMenu());
		$this->data['lmenu']			=	$this->core->load->view(VIEWS_DIR.'/admin/left_menu',$this->data,true,TRUE);
	}
	public function index()
	{
		$this->hubby->setTitle('News - Page d\'administration');
			
		$this->data['loadSection']	=	'main';
		$this->data['getNews']		=	$this->news->getNews(0,50);
		$this->data['body']			=	$this->core->load->view(MODULES_DIR.$this->moduleData['ENCRYPTED_DIR'].'/admin_view',$this->data,true,TRUE);
		
		return $this->data['body'];
	}
	public function publish()
	{
		if($this->core->users_global->isSuperAdmin()	|| $this->hubby_admin->adminAccess('modules','publish_news',$this->core->users_global->current('PRIVILEGE')))
		{
			$this->data['categories']	=	$this->news->getCat();
			if(count($this->data['categories']) == 0)
			{
				$this->core->url->redirect(array('admin','open','modules',$this->moduleData['ID'],'category','create?notice=noCategoryCreated'));
			}
			$this->hubby->setTitle('News - Créer un nouvel article');
			$this->core->load->library('form_validation');
			$this->core->form_validation->set_rules('news_name','Intitulé de l\'article','trim|required|min_length[5]|max_length[200]');
			$this->core->form_validation->set_rules('news_content','Contenu de l\'article','trim|required|min_length[5]|max_length[5000]');
			$this->core->form_validation->set_rules('push_directly','Choix de l\'action','trim|required|min_length[1]|max_length[10]');		
			$this->core->form_validation->set_rules('image_link','Lien de l\'image','trim|required|min_length[5]|max_length[1000]');		
			if($this->core->form_validation->run())
			{
				$this->data['result']	=	$this->news->publish_news(
					$this->core->input->post('news_name'),
					$this->core->input->post('news_content'),
					$this->core->input->post('push_directly'),
					$this->core->input->post('image_link'),
					$this->core->input->post('category')
				);
				if($this->data['result'])
				{
					$this->notice->push_notice(notice('done'));
				}
				else
				{
					$this->notice->push_notice(notice('error'));
				}
				
			}
			$this->hubby->loadEditor(3);
			$this->data['loadSection']	=	'publish';
			$this->data['body']			=	$this->core->load->view(MODULES_DIR.$this->moduleData['ENCRYPTED_DIR'].'/admin_view',$this->data,true,TRUE);
			return $this->data['body'];
		}
		else
		{
			$this->core->url->redirect(array('admin','menu?notice=accessDenied'));
		}
	}
	public function edit($e)
	{
		if(!$this->core->users_global->isSuperAdmin()	&& !$this->hubby_admin->adminAccess('modules','edit_news',$this->core->users_global->current('PRIVILEGE')))
		{
			$this->core->url->redirect(array('admin','index?notice=accessDenied'));
		}
		$this->data['categories']	=	$this->news->getCat();
		if(count($this->data['categories']) == 0)
		{
			$this->core->url->redirect(array('admin','open','modules',$this->moduleData['ID'],'category','create?notice=noCategoryCreated'));
		}
		// Control Sended Form Datas
		$this->core->load->library('form_validation');
		$this->core->form_validation->set_rules('news_name','Intitulé de l\'article','trim|required|min_length[5]|max_length[200]');
		$this->core->form_validation->set_rules('news_content','Contenu de l\'article','trim|required|min_length[5]|max_length[5000]');
		$this->core->form_validation->set_rules('push_directly','Choix de l\'action','trim|required|min_length[1]|max_length[1000]');		
		$this->core->form_validation->set_rules('image_link','Lien de l\'image','trim|required|min_length[5]|max_length[1000]');	
		$this->core->form_validation->set_rules('category','Cat&eacute;gorie','trim|required|min_length[1]|max_length[200]');	
		$this->core->form_validation->set_rules('article_id','Identifiant de l\'article','required|min_length[1]');	
		if($this->core->form_validation->run())
		{
			$this->data['result']	=	$this->news->edit(
				$this->core->input->post('article_id'),
				$this->core->input->post('news_name'),
				$this->core->input->post('news_content'),
				$this->core->input->post('push_directly'),
				$this->core->input->post('image_link'),
				$this->core->input->post('category')
			);
			if($this->data['result'])
			{
				$this->notice->push_notice(notice('done'));
			}
			else
			{
				$this->notice->push_notice(notice('error'));
			}
		}
		// Retreiving News Data
		$this->data['news']		=	$this->news->getSpeNews($e);
		$this->hubby->setTitle('News - Créer un nouvel article');
		$this->hubby->loadEditor(3);
		
		$this->data['loadSection']	=	'edit';
		$this->data['body']			=	$this->core->load->view(MODULES_DIR.$this->moduleData['ENCRYPTED_DIR'].'/admin_view',$this->data,true,TRUE);
		return $this->data['body'];
	}
	public function category($e = '',$i = null)
	{
		if(!$this->core->users_global->isSuperAdmin()	&& !$this->hubby_admin->adminAccess('modules','category_manage',$this->core->users_global->current('PRIVILEGE')))
		{
			$this->core->url->redirect(array('admin','index?notice=accessDenied'));
		}
		if($e == '')
		{
			$this->data['ttCat']		=	$this->news->countCat();
			$this->data['getCat']		=	$this->news->getCat(0,100);
			$this->hubby->setTitle('News - Gestion des cat&eacute;gories');
			$this->hubby->loadEditor(2);
			
			$this->data['loadSection']	=	'category';
			$this->data['body']			=	$this->core->load->view(MODULES_DIR.$this->moduleData['ENCRYPTED_DIR'].'/admin_view',$this->data,true,TRUE);
			return $this->data['body'];
		}
		else if($e == 'create')
		{
			$this->core->load->library('form_validation');
			$this->core->form_validation->set_rules('cat_name','Nom de la cat&eacute;gorie','required|min_length[3]|max_length[50]');
			$this->core->form_validation->set_rules('cat_description','Description de la cat&eacute;gorie','required|min_length[3]|max_length[200]');
			if($this->core->form_validation->run())
			{
				$this->data['notice']	=	$this->news->createCat(
					$this->core->input->post('cat_name'),
					$this->core->input->post('cat_description')
				);
				$this->notice->push_notice(notice($this->data['notice']));
			}
			$this->notice->push_notice(validation_errors('<p class="error">','</p>'));
			$this->hubby->setTitle('News - Cr&eacute;e une categorie');
			$this->hubby->loadEditor(2);
			
			$this->data['loadSection']	=	'create_cat';
			$this->data['body']			=	$this->core->load->view(MODULES_DIR.$this->moduleData['ENCRYPTED_DIR'].'/admin_view',$this->data,true,TRUE);
			return $this->data['body'];
		}
		else if($e == 'manage' && $i != null)
		{
			$this->core->load->library('form_validation');
			if($this->core->input->post('allower') == 'ALLOWEDITCAT')
			{
				$this->core->form_validation->set_rules('cat_name','Nom de la cat&eacute;gorie','required|min_length[3]|max_length[50]');
				$this->core->form_validation->set_rules('cat_description','Description de la cat&eacute;gorie','required|min_length[3]|max_length[200]');
				if($this->core->form_validation->run())
				{
					$this->data['notice']	=	$this->news->editCat(
						$this->core->input->post('cat_id'),
						$this->core->input->post('cat_name'),
						$this->core->input->post('cat_description')
					);
					$this->notice->push_notice(notice($this->data['notice']));
				}
			}
			else if($this->core->input->post('allower') == 'ALLOWCATDELETION')
			{
				$this->core->form_validation->set_rules('cat_id_for_deletion','Identifiant de la cat&eacute;gorie','required|min_length[1]');
				if($this->core->form_validation->run())
				{
					$this->data['notice']	=	$this->news->deleteCat(
						$this->core->input->post('cat_id_for_deletion')
					);
					if($this->data['notice']	==	'CatDeleted')
					{
						$this->core->url->redirect(array('admin','open','modules',$this->moduleData['ID'],'category?notice='.$this->data['notice']));
					}
					$this->notice->push_notice(notice($this->data['notice']));
				}
			}
			$this->data['cat']			=	$this->news->retreiveCat($i);
			$this->data['loadSection']	=	'manage_cat';
			$this->data['body']			=	$this->core->load->view(MODULES_DIR.$this->moduleData['ENCRYPTED_DIR'].'/admin_view',$this->data,true,TRUE);
			return $this->data['body'];
		}
	}
	public function delete($se)
	{
		if(!$this->core->users_global->isSuperAdmin()	&& !$this->hubby_admin->adminAccess('modules','delete_news',$this->core->users_global->current('PRIVILEGE')))
		{
			$this->data['loadSection']	=	'delete_news';
			$this->data['delete']	=	false;
			$this->data['body']		=	$this->core->load->view(MODULES_DIR.$this->moduleData['ENCRYPTED_DIR'].'/admin_view',$this->data,true,TRUE);
			return array(
				'RETURNED'	=>	$this->data['body'],
				'MCO'		=>	TRUE
			);
		}
		$this->data['delete']		=	$this->news->deleteSpeNews((int)$se);
		$this->data['loadSection']	=	'delete_news';
		$this->data['body']			=	$this->core->load->view(MODULES_DIR.$this->moduleData['ENCRYPTED_DIR'].'/admin_view',$this->data,true,TRUE);
		return array(
			'RETURNED'	=>	$this->data['body'],
			'MCO'		=>	TRUE
		);
	}
}
