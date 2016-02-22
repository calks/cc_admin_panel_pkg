<?php

	class adminPanelPkgCommonRoutingRule {
		
        public function seoToInternal(URL $seo_url) {        	
            $address = $seo_url->getAddress();
            
            $address_parts = explode('/', $address);
            $first_part = @array_shift($address_parts);

            
            if ($first_part=='admin') {
            	$seo_url->setAddress(implode('/', $address_parts));
            	return $seo_url;	
            }
            
            return false;
        }


        public function internalToSeo(URL $internal_url) {        	
        	$address = trim($internal_url->getAddress(), ' /');
        	        	
        	$address_parts = explode('/', $address);
        	$first_part = isset($address_parts[0]) ? $address_parts[0] : ''; 
        	$is_inside_var_or_temp = in_array($first_part, array('var', 'temp'));
        	
        	if ($is_inside_var_or_temp) {
        		$new_address = $address;
        	}
        	else {
        		$new_address = $address ? "admin/$address" : "admin";        		
        	}
        	 
        	$internal_url->setAddress($new_address);
            return $internal_url;
        }        
        
		
	}