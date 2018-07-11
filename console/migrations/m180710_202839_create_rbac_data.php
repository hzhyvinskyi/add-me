<?php

use yii\db\Migration;
use backend\models\User;

/**
 * Class m180710_202839_create_rbac_data
 */
class m180710_202839_create_rbac_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Define permissions

        $viewComplaintsListPermission = $auth->createPermission('viewComplaintsList');
        $auth->add($viewComplaintsListPermission);

        $viewPostPermission = $auth->createPermission('viewPost');
        $auth->add($viewPostPermission);

        $deletePostPermission = $auth->createPermission('deletePost');
        $auth->add($deletePostPermission);

        $approvePostPermission = $auth->createPermission('approvePost');
        $auth->add($approvePostPermission);

        $viewUsersListPermission = $auth->createPermission('viewUsersList');
        $auth->add($viewUsersListPermission);

        $viewUserPermission = $auth->createPermission('viewUser');
        $auth->add($viewUserPermission);

        $updateUserPermission = $auth->createPermission('updateUser');
        $auth->add($updateUserPermission);

        $deleteUserPermission = $auth->createPermission('deleteUser');
        $auth->add($deleteUserPermission);

        // Define roles

        $moderatorRole = $auth->createRole('moderator');
        $auth->add($moderatorRole);

        $adminRole = $auth->createRole('admin');
        $auth->add($adminRole);

        // Define roles - permissions relations

        $auth->addChild($moderatorRole, $viewComplaintsListPermission);
        $auth->addChild($moderatorRole, $viewPostPermission);
        $auth->addChild($moderatorRole, $deletePostPermission);
        $auth->addChild($moderatorRole, $approvePostPermission);
        $auth->addChild($moderatorRole, $viewUsersListPermission);
        $auth->addChild($moderatorRole, $viewUserPermission);

        $auth->addChild($adminRole, $moderatorRole);
        $auth->addChild($adminRole, $updateUserPermission);
        $auth->addChild($adminRole, $deleteUserPermission);

        // Create admin user

        $user = new User([
            'username' => 'Admin',
            'email' => 'admin@admin.com',
            'password_hash' => '$2y$13$f1xv8fDwA9b5mq1yQ8Ao3.FOOxT6jID7AVQ07uGNaa56OWIhQ6YFm',
        ]);
        $user->generateAuthKey();
        $user->save();

        // Assign admit role to user

        $auth->assign($adminRole, $user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180710_202839_create_rbac_data cannot be reverted.\n";

        return false;
    }
}
