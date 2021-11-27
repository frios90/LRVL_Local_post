let mix = require('laravel-mix');
mix.js('resources/assets/js/app.js', 'public/intranet/js');

mix.browserSync({ proxy: 'localhost:8000' });

