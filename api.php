<?php
	
	$api_key = 'DK2H30D27C';

	if (isset($_REQUEST['api_key'])) {
		if ($_REQUEST['api_key'] == $api_key) {
			if (isset($_REQUEST['phone'])) {
				require 'Chargebee/lib/ChargeBee.php';

				ChargeBee_Environment::configure("vatoday","live_4UIhnNWkhlUunF3kb2n3UP9sB3ZPdqM0");
				$c;
				$number = substr($_REQUEST['phone'], 2);
				$mssg = 'Client found!';
				$success = true;
				$client_sent = array();
				$customers = ChargeBee_Customer::all(
					array(
						"limit" => 1, 
						"phone[is]" => $number,
						"sortBy[desc]" => "created_at",
					)
				);
				if (count($customers) > 0) {
					foreach ($customers as $customer){
						$c = $customer->customer();
						$client_sent['name'] = $c->firstName.' '.$c->lastName;
						$client_sent['email'] = $c->email;
						$client_sent['phone'] = $c->phone;
						$subs = ChargeBee_Subscription::all(
							array(
								"limit" => 1, 
								"customerId[is]" => $c->id,
								"status[is]" => 'active',
							)
						);
						if (count($subs) > 0) {
							foreach ($subs as $sub) {
								$sub = $sub->subscription();
								$success = true;
							}
						} else {
							$mssg = 'The client does not have an active account on ChargeBee.';
							$success = false;
						}
					}
				} else {
					$mssg = 'There is no client with that phone number registered on ChargeBee.';
					$success = false;
				}

				$data = [
					'mssg' => $mssg,
					'success' => $success,
					'customer' => $client_sent,
				];
			}
		} else {
			$data = ['mssg' => 'API key is incorrect.'];
		}
	} else {
		$data = ['mssg' => 'No API key given.'];
	}
	echo (json_encode($data)); exit();