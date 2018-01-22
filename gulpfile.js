var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
    mix.sass('index.scss');
    // mix.sass('footer.scss');
    // mix.sass('header.scss');
    // mix.sass('style.scss');
    // mix.sass('common.scss');
    // mix.sass('post.scss');
    // mix.sass('postdetail.scss');
});