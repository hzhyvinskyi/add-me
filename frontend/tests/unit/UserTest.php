<?php
namespace frontend\tests\unit;

use Yii;
use codeception\test\Unit;
use frontend\tests\fixtures\UserFixture;

class UserTest extends Unit
{
    /* @var $tester \frontend\tests\UnitTester */
    protected $tester;

    public function _fixtures()
    {
        return ['users' => UserFixture::className()];
    }

    public function _before()
    {
        Yii::$app->setComponents([
            'redis' => [
                'class' => 'yii\redis\Connection',
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 1,
            ],
        ]);
    }

    public function testGetNicknameOnNicknemeEmpty()
    {
        $user = $this->tester->grabFixture('users', 'user1');

        expect($user->getNickname())->equals(1);
    }

    public function testGetNicknameOnNicknameNotEmpty()
    {
        $user = $this->tester->grabFixture('users', 'user3');

        expect($user->getNickname())->equals('snow');
    }

    public function testGetPostCount()
    {
        $user = $this->tester->grabFixture('users', 'user1');

        expect($user->getPostCount())->equals(3);
    }

    public function testFollowUser()
    {
        $user1 = $this->tester->grabFixture('users', 'user1');
        $user3 = $this->tester->grabFixture('users', 'user3');

        $user3->followUser($user1);

        $this->tester->seeRedisContains('user:1:followers', 3);
        $this->tester->seeRedisContains('user:3:subscriptions', 1);

        $this->tester->sendCommandToRedis('del', 'user:1:followers');
        $this->tester->sendCommandToRedis('del', 'user:3:subscriptions');
    }

    public function testUnfollowUser()
    {
        $user1 = $this->tester->grabFixture('users', 'user1');
        $user3 = $this->tester->grabFixture('users', 'user3');

        $user3->followUser($user1);

        $user3->unfollowUser($user1);

        $this->tester->seeRedisContains('user:1:followers', 3);
        $this->tester->seeRedisContains('user:3:subscriptions', 1);
    }
}
