<?php

namespace UserShield\UserModule\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Entities\User;
use Psr\Log\LoggerInterface;
use UserShield\UserModule\UserModule;
use CodeIgniter\Events\Events;

/**
 * UserController
 * @author Christel Ehrhart - https://ce-soft.info
 * @version 1.0
 * @date 2025-04-01
 * @description Controller for managing users
 *
 * @package App\Controllers
 */
class UserController extends BaseController
{
    protected $users;
    protected $groups;

    /**
     * Constructor
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController(
            $request,
            $response,
            $logger
        );

        $this->users    = auth()->getProvider();
        $this->groups   = config('Config\AuthGroups')->groups;

    }

    /**
     * Users list page to manage users
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index(): string
    {
        // Get all users and groups
        $data = [
            'users'     => $this->users->findAll(),
            'groups'    => $this->groups,
        ];

        // Get the view
        return view('users\index', $data);
    }

    /**
     * Display the user creation form
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function new(): string
    {
        // Get all groups
        $data = [
            'groups'    => $this->groups,
        ];

        // Get the view
        return view('users/create', $data);
    }

    /**
     * Process the user creation
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        // Get the validation rules
        $shieldRules    = new \CodeIgniter\Shield\Validation\ValidationRules();
        $rules          = $shieldRules->getRegistrationRules();

        // Validate the data
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Create a new user
        $user   = new User([
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
        ]);

        // Save the user
        $this->users->save($user);

        // Check if the user was created successfully
        $user = $this->users->findById($this->users->getInsertID());
        if ($user){
            // Set the user as active
            $user->activate();

            // Set the user groups
            $selectedGroups = $this->request->getPost('groups');
            if (!empty($selectedGroups)) {
                foreach($selectedGroups as $group) {
                    $user->addGroup($group);
                }
            } else {
                // By default, add to the "user" group
                $this->users->addToDefaultGroup($user);
            }

            // Send a welcome email TODO
            // $this->sendWelcomeEmail($user);

            // Redirect to the users list with a success message
            return redirect()->to('/users')
                ->with('message', lang('User.create_success'));
        } else {
            // Redirect to the users list with an error message
            return redirect()->back()->withInput()->with('error', lang('User.create_failed'));
        }
    }

    /**
     * Display the user edit form
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function edit($id)
    {
        // Get the user
        $user = $this->users->findById($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->to('/users')
                ->with('error', lang('User.not_found'));
        }

        // Set the user and the groups for the view
        $data = [
            'user'      => $user,
            'groups'    => $this->groups,
        ];

        // Get the view
        return view('users/edit', $data);
    }

    /**
     * Process the user update
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        // Get the user
        $user = $this->users->findById($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->to('/users')
                ->with('error', lang('User.not_found'));
        }
        // Set the validation rules
        $rules = [
            'email'     => "required|max_length[254]|valid_email|is_unique[auth_identities.secret,user_id,$id]",
            'username'  => "required|alpha_numeric_space|max_length[30]|min_length[3]|regex_match[/\A[a-zA-Z0-9\.]+\z/]',|is_unique[users.username,id,$id]",
        ];

        // If a password is provided, validate it
        if ($this->request->getPost('password')) {
            $rules['password']          = 'required|max_byte[72]|strong_password';
            $rules['password_confirm']  = 'required|matches[password]';
        }

        // Validate the data
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update the user data
        $user->fill([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
        ]);

        // Update the password if provided
        if ($this->request->getPost('password')) {
            $user->fill([
                'password' => $this->request->getPost('password'),
            ]);
        }

        // Save the user
        $this->users->save($user);

        // Update the groups if the user is an admin
        if (auth()->user()->inGroup('admin')) {
            // Get the selected groups
            $selectedGroups = $this->request->getPost('groups');

            // Get all the groups
            $arrGroups      = $this->groups;

            // Remove the old groups in database
            foreach ($arrGroups as $group=>$detail) {
                $user->removeGroup($group);
            }
            // Add the new groups in database
            if (!empty($selectedGroups)) {
                foreach($selectedGroups as $group) {
                    $user->addGroup($group);
                }
            } else {
                // By default, add to the "user" group
                $this->users->addToDefaultGroup($user);
            }
        }
        // Redirect to users list and send a success message
        return redirect()->to('/users')
            ->with('message', lang('User.update_success'));
    }

    /**
     * Disable a user
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function disable($id)
    {
        // Get the user
        $user = $this->users->findById($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->to('/users')
                ->with('error', lang('User.not_found'));
        }

        // Prevent disabling own account
        if ($id == auth()->id()) {
            return redirect()->to('/users')
                ->with('error', lang('User.disable_denied'));
        }

        // Disable the user
        $user->deactivate();

        // Redirect to users list and send a success message
        return redirect()->to('/users')
            ->with('message', lang('User.disable_success'));
    }

    /**
     * Enable a user
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function enable($id)
    {
        // Get the user
        $user = $this->users->findById($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->to('/users')
                ->with('error', lang('User.not_found'));
        }

        // Activate the user
        $user->activate();

        // Redirect to users list and send a success message
        return redirect()->to('/users')
            ->with('message', lang('User.enable_success'));
    }

    /**
     * Delete a user
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        // Only admins can delete users
        if (!auth()->user()->inGroup('admin')) {
            return redirect()->to('/users')->with('error', lang('User.access_denied'));
        }

        // Get the user
        $user = $this->users->findById($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->to('/users')
                ->with('error', lang('User.not_found'));
        }

        // Prevent deleting own account
        if ($id == auth()->id()) {
            return redirect()->to('/users')
                ->with('error', lang('User.delete_denied'));
        }

        // Delete the user
        $this->users->delete($id, false);

        // Redirect to users list and send a success message
        return redirect()->to('/users')
            ->with('message', lang('User.delete_success'));
    }

    /**
     * Display the user profile
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function profile()
    {
        echo 'ici';
        // Get the user
        $user = auth()->user();

        // Set the user and the groups for the view
        $data = [
            'user'      => $user,
            'groups'    => $this->groups,
        ];

        // Display the profile view
        return view('users/profile', $data);
    }

    /**
     * Process the profile update
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updateProfile()
    {
        // Get the user
        $user = auth()->user();

        $id = $user->id;

        // Validation des données
        $rules = [
            'email'     => "required|max_length[254]|valid_email|is_unique[auth_identities.secret,user_id,$id]",
            'username'  => "required|alpha_numeric_space|max_length[30]|min_length[3]|regex_match[/\A[a-zA-Z0-9\.]+\z/]',|is_unique[users.username,id,$id]",
        ];

        // Si un mot de passe est fourni, le valider
        if ($this->request->getPost('password')) {
            $rules['password']          = 'required|max_byte[72]|strong_password';
            $rules['password_confirm'] = 'required|matches[password]';
            $rules['current_password'] = 'required';

            // Vérifier que le mot de passe actuel est correct
            if (!password_verify($this->request->getPost('current_password'), $user->password_hash)) {
                return redirect()->back()->with('error', lang('User.current_password_invalid'));
            }
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Mise à jour des données utilisateur
        $user->fill([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
        ]);

        // Mise à jour du mot de passe si fourni
        if ($this->request->getPost('password')) {
            $user->fill([
                'password' => $this->request->getPost('password'),
            ]);
        }

        // Enregistrement des modifications
        $this->users->save($user);

        return redirect()->to('/profile')
            ->with('message', lang('User.update_profile_success'));
    }

    /**
     * Display the forgot password form
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function forgotPassword()
    {
        return view('users/forgot_password');
    }

    /**
     * Forgot password process
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function processForgotPassword()
    {
        // Get the email from the form
        $email = $this->request->getPost('email');

        // Data validation
        if (!$this->validate([
            'email' => 'required|valid_email'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check if the user exists by email
        $user = $this->users->findByCredentials(['email' => $email]);
        if (!$user) {
            // If the user does not exist, redirect to the login page with a message (do not specify if the user exists)
            return redirect()->to('/login')
                ->with('message', lang('User.if_exists'));
        }

        // Revoke all access tokens for the user
        $user->revokeAllAccessTokens();
        //$expiresAt = Time::now()->addMinutes(15);
        // Create a new access token for the user
        $token = $user->generateAccessToken('forgot_password');

        // Send the user an email with the token
        $email = service('email');
        $email->setTo($user->email);
        $email->setSubject(lang('User.subject_reset_password'));
        $message = view('users/emails/'.service('request')->getLocale().'/reset_password', [
            'token' => $token->raw_token,
            'resetUrl' => site_url('reset-password') . '?token=' . $token->raw_token
        ]);
        $email->setMessage($message);

        // Send the email
        if ($email->send() === false) {
            // Log the error
            log_message('error', $email->printDebugger(['headers']));
            // Redirect to the forgot password page with an error message
            return redirect()->route('forgot-password')->with('errors', lang('Auth.unableSendEmailToUser', [$user->email]));
        }

        // Redirect to the login page with a message (do not specify if the user exists)
        return redirect()->to('/login')
            ->with('message', lang('User.if_exists'));
    }

    /**
     * Display the reset password form
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function resetPassword()
    {
        // Get the token from the URL
        $token = $this->request->getGet('token');

        // Check if the token is valid
        if (empty($token)) {
            return redirect()->to('/login')
                ->with('error', lang('User.invalid_token'));
        }
        // Get the user by token
        $identity   = new UserIdentityModel();
        $user       = $identity->getAccessTokenByRawToken($token);

        // Check if the user exists
        if (!$user) {
            return redirect()->to('/login')
                ->with('error', lang('User.invalid_expired_token'));
        }

        // set the token for the view
        $data = [
            'token' => $token
        ];
        // Get the view
        return view('users/reset_password', $data);
    }

    /**
     * Process the reset password
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function processResetPassword()
    {
        // Get the token and password from the form
        $token              = $this->request->getPost('token');
        $password           = $this->request->getPost('password');

        // Get the user by token
        $identity   = new UserIdentityModel();
        $user       = $identity->getAccessTokenByRawToken($token);

        // Check if the user exists
        if (!$user) {
            return redirect()->to('/login')
                ->with('error', lang('User.invalid_expired_token'));
        }

        // Get the user by ID
        $user       = $this->users->findById($user->user_id);

        // Add the email to the POST data for validation
        $_POST['email'] = $user->email;
        $this->request->setGlobal('post', $_POST);

        // Validation rules
        $rules = [
            'password'            => "required|max_byte[72]|strong_password",
            'password_confirm'    => "required|matches[password]",
            'token'               => "required",
        ];

        // Validate the data
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update the user password
        $user->fill([
            'password' => $password,
        ]);

        // Save the user
        $this->users->save($user);

        // Redirect to the login page with a success message
        return redirect()->to('/login')
            ->with('message', lang('User.update_password_success'));
    }
}