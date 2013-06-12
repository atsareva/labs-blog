<?php

require_once CORE_PATH . 'controller/controller_front' . EXT;

class Controller_Profile extends Controller_Front
{

    function index($id)
    {
        if (!$this->checkSessionUser())
            $this->redirect('profile/login');

        $data = array();
        if (isset($id[0])) {
            $user = Core::getModel('user')->load((int) $id[0]);
            if ($user->getId()) {
                $data = array(
                    'user'       => $user,
                    'faculty'    => Core::getModel('faculty')->load($user->getFacultyId()),
                    'department' => Core::getModel('department')->load($user->getDepartmentId()),
                    'userStatus' => Core::getModel('user_status')->load($user->getStatusId())
                );
            }
        }

        $this->_view->setTitle('Профайл')
                ->setChild('content', 'front/profile/profile', $data);
    }

    function login()
    {
        $error     = '';
        $userModel = Core::getModel('user');

        if ($this->checkSessionUser())
            $this->redirect('home');

        if (isset($_POST['login']) && isset($_POST['pass'])) {
            Security::secCheckToken('userLogin');

            if (Security::secIsStr($_POST['login']) && Security::secIsStr($_POST['pass'])) {
                $user = $userModel->addFieldToFilter('user_name', array('=' => $_POST['login']))
                        ->addFieldToFilter('pass', array('=' => md5(Security::$_secAppSalt . $_POST['pass'])))
                        ->getCollection()
                        ->getData();
                if (isset($user[0]->id)) {
                    if ($user[0]->block == 1)
                        $error = "Вас заблокировал администратор сайта.";
                    else {
                        $userModel->load($user[0]->id)
                                ->setData('session_id', session_id())
                                ->save();
                        $this->redirect('home');
                    }
                }
                else
                    $error = "Проверьте правильность введенных логина и пароля.";
            }
            else
                $error = "Введенные логин или пароль - некорректны.";
        }

        $this->_view->setTitle('Вход')
                ->setBaseClass('login')
                ->setChild('content', 'front/profile/login', array('error' => $error));
    }

    function signup()
    {
        $error = '';
        /**
         * @todo Realise validation for post
         */
        if (isset($_POST) && !empty($_POST)) {
            Security::secCheckToken('userSignup');

            if (!Security::secIsStr($_POST['user_name'])) {
                $error .= 'Введите корректный логин!\n';
            }
            elseif (!Security::secIsStr($_POST['pass']) || !Security::secIsStr($_POST['confirm_pass'])) {
                $error .= 'Введите корректный пароль и подтвердите его!\n';
            }
            elseif (!Security::secMatches($_POST['pass'], 'confirm_pass')) {
                $error .= 'Поля "Пароль" и "Подтверждение пароля" - должны совпадать!\n';
            }
            elseif (!Security::secEmail($_POST['email'])) {
                $error .= 'Введите корректный email-адрес!\n';
            }
            else {
                //load user by entered login
                $userLogin = Core::getModel('user')->cleanQuery()
                        ->addFieldToFilter('user_name', array('=' => $_POST['user_name']))
                        ->getCollection()
                        ->getData();
                
                //load user by entered email
                $userEmail = Core::getModel('user')->cleanQuery()
                        ->addFieldToFilter('email', array('=' => $_POST['email']))
                        ->getCollection()
                        ->getData();

                if (isset($userLogin[0]->id)) {
                    $error = 'Пользователь с логином"' . $_POST['user_name'] . '" уже существует.';
                    unset($userLogin);
                }
                elseif (isset($userEmail[0]->id)) {
                    $error = 'Пользователь с email-адресом"' . $_POST['email'] . '" уже существует.';
                    unset($userEmail);
                }
                else {
                    session_regenerate_id();
                    $data = $_POST;
                    unset($_POST);

                    /**
                     * @todo Realise generate salt for password
                     */
                    $data['pass']          = md5(Security::$_secAppSalt . $data['pass']);
                    $data['register_date'] = time();
                    $data['last_login']    = time();
                    $data['access_id']     = 2;
                    $data['session_id']    = session_id();
                    unset($data['confirm_pass']);

                    $user = Core::getModel('user')
                            ->setData($data)
                            ->save();
                    $this->redirect('profile/index/' . $user->getId());
                }
            }
        }

        $faculties   = Core::getModel('faculty')->getCollection()->getData();
        $departments = Core::getModel('department')->getCollection()->getData();
        $statuses    = Core::getModel('user_status')
                ->addFieldToFilter('name', array(array('=' => 'студент'), array('=' => 'преподаватель')))
                ->getCollection()
                ->getData();

        $this->_view->setTitle('Регистрация')
                ->setBaseClass('signup')
                ->setChild('content', 'front/profile/signup', array('error'       => $error, 'faculties'   => $faculties, 'departments' => $departments, 'statuses'    => $statuses));
    }

    public function logout()
    {
        $userModel = Core::getModel('user');

        $id = Core::getHelper('user')->getCurrentUser();
        if ($id)
            $userModel->load($id)
                    ->setData('session_id', NULL)
                    ->save();
        $this->redirect('home');
    }

    protected function checkSessionUser()
    {
        $userModel = Core::getModel('user');

        $id = Core::getHelper('user')->getCurrentUser();
        if ($id) {
            session_regenerate_id();
            $userModel->load($id)
                    ->setData('session_id', session_id())
                    ->save();
            return $userModel->getAccessId();
        }
        return false;
    }

}

?>
