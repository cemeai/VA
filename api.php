<?php

	if (isset($_REQUEST['phone'])) {
		require $_SERVER['DOCUMENT_ROOT'].'/ChargeBee/lib/ChargeBee.php';

		ChargeBee_Environment::configure("vatoday-test","test_Gk4YTcKidSlp09GjYCGemgHUW5u3l7Yf");
		$c;
		$mssg = 'Client found!';
		$success = true;
		$customers = ChargeBee_Customer::all(
			array(
				"limit" => 1, 
				"phone[is]" => $_REQUEST['phone'],
				"sortBy[desc]" => "created_at",
			)
		);
		if (isset($customers)) {
			foreach ($customers as $customer){
				$c = $customer->customer();
				$subs = ChargeBee_Subscription::all(
					array(
						"limit" => 1, 
						"customerId[is]" => $c->id,
						"status[is]" => 'active',
					)
				);
				if (isset($subs)) {
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
			'customer' => $c,
		];
		var_dump($data); exit();
		return json_encode($data);
	}