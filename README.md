# Laravel Socialite - AWS Cognito with SSO 

## Description:
- A simple Laravel Socialite Authentication App with AWS Cognito & SSO feature integrated

## Usage

### Preparations: 
#### 1. Create AWS Cognito User Pool
      - https://www.youtube.com/watch?v=8WZmIdXZe3Q
      
      - Note: make sure `ALLOW_USER_PASSWORD_AUTH` is selected in App Client Settings
#### 2. Obtain dependencies
      - Cognito Pool ID
      - Cognito Client ID
      - Cognito Client Secret
      - Cognito Host (domain url)
      - Cognito Scopes

#### 3. Input dependencies in .env file


### Commands:
- composer install
- php artisan serve
- npm install && npm run dev (separate tab)
