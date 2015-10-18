<?php

namespace Rth\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RthNotificationBundle:Default:index.html.twig', array('name' => $name));
    }
}
