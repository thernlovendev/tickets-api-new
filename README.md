Setup API:

1. Clone Repository.

2. Clone file `.env.example` to `.env`.

3. Configure google 

3.1 Go to configurate credentials in https://console.cloud.google.com/apis/dashboard

3.2 Update the credentials in file .env

GOOGLE_OAUTH_ID= Client ID
GOOGLE_OAUTH_KEY= Client secret
GOOGLE_REDIRECT_URI={{url of server}}/auth/google/callback

4. Configure Naver

4.1 Go to configurate credentials in https://developers.naver.com/apps/#/myapps/

4.2 Update the credentials in file .env

NAVER_CLIENT_ID= Client ID
NAVER_CLIENT_SECRET= Client secret
NAVER_REDIRECT_URI={{url of server}}/auth/naver/callback

5. Migrate the Database: php artisan migrate