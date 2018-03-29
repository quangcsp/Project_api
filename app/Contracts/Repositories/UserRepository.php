<?php

namespace App\Contracts\Repositories;

interface UserRepository extends AbstractRepository
{
    public function getCurrentUser($userFromAuthServer);

    public function getDataBookOfUser($id, $action, $select = ['*'], $with = [], $officeId = '');

    public function addTags(string $tags = null);

    public function getInterestedBooks($dataSelect = ['*'], $with = [], $officeId = '');

    public function show($id);

    public function ownedBooks($dataSelect = ['*'], $with = []);

    public function getListWaitingApprove($dataSelect = ['*'], $with = [], $officeId = '');

    public function getBookApproveDetail($bookId, $dataSelect = ['*'], $with = []);

    public function getNotifications();

    public function getNotificationsDropdown();

    public function followOrUnfollow($userId);

    public function getFollowInfo($id);

    public function updateViewNotifications($notificationId);

    public function countNotificationNotView();

    public function getFavoriteCategory($id);

    public function updateViewNotificationsAll();

    public function getWaitingApproveEditBook();
}
