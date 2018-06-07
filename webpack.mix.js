let mix = require('laravel-mix');

mix
    .disableNotifications()
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .options({
        postCss: [
            require('autoprefixer')
        ]
    });

if (mix.config.inProduction) {
    mix.version();
} else {
    // mix.browserSync({
    //     proxy:     'localhost:8000',
    //     startPath: '/'
    // });
}