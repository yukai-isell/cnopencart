<?php
class ControllerTotalMinorder extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('total/minorder');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_total'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('total/minorder', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->load->model('setting/setting');
		$minorder_array = explode(',',$this->config->get('minorder_count')) ;
		$this->data['minorders'] = array();
		foreach ($minorder_array as $count) {
			$min_order=$this->model_setting_setting->getSetting('min_order_'.$count);
			if($min_order)
			$this->data['minorders'] []=array(
				'customer_groups'  => explode(',',$min_order['minorder_customer_groups']),
				'amount'  => $min_order['minorder_min'],
				'type'  => $min_order['minorder_type'],
				'status'  => $min_order['minorder_status'],
				'edit'  => $this->url->link('total/minorder/update', 'token=' . $this->session->data['token'].'&count='.$count, 'SSL'),
				'delete'  => $this->url->link('total/minorder/delete', 'token=' . $this->session->data['token'].'&count='.$count, 'SSL')
			);
		}
		$data = array(
			'order' => 'DESC'
		);
		$this->load->model('sale/customer_group');
		$results =$this->model_sale_customer_group->getCustomerGroups($data);

		$this->data['customer_groups'] = array();
		foreach ($results as $result) {
			$this->data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : NULL)
			);
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_item'] = $this->language->get('text_item');
		$this->data['insert'] = $this->url->link('total/minorder/update', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['text_status'] = $this->language->get('text_status');
		$this->data['text_type'] = $this->language->get('text_type');
		$this->data['text_amount'] = $this->language->get('text_amount');
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['text_last_min_total'] = $this->language->get('text_last_min_total');
		$this->data['text_action'] = $this->language->get('text_action');
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_delete'] = $this->language->get('text_delete');


		$this->template = 'total/minorder_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function update() {
		$this->load->language('total/minorder');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			if(isset($this->request->post['minorder_categories']))
				$this->request->post['minorder_categories']= implode(',',$this->request->post['minorder_categories']);
			else
			   $this->request->post['minorder_categories']='';

			if(isset($this->request->post['minorder_customer_groups']))
				$this->request->post['minorder_customer_groups']= implode(',',$this->request->post['minorder_customer_groups']);
			else
			   $this->request->post['minorder_customer_groups']='';

			$minorder_count_array = explode(',',$this->config->get('minorder_count')) ;

			$minorder_count = $this->request->post['minorder_count'];
			if($this->config->get('minorder_count')!=''){
				if(in_array($minorder_count,$minorder_count_array))
					$minorder_count=$this->config->get('minorder_count');
				else
					$minorder_count=$this->config->get('minorder_count').','.$minorder_count;
			}else{
				$minorder_count=1;
			}

			$min_order_count = array(
       			'minorder_count'      => $minorder_count
   			);
   			$count=$this->request->post['minorder_count'];
			unset($this->request->post['minorder_count']);
			$this->model_setting_setting->editSetting('minorder_count',$min_order_count);

			$this->model_setting_setting->editSetting('min_order_'.$count, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect( $this->url->link('total/minorder', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_item'] = $this->language->get('text_item');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_last_min_total'] = $this->language->get('entry_last_min_total');
		$this->data['entry_min_total'] = $this->language->get('entry_min_total');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_min_total_dec'] = $this->language->get('entry_min_total_dec');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_total'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('total/minorder', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('total/minorder/update', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('total/minorder', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('catalog/category');

		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		$data = array(
			'order' => 'DESC'
		);

		$this->load->model('sale/customer_group');
		$results =$this->model_sale_customer_group->getCustomerGroups($data);

		$this->data['customer_groups'] = array();
		foreach ($results as $result) {
			$this->data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : NULL)
			);
		}

		if (isset($this->request->get['count'])) {
			$minorder_count = $this->request->get['count'];
		} else {
			$minorder_count = explode(',', $this->config->get('minorder_count'));
		//	echo '###########'. max($minorder_count)+1;
			$minorder_count=max($minorder_count)+1;
		}
		$this->data['minorder_count']=$minorder_count;

		$min_order=$this->model_setting_setting->getSetting('min_order_'.$minorder_count);

		if(isset($this->request->post['minorder_customer_groups'])) {
			$this->data['minorder_customer_group'] = $this->request->post['minorder_customer_groups'];
		} elseif(isset($min_order['minorder_customer_groups'])) {
			$this->data['minorder_customer_group'] = explode(',',$min_order['minorder_customer_groups']) ;
		}else{
			$this->data['minorder_customer_group']=array();
		}

		if(isset($this->request->post['minorder_categories'])) {
			$this->data['minorder_category'] = $this->request->post['minorder_categories'];
		} elseif(isset($min_order['minorder_categories'])) {
			$this->data['minorder_category'] = explode(',',$min_order['minorder_categories']) ;
		}else{
			$this->data['minorder_category'] = array() ;
		}

		if(isset($this->request->post['minorder_status'])) {
			$this->data['minorder_status'] = $this->request->post['minorder_status'];
		} elseif(isset($min_order['minorder_status'])) {
			$this->data['minorder_status'] = $min_order['minorder_status'];
		}else{
			$this->data['minorder_status'] = 1;
		}

		if (isset($this->request->post['minorder_type'])) {
			$this->data['minorder_type'] = $this->request->post['minorder_type'];
		} elseif(isset($min_order['minorder_type'])) {
			$this->data['minorder_type'] = $min_order['minorder_type'];
		}else{
			$this->data['minorder_type'] = 1;
		}

		if (isset($this->request->post['minorder_min'])) {
				$this->data['minorder_min'] = $this->request->post['minorder_min'];
			if($this->data['minorder_type']==0)
				$this->data['minorder_min_str'] = $this->currency->format($this->request->post['minorder_min']);
			else
				$this->data['minorder_min_str'] = $this->request->post['minorder_min'];
		} else {
			if(isset($min_order['minorder_min'])){
				$this->data['minorder_min'] = $min_order['minorder_min'];
			}else{
				$this->data['minorder_min']='';
			}

			if($this->data['minorder_type']==0)
				$this->data['minorder_min_str'] = $this->currency->format($this->data['minorder_min']);
			else
				$this->data['minorder_min_str'] = $this->data['minorder_min'];
		}

		if (isset($this->request->post['minorder_sort_order'])) {
			$this->data['minorder_sort_order'] = $this->request->post['minorder_sort_order'];
		} elseif(isset($min_order['minorder_sort_order'])) {
			$this->data['minorder_sort_order'] = $min_order['minorder_sort_order'];
		}else{
			$this->data['minorder_sort_order'] ='';
		}

		$this->template = 'total/minorder.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}


	public function delete() {
		$this->load->model('setting/setting');
		if (($this->validate())) {
			if (isset($this->request->get['count'])) {
				$count = $this->request->get['count'];
				$minorder_count = explode(',', $this->config->get('minorder_count'));
				$k = array_search($count,$minorder_count);
				unset($minorder_count[$k]);
				//	$this->model_setting_setting->editSetting('minorder_count',implode(',',$minorder_count));
				$this->model_setting_setting->deleteSetting('min_order_'.$count);
				$min_order_count = array(
       				'minorder_count'      => implode(',',$minorder_count)
   				);
				$this->model_setting_setting->editSetting('minorder_count',$min_order_count);
			}
		}
		$this->redirect( $this->url->link('total/minorder', 'token=' . $this->session->data['token'], 'SSL'));
	}


	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/minorder')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>