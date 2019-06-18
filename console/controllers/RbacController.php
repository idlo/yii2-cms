<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // 添加 "createPost" 权限
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // 添加 "updatePost" 权限
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // 添加 "deletePost" 权限
        $deletePost = $auth->createPermission('deletePost');
        $deletePost->description = 'Delete post';
        $auth->add($deletePost);

        // 添加 "createCategory" 权限
        $createCategory = $auth->createPermission('createCategory');
        $createCategory->description = 'Create Category';
        $auth->add($createCategory);

        // 添加 "updateCategory" 权限
        $updateCategory = $auth->createPermission('updateCategory');
        $updateCategory->description = 'Update Category';
        $auth->add($updateCategory);

        // 添加 "deleteCategory" 权限
        $deleteCategory = $auth->createPermission('deleteCategory');
        $deleteCategory->description = 'Delete Category';
        $auth->add($deleteCategory);

        // 添加 "approveComment" 权限
        $approveComment = $auth->createPermission('approveComment');
        $approveComment->description = 'Approve Comment';
        $auth->add($approveComment);

        // 添加 "deleteComment" 权限
        $deleteComment = $auth->createPermission('deleteComment');
        $deleteComment->description = 'Delete Comment';
        $auth->add($deleteComment);

        // 添加 "updateComment" 权限
        $updateComment = $auth->createPermission('updateComment');
        $updateComment->description = 'Update Comment';
        $auth->add($updateComment);

        // 添加 "updateUser" 权限
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update User';
        $auth->add($updateUser);

        // 添加 "createAdmin" 权限
        $createAdmin = $auth->createPermission('createAdmin');
        $createAdmin->description = 'Create Admin';
        $auth->add($createAdmin);

        // 添加 "updateAdmin" 权限
        $updateAdmin = $auth->createPermission('updateAdmin');
        $updateAdmin->description = 'Update Admin';
        $auth->add($updateAdmin);

        // 添加 "resetAdminPassword" 权限
        $resetAdminPassword = $auth->createPermission('resetAdminPassword');
        $resetAdminPassword->description = 'Reset Admin Password';
        $auth->add($resetAdminPassword);

        // 添加 "privilegeAdmin" 权限
        $privilegeAdmin = $auth->createPermission('privilegeAdmin');
        $privilegeAdmin->description = 'Privilege Admin';
        $auth->add($privilegeAdmin);

        // 添加 "author" 角色并赋予 "createPost" 权限
        $author = $auth->createRole('author');
        $author->description = 'Author';
        $auth->add($author);
        $auth->addChild($author, $createPost);
        $auth->addChild($author, $createCategory);

        // 添加 "admin" 角色并赋予 "updatePost"
        // 和 "author" 权限
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
        $auth->addChild($admin, $createAdmin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $updateCategory);
        $auth->addChild($admin, $updateAdmin);
        $auth->addChild($admin, $updateComment);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteCategory);
        $auth->addChild($admin, $deleteComment);
        $auth->addChild($admin, $deletePost);
        $auth->addChild($admin, $approveComment);
        $auth->addChild($admin, $resetAdminPassword);
        $auth->addChild($admin, $privilegeAdmin);
        $auth->addChild($admin, $author);

        // 添加 "commentAuditor" 角色并赋予 "createPost" 权限
        $commentAuditor = $auth->createRole('commentAuditor');
        $commentAuditor->description = 'Comment Auditor';
        $auth->add($commentAuditor);
        $auth->addChild($commentAuditor, $approveComment);

        // 为用户指派角色。其中 1 和 2 是由 IdentityInterface::getId() 返回的id
        // 通常在你的 Admin 模型中实现这个函数。
        $auth->assign($author, 2);
        $auth->assign($admin, 1);
    }
}