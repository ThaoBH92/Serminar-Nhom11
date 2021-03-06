<?php

class erLhcoreClassModelChat {

   public function getState()
   {
       return array(
               'id'              		=> $this->id,
               'nick'            		=> $this->nick,
               'status'          		=> $this->status,
               'status_sub'          	=> $this->status_sub,
               'time'            		=> $this->time,
               'user_id'         		=> $this->user_id,
               'hash'            		=> $this->hash,
               'ip'              		=> $this->ip,
               'referrer'        		=> $this->referrer,
               'dep_id'          		=> $this->dep_id,
               'email'           		=> $this->email,
               'user_status'     		=> $this->user_status,
               'support_informed'		=> $this->support_informed,
               'country_code'    		=> $this->country_code,
               'country_name'    		=> $this->country_name,
               'user_typing'     		=> $this->user_typing,
               'user_typing_txt'     	=> $this->user_typing_txt,
               'operator_typing' 		=> $this->operator_typing,
               'operator_typing_id' 	=> $this->operator_typing_id,
               'phone'           		=> $this->phone,
               'has_unread_messages'    => $this->has_unread_messages,
               'last_user_msg_time'     => $this->last_user_msg_time,
               'last_msg_id'     		=> $this->last_msg_id,
               'mail_send'     			=> $this->mail_send,
               'lat'     				=> $this->lat,
               'lon'     				=> $this->lon,
               'city'     				=> $this->city,
               'additional_data'     	=> $this->additional_data,
               'session_referrer'     	=> $this->session_referrer,
               'wait_time'     			=> $this->wait_time,
               'chat_duration'     		=> $this->chat_duration,
               'chat_variables'     	=> $this->chat_variables,
               'priority'     			=> $this->priority,
               'chat_initiator'     	=> $this->chat_initiator,
               'user_tz_identifier'     => $this->user_tz_identifier,

       		   'online_user_id'     	=> $this->online_user_id,
       		   'unread_messages_informed' => $this->unread_messages_informed,
       		   'reinform_timeout'     	=> $this->reinform_timeout,

       		   // Wait timeout attribute
               'wait_timeout'     		=> $this->wait_timeout,
               'wait_timeout_send'     	=> $this->wait_timeout_send,
               'timeout_message'     	=> $this->timeout_message,

       		    // Transfer workflow attributes
               'transfer_timeout_ts'    => $this->transfer_timeout_ts,
               'transfer_if_na'    		=> $this->transfer_if_na,
               'transfer_timeout_ac'    => $this->transfer_timeout_ac,

       			// Callback status
               'na_cb_executed'    		=> $this->na_cb_executed,
               'fbst'    				=> $this->fbst,
               'nc_cb_executed'    		=> $this->nc_cb_executed,
       		
       		    //
               'remarks'    			=> $this->remarks,
       		   // What operation is pending visitor?
               'operation'    			=> $this->operation,
       		
       		   // What operation is pending operator?
               'operation_admin'    	=> $this->operation_admin,
       		
       		   // Screenshot ID? maps to file
               'screenshot_id'    		=> $this->screenshot_id,
       		
               'tslasign'    			=> $this->tslasign,
       );
   }

   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }

   public function removeThis()
   {
	   	$q = ezcDbInstance::get()->createDeleteQuery();

	   	// Messages
	   	$q->deleteFrom( 'lh_msg' )->where( $q->expr->eq( 'chat_id', $this->id ) );
	   	$stmt = $q->prepare();
	   	$stmt->execute();

	   	// Transfered chats
	   	$q->deleteFrom( 'lh_transfer' )->where( $q->expr->eq( 'chat_id', $this->id ) );
	   	$stmt = $q->prepare();
	   	$stmt->execute();

	   	// Delete user footprint
	   	$q->deleteFrom( 'lh_chat_online_user_footprint' )->where( $q->expr->eq( 'chat_id', $this->id ) );
	   	$stmt = $q->prepare();
	   	$stmt->execute();

	   	erLhcoreClassModelChatFile::deleteByChatId($this->id);

	   	erLhcoreClassChat::getSession()->delete($this);
	   	
	   	erLhcoreClassChat::updateActiveChats($this->user_id);
   }

   public static function fetch($chat_id) {
       	 $chat = erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChat', (int)$chat_id );
       	 return $chat;
   }

   public function saveThis() {
       	 erLhcoreClassChat::getSession()->saveOrUpdate($this);
   }

   public function updateThis() {
       	 erLhcoreClassChat::getSession()->update($this,$this->updateIgnoreColumns);
   }

   public function setIP()
   {
       $this->ip = erLhcoreClassIPDetect::getIP();
   }

   public function getChatOwner()
   {
       try {
           $user = erLhcoreClassUser::getSession()->load('erLhcoreClassModelUser', $this->user_id);
           return $user;
       } catch (Exception $e) {
           return false;
       }
   }

   public function __get($var) {

       switch ($var) {

       	case 'time_created_front':
       			$this->time_created_front = date('Ymd') == date('Ymd',$this->time) ? date(erLhcoreClassModule::$dateHourFormat,$this->time) : date(erLhcoreClassModule::$dateDateHourFormat,$this->time);
       			return $this->time_created_front;
       		break;
       	
       	case 'is_operator_typing':
       		   $this->is_operator_typing = $this->operator_typing > (time()-10); // typing is considered if status did not changed for 30 seconds
       		   return $this->is_operator_typing;
       		break;

       	case 'is_user_typing':
       		   $this->is_user_typing = $this->user_typing > (time()-10); // typing is considered if status did not changed for 30 seconds
       		   return $this->is_user_typing;
       		break;

       	case 'wait_time_front':
       		   $this->wait_time_front = erLhcoreClassChat::formatSeconds($this->wait_time);
       		   return $this->wait_time_front;
       		break;

       	case 'chat_duration_front':
       		   $this->chat_duration_front = erLhcoreClassChat::formatSeconds($this->chat_duration);
       		   return $this->chat_duration_front;
       		break;

       	case 'user_name':
       			return $this->user_name = (string)$this->user;
       		break;	
       		
       	case 'user':
       		   $this->user = false;
       		   if ($this->user_id > 0) {
       		   		try {
       		   			$this->user = erLhcoreClassModelUser::fetch($this->user_id,true);
       		   		} catch (Exception $e) {
       		   			$this->user = false;
       		   		}
       		   }
       		   return $this->user;
       		break;
       		
       	case 'operator_typing_user':
       		   $this->operator_typing_user = false;
       		   if ($this->operator_typing_id > 0) {
       		   		try {
       		   			$this->operator_typing_user = erLhcoreClassModelUser::fetch($this->operator_typing_id);
       		   		} catch (Exception $e) {
       		   			$this->operator_typing_user = false;
       		   		}
       		   }
       		   return $this->operator_typing_user;
       		break;

       	case 'online_user':
       			$this->online_user = false;
       			if ($this->online_user_id > 0){
       				try {
       					$this->online_user = erLhcoreClassModelChatOnlineUser::fetch($this->online_user_id);
       				} catch (Exception $e) {
       					$this->online_user = false;
       				}
       			}
       			return $this->online_user;
       		break;

       	case 'department':
       			$this->department = false;
       			if ($this->dep_id > 0) {
       				try {
       					$this->department = erLhcoreClassModelDepartament::fetch($this->dep_id,true);
       				} catch (Exception $e) {

       				}
       			}

       			return $this->department;
       		break;

       	case 'department_name':
       			return $this->department_name = (string)$this->department;
       		break;
       		
       	case 'screenshot':
       			$this->screenshot = false;
       			if ($this->screenshot_id > 0) {
       				try {
       					$this->screenshot = erLhcoreClassModelChatFile::fetch($this->screenshot_id);
       				} catch (Exception $e) {
       			
       				}
       			}
       			
       			return $this->screenshot;
       		break;	
       		
       	case 'unread_time':
       		
	       		$diff = time()-$this->last_user_msg_time;
	       		$hours = floor($diff/3600);
	       		$minits = floor(($diff - ($hours * 3600))/60);
	       		$seconds = ($diff - ($hours * 3600) - ($minits * 60));
	       		
       			$this->unread_time = array(
       				'hours' => $hours,
       				'minits' => $minits,
       				'seconds' => $seconds,
       			); 
       			 
       			return $this->unread_time;
       		break;
       		
       	case 'user_tz_identifier_time':
       			$date = new DateTime(null, new DateTimeZone($this->user_tz_identifier));
       			$this->user_tz_identifier_time = $date->format(erLhcoreClassModule::$dateHourFormat);       			
       			return $this->user_tz_identifier_time;
       		break;
       		
       	case 'additional_data_array':
       			$jsonData = json_decode($this->additional_data);
       			if ($jsonData !== null) {
       				$this->additional_data_array = $jsonData;
       			} else {
       				$this->additional_data_array = $this->additional_data;
       			}
       			return $this->additional_data_array;
       		break;
       			
       	default:
       		break;
       }

   }

   public static function detectLocation(erLhcoreClassModelChat & $instance)
   {
       $geoData = erLhcoreClassModelChatConfig::fetch('geo_data');
       $geo_data = (array)$geoData->data;

       if (isset($geo_data['geo_detection_enabled']) && $geo_data['geo_detection_enabled'] == 1) {

           $params = array();

           if ($geo_data['geo_service_identifier'] == 'mod_geoip2'){
               $params['country_code'] = $geo_data['mod_geo_ip_country_code'];
               $params['country_name'] = $geo_data['mod_geo_ip_country_name'];
               $params['mod_geo_ip_city_name'] = $geo_data['mod_geo_ip_city_name'];
               $params['mod_geo_ip_latitude'] = $geo_data['mod_geo_ip_latitude'];
               $params['mod_geo_ip_longitude'] = $geo_data['mod_geo_ip_longitude'];
           } elseif ($geo_data['geo_service_identifier'] == 'locatorhq') {
               $params['username'] = $geo_data['locatorhqusername'];
               $params['api_key'] = $geo_data['locatorhq_api_key'];
           } elseif ($geo_data['geo_service_identifier'] == 'ipinfodbcom') {             
               $params['api_key'] = $geo_data['ipinfodbcom_api_key'];
           } elseif ($geo_data['geo_service_identifier'] == 'max_mind') {             
               $params['detection_type'] = $geo_data['max_mind_detection_type'];         
               $params['city_file'] = isset($geo_data['max_mind_city_location']) ? $geo_data['max_mind_city_location'] : '';
           }

           $location = erLhcoreClassModelChatOnlineUser::getUserData($geo_data['geo_service_identifier'],$instance->ip,$params);

           if ($location !== false){
               $instance->country_code = $location->country_code;
               $instance->country_name = $location->country_name;
               $instance->lat = $location->lat;
               $instance->lon = $location->lon;
               $instance->city = $location->city;
           }
       }
   }

   public function blockUser() {

       if (erLhcoreClassModelChatBlockedUser::getCount(array('filter' => array('ip' => $this->ip))) == 0)
       {
           $block = new erLhcoreClassModelChatBlockedUser();
           $block->ip = $this->ip;
           $block->user_id = erLhcoreClassUser::instance()->getUserID();
           $block->saveThis();
       }
   }

   const STATUS_PENDING_CHAT = 0;
   const STATUS_ACTIVE_CHAT = 1;
   const STATUS_CLOSED_CHAT = 2;
   const STATUS_CHATBOX_CHAT = 3;
   const STATUS_OPERATORS_CHAT = 4;

   const CHAT_INITIATOR_DEFAULT = 0;
   const CHAT_INITIATOR_PROACTIVE = 1;

   const STATUS_SUB_DEFAULT = 0;
   const STATUS_SUB_OWNER_CHANGED = 1;
   const STATUS_SUB_CONTACT_FORM = 2;
   
   const USER_STATUS_JOINED_CHAT = 0;
   const USER_STATUS_CLOSED_CHAT = 1;
   const USER_STATUS_PENDING_REOPEN = 2;
   
   
   
   public $id = null;
   public $nick = '';
   public $status = self::STATUS_PENDING_CHAT;
   public $status_sub = self::STATUS_SUB_DEFAULT;
   public $time = '';
   public $user_id = '';
   public $hash = '';
   public $ip = '';
   public $referrer = '';
   public $dep_id = '';
   public $email = '';
   public $user_status = self::USER_STATUS_JOINED_CHAT;
   public $support_informed = '';
   public $country_code = '';
   public $country_name = '';
   public $phone = '';
   public $user_typing = 0;
   public $user_typing_txt = '';
   public $operator_typing = 0;
   public $has_unread_messages = 0;
   public $last_user_msg_time = 0;
   public $last_msg_id = 0;
   public $mail_send = 0;
   public $lat = 0;
   public $lon = 0;
   public $city = '';
   public $additional_data = '';
   public $session_referrer = '';
   public $wait_time = 0;
   public $chat_duration = 0;
   public $priority = 0;
   public $online_user_id = 0;

   // Transfer attributes
   public $transfer_if_na = 0;
   public $transfer_timeout_ts = 0;
   public $transfer_timeout_ac = 0;

   // Wait timeout attributes
   public $wait_timeout = 0;
   public $wait_timeout_send = 0;
   public $timeout_message = '';
   
   // User timezone identifier
   public $user_tz_identifier = '';

   // Unanswered chat callback executed
   public $na_cb_executed = 0;
   
   // New chat callback executed
   public $nc_cb_executed = 0;

   // Feedback status
   public $fbst = 0;
   
   // What operator is typing now.
   public $operator_typing_id = 0;

   public $chat_initiator = self::CHAT_INITIATOR_DEFAULT;
   public $chat_variables = '';
   
   public $remarks = '';
   
   // Pending operations from user side
   public $operation = '';
   
   public $operation_admin = '';
   
   public $screenshot_id = 0;
   
   public $unread_messages_informed = 0;
   public $reinform_timeout = 0;
   
   // Time since last assignment
   public $tslasign = 0;
   
   public $updateIgnoreColumns = array();
}

?>