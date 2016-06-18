<?php
// Routes

$app->get('/', 'App\Action\HomeAction:dispatch')
    ->setName('homepage');

$app->post('/export', 'App\Action\HomeAction:export')
    ->setName('export_action');

$app->post('/punch', 'App\Action\HomeAction:punch')
    ->setName('punch_action');

$app->get('/update', 'App\Action\HomeAction:getUpdateList')
    ->setName('update_data');

$app->post('/update', 'App\Action\HomeAction:update')
    ->setName('update_action');

$app->get('/login', 'App\Action\LoginAction:dispatch')
    ->setName('login_page');

$app->post('/login', 'App\Action\LoginAction:login')
    ->setName('login_action');

$app->get('/logout', 'App\Action\LogoutAction:logout')
    ->setName('logout_action');

$app->get('/signup', 'App\Action\SignupAction:dispatch')
    ->setName('signup_page');

$app->post('/signup', 'App\Action\SignupAction:signup')
    ->setName('signup_action');
