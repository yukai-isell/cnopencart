<?php
class ModelToolMinOrder extends Model {
	public function checkOvermin() {
		if($this->customer->isLogged()){
			$customer_group_id=$this->customer->getCustomerGroupId();
		}else{
			$customer_group_id=$this->config->get('config_customer_group_id');
		}

		$item_count=$this->checkMinItem($customer_group_id);
		$item_total=$this->checkMinTotal($customer_group_id);
		$return[]=array();


		if($item_count>0&&$item_count!='false'){
			$return=array(
					'amount'  => $item_count,
					'type'  => 1,
				);
			return $return;
		}elseif($item_total>0&&$item_total!='false'){
			$return=array(
					'amount'  => $item_total,
					'type'  => 0,
				);
			return $return;
		}else{
			return true;
		}


	}

	private function checkMinItem($customer_group_id) {
		$count = array();
		$minorders=$this->getCondition();
		$item_count=0;
		$min_item_count=0;
		$item_count_other=0;
		$min = array();
		$product_id = array();
		foreach ($minorders as $minorder) {

			if(in_array($customer_group_id,$minorder['customer_groups'])&&$minorder['type']==1){
				$min[]=array(
						'amount'  => $minorder['amount']
				);
				// check if the product in the categoried set in the admin panel
				if(isset($minorder['minorder_categories'])&&$minorder['minorder_categories']!=''){
					$query = $this->db->query(" SELECT DISTINCT product_id  FROM " . DB_PREFIX . "product_to_category WHERE category_id in (".$minorder['minorder_categories'].")");
					foreach ($query->rows as $row) {
						array_push($product_id,$row['product_id']);
					}

					foreach ($this->cart->getProducts() as $product) {
						if (in_array($product['product_id'],$product_id)) {
							$item_count+=$product['quantity'];
						}else{
							$item_count_other+=$product['quantity'];
					 	 }
					}
				}else{
						continue;
				}
			}else{
					continue;
			}
		}

		if(count($min)>0){
			$val=min($min);
			$min_item_count=$val['amount'];
		}
		if($item_count_other==0){
			if($item_count>=$min_item_count)
				return 0;
			else
				return $min_item_count;
		}else if($item_count==0){
			return 0;
		}else if($item_count_other>0 && $item_count>0){
			$item_count+=$item_count_other;
			if($item_count>=$min_item_count)
				return 0;
			else
				return $min_item_count;
		}else{
			return 0;
		}
	}

  	private function checkMinTotal($customer_group_id) {
		$count = array();
		$product_id = array();
		$minorders=$this->getCondition();
		$total=0;
		$total_other=0;
		$min_total=0;
		$min = array();
	//	print_r($minorders);
		foreach ($minorders as $minorder) {

			if(in_array($customer_group_id,$minorder['customer_groups'])&&$minorder['type']==0){

				$min[]=array(
					'amount'  => $minorder['amount']
				);
				// check if the product in the categoried set in the admin panel
				if(isset($minorder['minorder_categories'])&&$minorder['minorder_categories']!=''){
					$query = $this->db->query(" SELECT DISTINCT product_id  FROM " . DB_PREFIX . "product_to_category WHERE category_id in (".$minorder['minorder_categories'].")");
					foreach ($query->rows as $row) {
						array_push($product_id,$row['product_id']);
					}

					foreach ($this->cart->getProducts() as $product) {
					if (in_array($product['product_id'],$product_id)) {
							$total+=$product['total'];
						}else{
							$total_other+=$product['total'];
						}
					}
				
				}else{
					continue;
				}
			}else{
				continue;
			}
		}

		if(count($min)>0){
			$val=min($min);
			$min_total=$val['amount'];

		}
		if($total_other==0){
			if($total>=$min_total)
				return 0;
			else
				return $min_total;
		}else if($total==0){
			return 0;
		}else if($total>0 && $total_other>0){
			$total+=$total_other;
			if($total>=$min_total)
				return 0;
			else
				return $min_total;
		}else{
			return 0;
		}
	}

	private function getCondition() {
		$minorder_array = explode(',',$this->config->get('minorder_count')) ;
		$minorders = array();

		foreach ($minorder_array as $count) {
			//echo '############'.$count;
			$min_order=$this->getSetting('min_order_'.$count);
			if(isset($min_order['minorder_status'])&&$min_order['minorder_status']==1){
				$minorders[]=array(
					'customer_groups'  => explode(',',$min_order['minorder_customer_groups']),
					'minorder_categories'  => $min_order['minorder_categories'],
					'amount'  => $min_order['minorder_min'],
					'type'  =>  $min_order['minorder_type'],
					'status'  => $min_order['minorder_status']
				);
			}
		}
		return $minorders;
	}

	private function getSetting($group, $store_id = 0) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");

		foreach ($query->rows as $result) {
			$data[$result['key']] = $result['value'];
		}

		return $data;
	}
}
?>