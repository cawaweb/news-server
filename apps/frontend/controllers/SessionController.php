<?php

namespace NewsServer\Frontend\Controllers;

use NewsServer\Frontend\Collections\Users;

class SessionController extends ControllerBase
{
    public function loginAction()
    {
        try {
            if ($this->request->isPost()) {
                if ($this->security->checkToken()) {
                    $username = $this->request->getPost('username');
                    $password = $this->request->getPost('password');

                    $user = Users::findFirst([
                        ['username' => $username]
                    ]);
                    if ($user) {
                        if ($this->security->checkHash($password, $user->getPassword())) {
                            $this->registerSession($user);
                            $this->flash->success('Welcome ' . $user->getName());
                            $this->response->redirect('/web/index');
                        }
                    } else {
                        $this->security->hash(rand());
                        $this->flash->error('Authentication failed.');
                    }
                }
            }
        } catch (\Exception $e) {
            $this->flash->error('Authentication failed.');
        }
    }

    public function logoutAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        $this->dispatcher->forward(
            [
                'module'     => 'web',
                'controller' => 'session',
                'action'     => 'login'
            ]
        );
    }

    protected function registerSession($user)
    {
        $this->session->set(
            'auth',
            [
                'id'   => $user->getId(),
                'name' => $user->getName()
            ]
        );
    }
}
