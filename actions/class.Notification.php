<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2017 (original work) Open Assessment Technologies SA;
 *
 */

use \oat\oatbox\notification\NotificationServiceInterface;
use \oat\oatbox\notification\NotificationInterface;
use \oat\oatbox\notification\exception\NotListedNotificationNotListedNotification;

class tao_actions_Notification extends \tao_actions_CommonModule
{

    public function getCount() {

        $userService = \tao_models_classes_UserService::singleton();
        $user = $userService->getCurrentUser();

        /**
         * @var \oat\oatbox\notification\NotificationServiceInterface $notificationService
         */
        $notificationService = $this->getServiceManager()->get(NotificationServiceInterface::SERVICE_ID);
        try {
            $count = $notificationService->notificationCount($user->getUri());
        } catch (NotListedNotificationNotListedNotification $e) {
            return $this->returnError($e->getUserMessage());
        }
        return $this->returnJson($count);

    }

    public function getList() {

        $userService = \tao_models_classes_UserService::singleton();
        $user = $userService->getCurrentUser();

        /**
         * @var \oat\oatbox\notification\NotificationServiceInterface $notificationService
         */
        $notificationService = $this->getServiceManager()->get(NotificationServiceInterface::SERVICE_ID);
        try {
            $list = $notificationService->getNotifications($user->getUri());
        } catch (NotListedNotificationNotListedNotification $e) {
            return $this->returnError($e->getUserMessage());
        }
        return $this->returnJson($list);

    }

    public function getDetail() {
        if( $this->hasRequestParameter('id')) {
            $id = $this->getRequestParameter('id');
            /**
             * @var \oat\oatbox\notification\NotificationServiceInterface $notificationService
             */
            $notificationService = $this->getServiceManager()->get(NotificationServiceInterface::SERVICE_ID);
            try {
                $list = $notificationService->getNotification($id);
            } catch (NotListedNotificationNotListedNotification $e) {
                return $this->returnError($e->getUserMessage());
            }
            return $this->returnJson($list);
        }
        return $this->returnError(__('require notification ID'));
    }

    public function getUiList() {

        $userService = \tao_models_classes_UserService::singleton();
        $user = $userService->getCurrentUser();

        /**
         * @var \oat\oatbox\notification\NotificationServiceInterface $notificationService
         */
        $notificationService = $this->getServiceManager()->get(NotificationServiceInterface::SERVICE_ID);
        try {
            $list = $notificationService->getNotifications($user->getUri());
        } catch (NotListedNotificationNotListedNotification $e) {
            return $this->returnError($e->getUserMessage());
        }
        /**
         * @var NotificationInterface $notif
         */
        foreach ($list as $notif) {
            if($notif->getStatus() === NotificationInterface::CREATED_STATUS) {
                $notif->setStatus(NotificationInterface::READ_STATUS);
                $notificationService->changeStatus($notif);
                $notif->setStatus(NotificationInterface::CREATED_STATUS);
            }
        }

        $this->setData('notif-list' , $list);
        $this->setView('notification/list.tpl');

    }

    public function addNotification() {

        $userService = \tao_models_classes_UserService::singleton();
        $user = $userService->getCurrentUser();

        $notification = new \oat\oatbox\notification\implementation\Notification(
            $user->getUri() , 'title test 2' , 'message test 2' , $user->getUri() , $user->getLabel()
        );

        /* @var $notifService NotificationServiceInterface */
        $notifService = $this->getServiceManager()->get(NotificationServiceInterface::SERVICE_ID);
        $notifService->sendNotification($notification);
        die();

    }

}