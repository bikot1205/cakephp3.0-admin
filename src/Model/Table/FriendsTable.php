<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;

class FriendsTable extends Table {

    public function initialize(array $config) {
        
    }

    /**
     * User one in the relationship
     *
     * @var User
     */
    public $loggedInUser;

    /**
     * User one in the relationship
     *
     * @var User
     */
    public $userOne;

    /**
     * User two in the relationship
     *
     * @var User
     */
    public $userTwo;

    /**
     * Determines the status of the relationship
     * 
     * 0 - Pending
     * 1 - Accepted
     * 2 - Declined
     * 3 - Blocked
     * 
     * By default the status is set to 0
     */
    public $status = 0;

    /**
     * This is the user who made the most recent status field update
     */
    public $actionUserId;

    /**
     * Return the friend of the current logged in user in the relationship object
     * 
     * @param Relationship $rel
     * @return User $friend
     */
    public function isFriend($user) {
        $user_one = (int) $this->loggedInUser['id'];
        $user_two = (int) $user['id'];

        $query = $this->find('all');
        $query = $query->where([
            ['OR' => ['user_one_id' => $user_one, 'user_two_id' => $user_one]],
            ['OR' => ['user_one_id' => $user_two, 'user_two_id' => $user_two]]
        ]);

        return $query->first();
    }

    /**
     * Get all the friends list for the currently loggedin user
     * 
     * @return array Relationship Objects
     */
    public function getFriendsList() {
        $id = (int) $this->loggedInUser->getUserId();

        $sql = 'SELECT * FROM `relationship` WHERE ' .
                '(`user_one_id` = ' . $id . ' OR `user_two_id` = ' . $id . ') ' .
                'AND `status` = 1';

        $resultObj = $this->dbCon->query($sql);

        $rels = array();

        while ($row = $resultObj->fetch_assoc()) {
            $rel = new Relationship();
            $rel->arrToRelationship($row, $this->dbCon);
            $rels[] = $rel;
        }

        return $rels;
    }

    /**
     * Get the list of friend requests sent by the logged in user
     * 
     * @return array Relationship Objects
     */
    public function getSentFriendRequests() {
        $id = (int) $this->loggedInUser->getUserId();

        $sql = 'SELECT * FROM `relationship` WHERE ' .
                '(`user_one_id` = ' . $id . ' OR `user_two_id` = ' . $id . ') ' .
                'AND `status` = 0 ' .
                'AND `action_user_id` = ' . $id;

        $resultObj = $this->dbCon->query($sql);

        $rels = array();

        while ($row = $resultObj->fetch_assoc()) {
            $rel = new Relationship();
            $rel->arrToRelationship($row, $this->dbCon);
            $rels[] = $rel;
        }

        return $rels;
    }

    /**
     * Get the list of friend requests for the logged in user
     * 
     * @return array Relationship Objects
     */
    public function getFriendRequests() {
        $id = (int) $this->loggedInUser->getUserId();

        $sql = 'SELECT * FROM `relationship` ' .
                'WHERE (`user_one_id` = ' . $id . ' OR `user_two_id` = ' . $id . ')' .
                ' AND `status` = 0 ' .
                'AND `action_user_id` != ' . $id;

        $resultObj = $this->dbCon->query($sql);

        $rels = array();

        while ($row = $resultObj->fetch_assoc()) {
            $rel = new Relationship();
            $rel->arrToRelationship($row, $this->dbCon);
            $rels[] = $rel;
        }

        return $rels;
    }

    /**
     * Get the list of friends blocked by the current user.
     * 
     * @return \Relationship array
     */
    public function getBlockedFriends() {
        $id = (int) $this->loggedInUser->getUserId();

        $sql = 'SELECT * FROM `relationship` ' .
                'WHERE (`user_one_id` = ' . $id . ' OR `user_two_id` = ' . $id . ')' .
                ' AND `status` = 3 ' .
                'AND `action_user_id` = ' . $id;

        $resultObj = $this->dbCon->query($sql);

        $rels = array();

        while ($row = $resultObj->fetch_assoc()) {
            $rel = new Relationship();
            $rel->arrToRelationship($row, $this->dbCon);
            $rels[] = $rel;
        }

        return $rels;
    }

    /**
     * Insert a new friends request
     * 
     * @param User $user - User to which the friend request must be added with.
     * @return Boolean
     */
    public function addFriendRequest($user) {
        $user_one = (int) $this->loggedInUser['id'];
        $action_user_id = $user_one;
        $user_two = (int) $user['id'];

        if ($user_one > $user_two) {
            $temp = $user_one;
            $user_one = $user_two;
            $user_two = $temp;
        }


        $requestData = $this->newEntity();
        $requestData->user_one_id = $user_one;
        $requestData->user_two_id = $user_two;
        $requestData->status = 0;
        $requestData->action_user_id = $action_user_id;

        if ($this->save($requestData)) {
            return true;
        }

        return false;
    }

    /**
     * Accept a friend request
     * 
     * @param User $user - User to whome the friend request must be accepted with.
     * @return Boolean
     */
    public function acceptFriendRequest($user) {
        $user_one = (int) $this->loggedInUser['id'];
        $action_user_id = $user_one;
        $user_two = (int) $user['id'];

        if ($user_one > $user_two) {
            $temp = $user_one;
            $user_one = $user_two;
            $user_two = $temp;
        }


        $query = $this->query();
        return $query->update()->set(['status' => 1, 'action_user_id' => $action_user_id])->where(['user_one_id' => $user_one, 'user_two_id' => $user_two])->execute();
    }

    /**
     * Decline a friend request for the user
     * 
     * @params User $user - The user whose request to be declined
     * @return Boolean
     */
    public function declineFriendRequest($user) {
        $user_one = (int) $this->loggedInUser['id'];
        $action_user_id = $user_one;
        $user_two = (int) $user['id'];

        if ($user_one > $user_two) {
            $temp = $user_one;
            $user_one = $user_two;
            $user_two = $temp;
        }


        $query = $this->query();
        return $query->update()->set(['status' => 2, 'action_user_id' => $action_user_id])->where(['user_one_id' => $user_one, 'user_two_id' => $user_two])->execute();
    }

    /**
     * Cancel a friend request
     * 
     * @param User $user - The friend details
     * @return Boolean
     */
    public function cancelFriendRequest($user) {
        $user_one = (int) $this->loggedInUser['id'];
        $user_two = (int) $user['id'];

        if ($user_one > $user_two) {
            $temp = $user_one;
            $user_one = $user_two;
            $user_two = $temp;
        }

        $query = $this->query();
        $result = $query->delete()
                ->where(['user_one_id' => $user_one, 'user_two_id' => $user_two, 'status' => 0])
                ->execute();

        return $result->count();
    }

    /**
     * Remove a friend from the friends list
     * 
     * @param User $user - The friend details
     * @return Boolean
     */
    public function unfriend($user) {
        $user_one = (int) $this->loggedInUser['id'];
        $user_two = (int) $user['id'];

        if ($user_one > $user_two) {
            $temp = $user_one;
            $user_one = $user_two;
            $user_two = $temp;
        }

        $query = $this->query();
        $result = $query->delete()
                ->where(['user_one_id' => $user_one, 'user_two_id' => $user_two, 'status' => 1])
                ->execute();

        return $result->count();
    }

    /**
     * Block a particular user
     * 
     * @param User $user - The user to be blocked
     * @return Boolean
     */
    public function block($user) {
        $user_one = (int) $this->loggedInUser['id'];
        $action_user_id = $user_one;
        $user_two = (int) $user['id'];

        if ($user_one > $user_two) {
            $temp = $user_one;
            $user_one = $user_two;
            $user_two = $temp;
        }

        $query = $this->query();
        return $query->update()->set(['status' => 3, 'action_user_id' => $action_user_id])->where(['user_one_id' => $user_one, 'user_two_id' => $user_two])->execute();
    }

    /**
     * Unblock a friend who is blocked already.
     * 
     * @param User $user
     * @return boolean
     */
    public function unblockFriend(User $user) {
        $user_one = (int) $this->loggedInUser->getUserId();
        $user_two = (int) $user->getUserId();

        if ($user_one > $user_two) {
            $temp = $user_one;
            $user_one = $user_two;
            $user_two = $temp;
        }

        $sql = 'DELETE FROM `relationship` ' .
                'WHERE `user_one_id` = ' . $user_one .
                ' AND `user_two_id` = ' . $user_two .
                ' AND `status` = 3';

        $this->dbCon->query($sql);

        if ($this->dbCon->affected_rows > 0) {
            return true;
        }

        return false;
    }

}
