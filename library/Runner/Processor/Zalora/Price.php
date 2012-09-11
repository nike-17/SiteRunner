<?php

class Runner_Processor_Zalora_Price extends Runner_Processor_Xpath {

	public function is_price_interval($data, $start, $end){

		$result = $this->getSpanContentByProperty($data, 'gr:hasCurrencyValue');
		if(!$result){
			return true;
		} else {
			$price = floatval($result);
			return ($start <= $price && $price <= $end);
		}
	}

}