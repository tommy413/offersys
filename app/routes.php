<?php
// Routes

$app->get('/', 'App\Action\HomeAction:dispatch')
    ->setName('homepage');

$app->post('/updatebuy', 'App\Action\HomeAction:updatebuy')
    ->setName('update_buy');

$app->post('/buy', 'App\Action\HomeAction:buy')
    ->setName('update_data');

$app->post('/sell', 'App\Action\HomeAction:sell')
    ->setName('update_action');

$app->post('/produce', 'App\Action\HomeAction:produce')
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
