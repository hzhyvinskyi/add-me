<?php
namespace frontend\tests\unit\models;

use frontend\modules\user\models\SignupForm;
use frontend\tests\fixtures\UserFixture;

class SignupFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
            ]
        ]);
    }

    public function testCorrectSignup()
    {
        $model = new SignupForm([
            'username' => 'some_username',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
        ]);

        $user = $model->signup();

        expect($user)->isInstanceOf('common\models\User');

        expect($user->username)->equals('some_username');
        expect($user->email)->equals('some_email@example.com');
        expect($user->validatePassword('some_password'))->true();
    }

    public function testNotCorrectSignup()
    {
        $model = new SignupForm([
            'username' => 'troy.becker',
            'email' => 'nicolas.dianna@hotmail.com',
            'password' => 'some_password',
        ]);

        expect_not($model->signup());
        expect_that($model->getErrors('username'));
        expect_that($model->getErrors('email'));

        expect($model->getFirstError('username'))
            ->equals('This username has already been taken.');
        expect($model->getFirstError('email'))
            ->equals('This email address has already been taken.');
    }

    public function testTrimUsername()
    {
        $model = new SignupForm([
            'username' => '   some_username   ',
            'email' => 'some_email@example.com',
            'password' => '123456',
        ]);

        $model->signup();

        expect($model->username)->equals('some_username');
    }

    public function testUsernameRequired()
    {
        $model = new SignupForm([
            'username' => '',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
        ]);

        $model->signup();

        expect($model->getFirstError('username'))->equals('Username cannot be blank.');
    }

    public function testUsernameTooShort()
    {
        $model = new SignupForm([
            'username' => 's',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
        ]);

        $model->signup();

        expect($model->getFirstError('username'))->equals('Username should contain at least 2 characters.');
    }

    public function testPasswordRequired()
    {
        $model = new SignupForm([
            'username' => 'some_username',
            'email' => 'some_email@example.com',
            'password' => '',
        ]);

        $model->signup();

        expect($model->getFirstError('password'))->equals('Password cannot be blank.');
    }

    public function testEmailUnique()
    {
        $model = new SignupForm([
            'username' => 'some_username',
            'email' => '1@got.com',
            'password' => 'some_password',
        ]);

        $model->signup();

        expect($model->getFirstError('email'))->equals('This email addres has already been taken');
    }
}
