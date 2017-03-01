<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Network\Http\Client;
use Cake\Http\Client\Response;
use GuzzleHttp;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Cake\Core\Configure;

class AuthController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('Users');
        if (!Configure::read('Site.Registration.SocialLogin')) {
            $messaage = __('Social Login is not activated at this movement.');
            echo json_encode(['status' => false, 'message' => $messaage]);
            exit;
        }
        $this->Auth->allow(['facebook', 'google', 'twitter', 'google']);
    }

    /**
     * Login with Facebook.
     */
    public function facebook() {
        $client = new Client();

        $params = [
        'code' => $this->request->data('code'),
        'client_id' => $this->request->data('clientId'),
        'redirect_uri' => $this->request->data('redirectUri'),
        'client_secret' => '7acb9c5af7cdab11c04c93af23cc54fa'
        ];

        // Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->get('https://graph.facebook.com/v2.5/oauth/access_token', $params);
        $accessToken = json_decode($accessTokenResponse->body(), true);

        // Step 2. Retrieve profile information about the current user.
        $fields = 'id,email,first_name,last_name,link,name,gender,picture';
        $profileResponse = $client->get('https://graph.facebook.com/v2.5/me', [
            'access_token' => $accessToken['access_token'],
            'fields' => $fields
            ]);
        $profile = json_decode($profileResponse->body(), true);

        if (!$profile) {
            echo json_encode(array('status' => false));
            exit;
        }

        if ($this->loggedIn) {
            $user = $this->Users->find()->where(['Users.facebook_id' => $profile['id']]);
        } else {
            $user = $this->Users->find()->where(['OR' => ['Users.facebook_id' => $profile['id'], 'Users.facebook_email' => $profile['email'], 'Users.email' => $profile['email']]]);
        }
        $status = false;
        if ($user = $user->first()) {
            if ($this->loggedIn['facebook_id'] && $this->loggedIn['facebook_id'] != $user->facebook_id) {
                echo json_encode(['status' => false, 'message' => 'This facebook account is connected to another account.']);
                exit;
            }
            $status = true;
            $userUpdate = $this->Users->newEntity();
            if (!$user->facebook_id) {
                $userUpdate->facebook_id = $profile['id'];
                $userUpdate->id = $user->id;
                if ($profile['email']) {
                    $userUpdate->facebook_email = $profile['email'];
                }
                if ($this->Users->save($userUpdate)) {
                    $status = true;
                } else {
                    $status = false;
                }
            }
        } else {

            $user = $this->Users->newEntity();
            if ($this->loggedIn['id']) {
                if ($profile['email'] && !$this->loggedIn['email']) {
                    $user->email = $profile['email'];
                }
                if ($profile['email']) {
                    $user->facebook_email = $profile['email'];
                }
                $user->id = $this->loggedIn['id'];
                $session = $this->request->session();
                $session->write('Auth.User.facebook_id', $profile['id']);
            } else {
                $user->first_name = $profile['first_name'];
                $user->last_name = $profile['last_name'];
                $user->nick_name = $profile['name'];
                $user->image = 'https://graph.facebook.com/' . $profile['id'] . '/picture?type=large&w‌​idth=720&height=720';
                if ($profile['gender'] == 'male') {
                    $user->gender = 'M';
                }
                if ($profile['gender'] == 'female') {
                    $user->gender = 'F';
                }
                if ($profile['email']) {
                    $user->facebook_email = $profile['email'];
                    $user->email = $profile['email'];
                }
            }
            $user->facebook_id = $profile['id'];



            if ($this->Users->save($user)) {
                $status = true;
            }
        }
        if ($status && !$this->loggedIn) {
            $user = $this->Users->find('all', [
                'conditions' => ['Users.id' => $user->id],
                'contain' =>
                [
                'Countries' => ['fields' => ['Countries.name']],
                'Avatars'
                ]
                ]
                )->first();

            $this->Auth->setUser($user->toArray());
        }

        $this->loadModel('RoomUsers');
        $room = $this->RoomUsers->getMostAccupantRoom();

        $redirectTo = \Cake\Routing\Router::url(['controller' => 'rooms','action' => 'view','slug' => $room], true);

        echo json_encode(array('status' => $status,'redirectTo' => $redirectTo));
        exit;
    }

    /**
     * Login with Google.
     */
    public function google() {
        $client = new Client();

        $params = [
        'code' => $this->request->data('code'),
        'client_id' => $this->request->data('clientId'),
        'client_secret' => 'ljJt4-nQqG8oHm6VAbGNpvGu',
        'redirect_uri' => $this->request->data('redirectUri'),
        'grant_type' => 'authorization_code',
        ];

// Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->post('https://accounts.google.com/o/oauth2/token', $params);


        $accessToken = json_decode($accessTokenResponse->body(), true);

// Step 2. Retrieve profile information about the current user.
        $http = new Client([
            'headers' => ['Authorization' => 'Bearer ' . $accessToken['access_token']]
            ]);
        $profileResponse = $http->get('https://www.googleapis.com/plus/v1/people/me/openIdConnect');

        $profile = json_decode($profileResponse->body(), true);
        if (!$profile) {
            echo json_encode(array('status' => false));
            exit;
        }
// Step 3a. If user is already signed in then link accounts.

        $user = $this->Users->find()->where(['OR' => ['Users.google_id' => $profile['sub'], 'Users.google_email' => $profile['email'], 'Users.email' => $profile['email']]]);

        $status = false;
        if ($user = $user->first()) {
            if (!empty($this->loggedIn['google_id']) && $this->loggedIn['google_id'] != $user->google_id) {
                echo json_encode(['status' => false, 'message' => 'This google account is connected to another account.']);
                exit;
            }
            $userUpdate = $this->Users->newEntity();
            $status = true;
            if (!$user->google_id) {
                $userUpdate->google_id = $profile['sub'];
                $userUpdate->id = $user->id;
                if ($profile['email']) {
                    $userUpdate->google_email = $profile['email'];
                }
                if (!$this->Users->save($userUpdate)) {
                    $status = false;
                }
            }
        } else {
            $user = $this->Users->newEntity();
            if ($this->loggedIn['id']) {
                if (!empty($profile['email']) && !$this->loggedIn['email']) {
                    $user->email = $profile['email'];
                }
                if (!empty($profile['email'])) {
                    $user->google_email = $profile['email'];
                }
                $user->id = $this->loggedIn['id'];
                $session = $this->request->session();
                $session->write('Auth.User.google_id', $profile['sub']);
            } else {
                if (!empty($profile['email'])) {
                    $user->google_email = $profile['email'];
                    $user->email = $profile['email'];
                }
                $user->first_name = $profile['name'];
                $user->image = $profile['picture'];
            }
            $user->google_id = $profile['sub'];
            if ($this->Users->save($user)) {
                $status = true;
            }
        }
        if ($status) {
            $this->Auth->setUser($user->toArray());
        }

        $this->loadModel('RoomUsers');
        $room = $this->RoomUsers->getMostAccupantRoom();

        $redirectTo = \Cake\Routing\Router::url(['controller' => 'rooms','action' => 'view','slug' => $room], true);

        echo json_encode(array('status' => $status,'redirectTo' => $redirectTo));
        exit;
    }

    /**
     * Login with LinkedIn.
     */
    public function linkedin(Request $request) {
        $client = new GuzzleHttp\Client();

        $params = [
        'code' => $request->input('code'),
        'client_id' => $request->input('clientId'),
        'client_secret' => Config::get('app.linkedin_secret'),
        'redirect_uri' => $request->input('redirectUri'),
        'grant_type' => 'authorization_code',
        ];

// Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->request('POST', 'https://www.linkedin.com/uas/oauth2/accessToken', [
            'form_params' => $params
            ]);
        $accessToken = json_decode($accessTokenResponse->getBody(), true);

// Step 2. Retrieve profile information about the current user.
        $profileResponse = $client->request('GET', 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address)', [
            'query' => [
            'oauth2_access_token' => $accessToken['access_token'],
            'format' => 'json'
            ]
            ]);
        $profile = json_decode($profileResponse->getBody(), true);

// Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization')) {
            $user = User::where('linkedin', '=', $profile['id']);

            if ($user->first()) {
                return response()->json(['message' => 'There is already a LinkedIn account that belongs to you'], 409);
            }

            $token = explode(' ', $request->header('Authorization'))[1];
            $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));

            $user = User::find($payload['sub']);
            $user->linkedin = $profile['id'];
            $user->displayName = $user->displayName ? : $profile['firstName'] . ' ' . $profile['lastName'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
// Step 3b. Create a new user account or return an existing one.
        else {
            $user = User::where('linkedin', '=', $profile['id']);

            if ($user->first()) {
                return response()->json(['token' => $this->createToken($user->first())]);
            }

            $user = new User;
            $user->linkedin = $profile['id'];
            $user->displayName = $profile['firstName'] . ' ' . $profile['lastName'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
    }

    /**
     * Login with Twitter.
     */
    public function twitter() {
        $stack = GuzzleHttp\HandlerStack::create();

// Part 1 of 2: Initial request from Satellizer.
        if (!$this->request->data('oauth_token') || !$this->request->data('oauth_verifier')) {
            $stack = GuzzleHttp\HandlerStack::create();

            $requestTokenOauth = new Oauth1([
                'consumer_key' => 'ytL2KsoLcygTKwRv2qEQkGlnz',
                'consumer_secret' => 'm4SGeLCO83Vk78PryvH0H2vivyks5npln1f0TQcAtylI7E8OqL',
                'callback' => $this->request->data('redirectUri'),
                'token' => '',
                'token_secret' => ''
                ]);

            $stack->push($requestTokenOauth);

            $client = new GuzzleHttp\Client([
                'handler' => $stack
                ]);

// Step 1. Obtain request token for the authorization popup.
            $requestTokenResponse = $client->request('POST', 'https://api.twitter.com/oauth/request_token', [
                'auth' => 'oauth'
                ]);

            $oauthToken = array();
            parse_str($requestTokenResponse->getBody(), $oauthToken);

// Step 2. Send OAuth token back to open the authorization screen.
            echo json_encode($oauthToken);
            exit;
        }
// Part 2 of 2: Second request after Authorize app is clicked.
        else {
            $accessTokenOauth = new Oauth1([
                'consumer_key' => 'ytL2KsoLcygTKwRv2qEQkGlnz',
                'consumer_secret' => 'm4SGeLCO83Vk78PryvH0H2vivyks5npln1f0TQcAtylI7E8OqL',
                'token' => $this->request->data('oauth_token'),
                'verifier' => $this->request->data('oauth_verifier'),
                'token_secret' => ''
                ]);
            $stack->push($accessTokenOauth);

            $client = new GuzzleHttp\Client([
                'handler' => $stack
                ]);

// Step 3. Exchange oauth token and oauth verifier for access token.
            $accessTokenResponse = $client->request('POST', 'https://api.twitter.com/oauth/access_token', [
                'auth' => 'oauth'
                ]);

            $accessToken = array();
            parse_str($accessTokenResponse->getBody(), $accessToken);

            $profileOauth = new Oauth1([
                'consumer_key' => 'ytL2KsoLcygTKwRv2qEQkGlnz',
                'consumer_secret' => 'm4SGeLCO83Vk78PryvH0H2vivyks5npln1f0TQcAtylI7E8OqL',
                'oauth_token' => $accessToken['oauth_token'],
                'token_secret' => ''
                ]);
            $stack->push($profileOauth);

            $client = new GuzzleHttp\Client([
                'handler' => $stack
                ]);

// Step 4. Retrieve profile information about the current user.
            $profileResponse = $client->request('GET', 'https://api.twitter.com/1.1/users/show.json?screen_name=' . $accessToken['screen_name'], [
                'auth' => 'oauth'
                ]);
            $profile = json_decode($profileResponse->getBody(), true);

// Step 5a. Link user accounts.
            $conditions = ['Users.twitter_id' => $profile['id']];
            if (!empty($profile['email'])) {
                $conditions[] = ['Users.twitter_email' => $profile['email']];
            }

            $user = $this->Users->find()->where(['OR' => $conditions]);

            $status = false;
            if ($user = $user->first()) {
                if (!empty($this->loggedIn['twitter_id']) && $this->loggedIn['twitter_id'] != $user->twitter_id) {
                    echo json_encode(['status' => false, 'message' => 'This twitter account is connected to another account.']);
                    exit;
                }
                $userUpdate = $this->Users->newEntity();
                $status = true;
                if (!$user->twitter_id) {
                    $userUpdate->twitter_id = $profile['id'];
                    $userUpdate->id = $user->id;
                    if (!empty($profile['email'])) {
                        $userUpdate->twitter_email = $profile['email'];
                    }
                    if (!$this->Users->save($userUpdate)) {
                        $status = false;
                    }
                }
            } else {
                $user = $this->Users->newEntity();
                if ($this->loggedIn['id']) {
                    if (!empty($profile['email']) && $profile['email'] && !$this->loggedIn['email']) {
                        $user->email = $profile['email'];
                    }
                    if (!empty($profile['email'])) {
                        $user->twitter_email = $profile['email'];
                    }
                    $user->id = $this->loggedIn['id'];
                    $session = $this->request->session();
                    $session->write('Auth.User.twitter_id', $profile['id']);
                } else {
                    $user->nick_name = $profile['screen_name'];
                    if (!empty($profile['email'])) {
                        $user->twitter_email = $profile['email'];
                        $user->email = $profile['email'];
                    }
                    $user->first_name = $profile['name'];
                }
                $user->twitter_id = $profile['id'];
                if ($this->Users->save($user)) {
                    $status = true;
                }
            }
            if ($status) {
                $this->Auth->setUser($user->toArray());
            }

            $this->loadModel('RoomUsers');
            $room = $this->RoomUsers->getMostAccupantRoom();

            $redirectTo = \Cake\Routing\Router::url(['controller' => 'rooms','action' => 'view','slug' => $room], true);

            echo json_encode(array('status' => $status,'redirectTo' => $redirectTo));
            exit;
        }
    }

    /**
     * Login with Foursquare.
     */
    public function foursquare(Request $request) {
        $client = new GuzzleHttp\Client();

        $params = [
        'code' => $request->input('code'),
        'client_id' => $request->input('clientId'),
        'client_secret' => Config::get('app.foursquare_secret'),
        'redirect_uri' => $request->input('redirectUri'),
        'grant_type' => 'authorization_code',
        ];

// Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->request('POST', 'https://foursquare.com/oauth2/access_token', [
            'form_params' => $params
            ]);
        $accessToken = json_decode($accessTokenResponse->getBody(), true);

// Step 2. Retrieve profile information about the current user.
        $profileResponse = $client->request('GET', 'https://api.foursquare.com/v2/users/self', [
            'query' => [
            'v' => '20140806',
            'oauth_token' => $accessToken['access_token']
            ]
            ]);

        $profile = json_decode($profileResponse->getBody(), true)['response']['user'];

// Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization')) {
            $user = User::where('foursquare', '=', $profile['id']);
            if ($user->first()) {
                return response()->json(array('message' => 'There is already a Foursquare account that belongs to you'), 409);
            }

            $token = explode(' ', $request->header('Authorization'))[1];
            $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));

            $user = User::find($payload['sub']);
            $user->foursquare = $profile['id'];
            $user->displayName = $user->displayName ? : $profile['firstName'] . ' ' . $profile['lastName'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
// Step 3b. Create a new user account or return an existing one.
        else {
            $user = User::where('foursquare', '=', $profile['id']);

            if ($user->first()) {
                return response()->json(['token' => $this->createToken($user->first())]);
            }

            $user = new User;
            $user->foursquare = $profile['id'];
            $user->displayName = $profile['firstName'] . ' ' . $profile['lastName'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
    }

    /**
     * Login with Instagram.
     */
    public function instagram(Request $request) {
        $client = new GuzzleHttp\Client();

        $params = [
        'code' => $request->input('code'),
        'client_id' => $request->input('clientId'),
        'client_secret' => Config::get('app.instagram_secret'),
        'redirect_uri' => $request->input('redirectUri'),
        'grant_type' => 'authorization_code',
        ];

// Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
            'body' => $params
            ]);
        $accessToken = json_decode($accessTokenResponse->getBody(), true);

// Step 2a. If user is already signed in then link accounts.
        if ($request->header('Authorization')) {
            $user = User::where('instagram', '=', $accessToken['user']['id']);
            if ($user->first()) {
                return response()->json(array('message' => 'There is already an Instagram account that belongs to you'), 409);
            }

            $token = explode(' ', $request->header('Authorization'))[1];
            $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));

            $user = User::find($payload['sub']);
            $user->instagram = $accessToken['user']['id'];
            $user->displayName = $user->displayName ? : $accessToken['user']['username'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
// Step 2b. Create a new user account or return an existing one.
        else {
            $user = User::where('instagram', '=', $accessToken['user']['id']);

            if ($user->first()) {
                return response()->json(['token' => $this->createToken($user->first())]);
            }

            $user = new User;
            $user->instagram = $accessToken['user']['id'];
            $user->displayName = $accessToken['user']['username'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
    }

    /**
     * Login with GitHub.
     */
    public function github(Request $request) {
        $client = new GuzzleHttp\Client();

        $params = [
        'code' => $request->input('code'),
        'client_id' => $request->input('clientId'),
        'client_secret' => Config::get('app.github_secret'),
        'redirect_uri' => $request->input('redirectUri')
        ];

// Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->request('GET', 'https://github.com/login/oauth/access_token', [
            'query' => $params
            ]);

        $accessToken = array();
        parse_str($accessTokenResponse->getBody(), $accessToken);

// Step 2. Retrieve profile information about the current user.
        $profileResponse = $client->request('GET', 'https://api.github.com/user', [
            'headers' => ['User-Agent' => 'Satellizer'],
            'query' => $accessToken
            ]);
        $profile = json_decode($profileResponse->getBody(), true);

// Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization')) {
            $user = User::where('github', '=', $profile['id']);

            if ($user->first()) {
                return response()->json(['message' => 'There is already a GitHub account that belongs to you'], 409);
            }

            $token = explode(' ', $request->header('Authorization'))[1];
            $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));

            $user = User::find($payload['sub']);
            $user->github = $profile['id'];
            $user->displayName = $user->displayName ? : $profile['name'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
// Step 3b. Create a new user account or return an existing one.
        else {
            $user = User::where('github', '=', $profile['id']);

            if ($user->first()) {
                return response()->json(['token' => $this->createToken($user->first())]);
            }

            $user = new User;
            $user->github = $profile['id'];
            $user->displayName = $profile['name'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
    }

}
