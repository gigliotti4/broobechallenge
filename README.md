1. Clonar el proyecto 'git clone https://github.com/gigliotti4/broobechallenge.git'
2. Tirar el comando Composer install en una terminal y el php artisan key:generate
3. En el archivo .env agregar el nombre de la base de datos Broobechallenge y crearla
4. Agregar en el .env GOOGLE_API_KEY=AIzaSyA-2ORoq3Hqo5HT1ATih7GdwjmpP5sU2b0
5. Tirar php artisan db:seed o php artisan db:seed --class=CategoriesTableSeeder, php artisan db:seed --class=StrategiesTableSeeder
6. Tirar php artisan migrate
