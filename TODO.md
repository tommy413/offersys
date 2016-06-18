# TODO

## API
1. App\Action\HomeAction
    + dispatch()
        * method: GET
        * return: render /
    + export()
        * method: POST
        * params: company, office, account, start, end
        * return: csv file
    + punch()
        * method: POST
        * params: account, state, note
        * return: redirect to /
    + getUpdateList()
        * method: GET
        * params: account
        * return: [[$item_num, $state, $note, $time]]
    + update()
        * method: POST
        * params: account, old_item(-1), new_state, new_note, new_time(Y/m/d H:i:s)

2. App\Action\LoginAction
    + dispatch()
        * method: GET
        * return: render /login
    + login()
        * method: POST
        * params: account, password
        * return: redirect to /

3. App\Action\LogoutAction
    + logout()
        * return: redirect to /login

4. App\Action\SignupAction
    + signup()
        * method: POST
        * params: company, office, account, password
        * return: redirect to /