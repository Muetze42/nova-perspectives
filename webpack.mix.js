let mix = require('laravel-mix')

require('./nova.mix')

mix
    .setPublicPath('dist')
    .js('resources/js/perspektive.js', 'js')
    .vue({version: 3})
    .nova('norman-huth/nova-perspectives')
    .disableNotifications()
    .version()
