import _ from "lodash";
import $ from "jquery";
import * as bootstrap from "bootstrap";
import select2Factory from "select2";
import Swal from "sweetalert2";
import Quagga from "@ericblade/quagga2";
import Chart from "chart.js/auto";
import { PieChart } from "./charts/PieChart";

/**
 * We'll load jQuery and Bootstrap, which provide support for JavaScript based
 * Bootstrap features such as modals and tabs. This code may be modified to fit
 * the specific needs of your application.
 */

try {
    window._ = _;
    window.$ = window.jQuery = $;
    window.bootstrap = bootstrap;
    window.select2 = select2Factory(window, $);
} catch (e) { }

window.Swal = Swal;
window.Quagga = Quagga;
window.Chart = Chart;
window.PieChart = PieChart;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
