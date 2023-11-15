![screencapture-localhost-tasks-2023-11-15-10_15_53](https://github.com/mhardaniel/task-management-system/assets/26666891/ba91ee29-0abc-4cf3-8686-805473b13535)

## Requirements

https://laravel.com/docs/10.x/installation#getting-started-on-windows

-   Docker Desktop
-   WSL

## Tech Stacks used

-   Laravel
-   Inertia JS (Vue JS)
-   CSS (Tailwind)
-   Database (SQlite)

## Running

1. open your terminal
2. git clone https://github.com/mhardaniel/task-management-system.git
3. cd task-management-system
4. cp .env.example .env
5. php artisan key:generate
6. ./vendor/bin/sail up
7. php artisan migrate
8. you can access the application in your web browser at: http://localhost

## Pruning

Tasks that are moved to trash can be deleted permanently either manually or within 30
days.

-   php artisan model:prune --pretend

### Thank you, Regards

mhardaniel
