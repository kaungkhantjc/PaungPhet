# ဖူးစာ web app

# Development

- Copy `.env.example` to `.env`
- Set up mysql database and configure it in the `.env` file
    ```dotenv
    DB_DATABASE=paungphet
    DB_USERNAME=root
    DB_PASSWORD=
    ```
- Replace `APP_URL` with the URL you will use to access the app (e.g. `https://paungphet.test`)
- Install required packages and build assets

    ```shell
    composer install
    npm install
    npm run build
    
    # for images
    php artisan storage:link
    ```

### Generate language files (optional)

```shell
php artisan filament:localize --no-git
```
