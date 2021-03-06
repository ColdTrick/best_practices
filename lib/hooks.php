<?php

	function best_practices_public_pages_hook($hook, $type, $returnvalue, $params) {
		$result = $returnvalue;
		
		$result[] = "best_practice/icon/.*";
		
		return $result;
	}
	
	function best_practices_register_menu_owner_block_hook($hook, $type, $returnvalue, $params) {
		$result = $returnvalue;
		
		if (!empty($params) && is_array($params)) {
			$entity = elgg_extract("entity", $params);
			
			if (elgg_instanceof($entity, "user")) {
				$result[] = ElggMenuItem::factory(array(
					"name" => "best_practices",
					"text" => elgg_echo("best_practices:menu:owner_block:user"),
					"href" => "best_practice/owner/" . $entity->username,
					"is_trusted" => true
				));
			} elseif (elgg_instanceof($entity, "group")) {
				// group menu item depends on admin settings
				if (!best_practices_use_predefined_groups() || (($group_guids = best_practices_get_predefined_group_guids()) && in_array($entity->getGUID(), $group_guids))) {
					$result[] = ElggMenuItem::factory(array(
						"name" => "best_practices",
						"text" => elgg_echo("best_practices:menu:owner_block:group"),
						"href" => "best_practice/group/" . $entity->getGUID() . "/all",
						"is_trusted" => true
					));
				}
			}
		}
		
		return $result;
	}
	
	function best_practices_widget_url_hook($hook, $type, $returnvalue, $params) {
		$result = $returnvalue;
		
		if (empty($result) && !empty($params) && is_array($params)) {
			$widget = elgg_extract("entity", $params);
			
			if ($widget->handler == "best_practices") {
				switch ($widget->context) {
					case "groups":
						$result = "best_practice/group/" . $widget->getOwnerGUID() . "/all";
						break;
				}
			}
		}
		
		return $result;
	}
	
	function best_practices_setting_hook($hook, $type, $returnvalue, $params) {
		$result = $returnvalue;
		
		if (!empty($params) && is_array($params)) {
			$plugin = elgg_extract("plugin", $params);
			$name = elgg_extract("name", $params);
			$value = elgg_extract("value", $params);
			
			if (($plugin->getID() == "best_practices") && ($name == "group_guids") && !empty($value)) {
				$result = implode(",", $value);
			}
		}
		
		return $result;
	}
	