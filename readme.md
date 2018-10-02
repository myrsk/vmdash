# vmDash
**A cloud (vm) Dashboard that allows you to interact with multiple providers from a single panel.** 
Built on top of [Laradminator](https://github.com/kossa/laradminator) ([Laravel](https://laravel.com) & [Adminator](https://github.com/puikinsh/Adminator-admin-dashboard))

Please be advised that the dashboard is still very new (BETA) - with many changes happening, and there could be bugs or issues. If you find anything that we missed, [open a new issue](https://github.com/myrsk/vmdash/issues).

## Screenshots
Will be added soon.

## Integrated Provider Features
|Provider|Reboot|Shutdown|Power On|Root Password Reset|Enable Rescue|Disable Rescue|Reinstall OS|Attach ISO|Remove ISO|VNC Console|
|-|-|-|-|-|-|-|-|-|-|-|
|Hetzner Cloud|✔|✔|✔|✔|✔|✔|✔|✔|✔| |
|Vultr|✔|✔|✔| | | |✔|✔|✔|✔|
|Scaleway (Paris)|✔|✔|✔| | | | | | | |
|Scaleway (Amsterdam)|✔|✔|✔| | | | | | | |
|Digital Ocean|✔|✔|✔| | | |✔| | | |

## Roadmap (future releases)
- Auto update functionality vmDash from Github releases
- Server monitoring plugin (reports uptime, and state of machine and notifies user)
- Multi-user environment
- Graphs from supported providers
- Adding a dashboard overview page (for VM Hobbyists & Collectors with maps, insights and statistics)
- Adding additional providers
- Improve code

## Requirements
- A Webserver
- A Database Server (Mysql or MariaDB)
- PHP7.1+
- [Laravel Requirements](https://laravel.com/docs/5.6/installation#server-requirements)

## Installation Steps
```bash
git clone https://github.com/myrsk/vmdash.git
cd vmdash 
composer install                   # Install backend dependencies
sudo chmod 777 storage/            # Chmod Storage
cp .env.example .env               # Update database credentials configuration
php artisan key:generate           # Generate new keys for Laravel
php artisan migrate:fresh --seed   # Run migration and seed user for initial login
npm i                              # Installs node dependencies
npm run production                 # Compile assets for production
```
##### Default Login Credentials
Username: test@example.com      
Password: 123456

_**Note**: Please change your email and password as soon as you login_

##### HTTPS Reminder
If you are running **vmDash** in a production environment, please make sure you are accessing the dashboard using HTTPS to avoid any MITM attacks and the leakage of sensitive data

## Demo
To run the demo on your own machine run the following command (for testing purposes only)
```bash
php artisan serve
```
Visit the dashboard at [localhost:8000/](http://localhost:8000/) 

## Included Packages
#### Laravel & PHP:

* [Laradminator](https://github.com/kossa/laradminator) (vmDash is built on top of Laradminator)
* [Laravel Framework](https://github.com/laravel/laravel/) (5.6.*)
* [Forms & HTML](https://github.com/laravelcollective/html) : for forms
* [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar) : for debugging
* [rinvex/country](https://github.com/rinvex/country) : lightweight package for country data
* [thomaswelton/laravel-gravatar](https://github.com/thomaswelton/laravel-gravatar) : gravatar package used for logged-in user's profile picture
* [Guzzle](https://github.com/guzzle/guzzle) : for API calls
* [PHPSecLib](https://github.com/phpseclib/phpseclib) : for SSH calls

#### JS plugins:

* All ADMINATOR plugins [here](https://github.com/puikinsh/Adminator-admin-dashboard#built-with)
* [sweetalert2](https://github.com/limonte/sweetalert2)
* [Axios](https://github.com/mzabriskie/axios)
* [nprogress](https://github.com/rstacruz/nprogress)
* [clipboard.js](https://github.com/zenorocha/clipboard.js/)


#### Need help? Want to report an issue?
[Open a new issue](https://github.com/myrsk/vmdash/issues) 
