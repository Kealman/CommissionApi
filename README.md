CommissionApi
=============

PHP class for FBS commission api

Expample:

```php
  <?php

	$api = new CommissionApi();

	try{
		$result = $api->getCommissionOrders("2014-07-31", 123456);
	}catch(CommissionApiException $e){
		echo "Request error " . $e->getMessage();
		die();
	}catch(Exception $e){
		echo "Internall error " . $e->getMessage();
	}

	var_dump($result);


?>
```
