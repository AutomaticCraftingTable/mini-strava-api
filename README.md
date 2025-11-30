# Laravel-API-setup
A convinient isolated setup of NGINX enironment. 

## Getting started
1. Copy `.env`
    ```bash
    cp .env.example .env
    ```

2. Modify `.env`
    ```bash
    # Set the port that your server is forwarding.
    HTTP_PORT=80
    ```
3. Bring your built webapp to the `dist` folder
4. Confiugure the NGINX in `environment/nginx/default.conf` to run your webapp