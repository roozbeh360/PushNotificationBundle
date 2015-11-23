<?php

namespace Rth\NotificationBundle\Services;

use Sly\NotificationPusher\PushManager,
    Sly\NotificationPusher\Adapter\Apns as ApnsAdapter,
    Sly\NotificationPusher\Adapter\Gcm as GcmAdapter,
    Sly\NotificationPusher\Collection\DeviceCollection,
    Sly\NotificationPusher\Model\Device,
    Sly\NotificationPusher\Model\Message,
    Sly\NotificationPusher\Model\Push;
use Rth\NotificationBundle\Entity\Device as Hdevice;
use Doctrine\ORM\EntityManager;

class NotificationService
{

    private $entityManager;
    private $pushManager;
    private $apnsAdapter;
    private $gcmAdapter;
    private $env;

    function __construct(EntityManager $entityManager, $certificate_pem, $gcm_key, $env)
    {
        $this->entityManager = $entityManager;

        if ($env == 'prod') {
            $this->pushManager = new PushManager(PushManager::ENVIRONMENT_PROD);
        } else {
            $this->pushManager = new PushManager(PushManager::ENVIRONMENT_DEV);
        }

        $this->apnsAdapter = new ApnsAdapter(array('certificate' => $certificate_pem,));
        $this->gcmAdapter = new GcmAdapter(array('apiKey' => $gcm_key,));
        $this->env = $env ;
    }

    public function addDevice($os, $token, $user = null)
    {
        $device = $this->entityManager->getRepository('RthNotificationBundle:Device')->findOneBy(['os' => $os, 'token' => $token]);

        if (!$device) {
            $device = new Hdevice();
            $device->setOs($os);
            $device->setToken($token);
            $this->entityManager->persist($device);
            $this->entityManager->flush();
        }

        if ($user) {
            $user->setDevice($device);
            $this->entityManager->flush();
        }
        return true;
    }

    public function sendNotifications($devices, $message)
    {
        $listApns = [];
        $listGcm = [];
        foreach ($devices as $device) {
            if ($device->getOs() == 'ios') {
                $listApns[] = $device->getToken();
            }

            if ($device->getOs() == 'android') {
                $listGcm[] = $device->getToken();
            }
        }

        $this->sendApnsNotification($listApns, $message);
        $this->sendGcmNotification($listGcm, $message);
    }

    private function sendApnsNotification($tokens, $apnsMessage)
    {
        $apnsDevices = [];

        foreach ($tokens as $token) {
            $apnsDevices[] = new Device($token, array('badge' => 1, 'sound' => 'chime.aiff'))
            ;
        }

        $devices = new DeviceCollection($apnsDevices);
        $message = new Message($apnsMessage);
        $push = new Push($this->apnsAdapter, $devices, $message);

        if ($this->env == 'prod') {
            $pushManager = new PushManager(PushManager::ENVIRONMENT_PROD);
        } else {
            $pushManager = new PushManager(PushManager::ENVIRONMENT_DEV);
        }
        
        $pushManager->add($push);
        $pushManager->push();

        //$this->pushManager->add($push);
        //$this->pushManager->push();
    }

    private function sendGcmNotification($tokens, $gcmMessage)
    {
        $gcmDevices = [];

        foreach ($tokens as $token) {
            $gcmDevices[] = new Device($token);
        }

        $devices = new DeviceCollection($gcmDevices);
        $message = new Message($gcmMessage);
        $push = new Push($this->gcmAdapter, $devices, $message);
        $this->pushManager->add($push);
        $this->pushManager->push(); // Returns a collection of notified devices
    }

}
