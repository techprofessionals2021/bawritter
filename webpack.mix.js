const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js').vue().sass('resources/sass/app.scss', 'public/css/app.min.css');
// mix.sass('resources/sass/theme.scss', 'public/css/theme.min.css');
// mix.sass('resources/sass/app.scss', 'public/css/app.min.css');
// mix.sass('resources/sass/authentication.scss', 'public/css/authentication.css');
// mix.js('resources/js/theme.min.js', 'public/js');
// mix.sass('resources/sass/invoice.scss', 'public/css/invoice.css');
// mix.scripts([
//     'resources/js/theme.js',
//     'resources/js/website/jquery.slicknav.min.js',
//     'resources/js/website/main.js'
// ], 'public/js/theme.min.js');
