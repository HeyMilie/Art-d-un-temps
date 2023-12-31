/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
//import { Tooltip, Toast, Popover } from 'bootstrap';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/alert';


const $ = require("jquery");
global.$ = global.jQuery = $;
window.Popper = require("popper.js");
require("bootstrap");

//import './bootstrap';
console.log("chargement du fichier app.js");

$('.alert').alert('close');

$(document).on('change', '.custom-file-input', function () {
    let fileName = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
    $(this).parent('.custom-file').find('.custom-file-label').text(fileName);
});