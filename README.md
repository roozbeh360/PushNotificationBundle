# PushNotificationBundle
symfony push notification bundle for ios , android devices . based on Ph3nol/NotificationPusher .

https://github.com/Ph3nol/NotificationPusher

## install

	composer require rth/notification-bundle

in your AppKernel.php file add following line :

	public function registerBundles()
		{
			$bundles = array(
			.
			.
			.
			.
			new Rth\NotificationBundle\RthNotificationBundle(), // push notification bundle
			)
		}

now add these lines in app/config.yml

	parameters:    
		r360_notification.apns.pem: "%kernel.root_dir%/config/apple.pem" # could be production or sandbox/dev 
		r360_notification.gcm.key: "api_key" # google api key for push 
		r360_notification.env: prod # environment depends on your key for pem file 
		
		
		
		
usage

add device to database

		$notificaionManager = $this->get('r360_notification.service');
        $notificaionManager->addDevice($os, $token);
		
send push notification		
		$notificaionManager = $this->get('r360_notification.service');
        $notificaionManager->sendNotifications(Device_entity, 'i have send 1 push to device' );
		
		
add device entity to any entities 

		<one-to-one field="device" target-entity="Rth\NotificationBundle\Entity\Device">
            <join-column name="device_id" referenced-column-name="id" />     
        </one-to-one>     	

	$notificaionManager = $this->get('r360_notification.service');
    $notificaionManager->addDevice($os, $token, $entity );	

more useage :
		https://github.com/Ph3nol/NotificationPusher