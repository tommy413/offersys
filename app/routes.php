<?php
// Routes

$app->get('/', 'App\Action\HomeAction:dispatch')
    ->setName('homepage');

$app->post('/updateteam', 'App\Action\HomeAction:updateteam')
    ->setName('update_team');

$app->post('/updatebuy', 'App\Action\HomeAction:updatebuy')
    ->setName('update_buy');

$app->post('/updatesell', 'App\Action\HomeAction:updatesell')
    ->setName('update_sell');

$app->post('/updateproduce', 'App\Action\HomeAction:updateproduce')
    ->setName('update_produce');

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

$app->get('/back', 'App\Action\BackAction:dispatch')
    ->setName('backpage');

$app->get('/run', 'App\Action\BackAction:run_dispatch')
    ->setName('runpage');

