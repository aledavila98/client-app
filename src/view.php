<?php

use React\EventLoop\Factory;
use React\Socket\Connector as ReactConnector;
use Ratchet\Client\Connector;

$title = 'Test title';
$uri = $_SERVER['REQUEST_URI'];

$reverbPort = $_ENV['REVERB_PORT'];
$reverbHost = $_ENV['REVERB_HOST'];
$reverbAppId = $_ENV['REVERB_APP_ID'];
$reverbAppKey = $_ENV['REVERB_APP_KEY'];
$reverbScheme = $_ENV['REVERB_SCHEME'];

$reverbUrl = "ws://$reverbHost:$reverbPort/app/$reverbAppKey";

$loop = Factory::create();
$reactConnector = new ReactConnector($loop);
$connector = new Connector($loop, $reactConnector);

$connector($reverbUrl)->then(function($conn) {
    echo "Connected to Reverb WebSocket server\n";

    $message = json_encode([
        'event' => 'pusher:subscribe',
        'data' => [
            'auth' => '',
            'channel' => 'pingme'
        ]
    ]);

    $conn->send($message);

    $conn->on('message', function($msg) {
        echo "Received: $msg\n";
    });

    $conn->on('close', function($code = null, $reason = null) {
        echo "Connection closed ({$code} - {$reason})\n";
    });
}, function($e) {
    echo "Could not connect: {$e->getMessage()}\n";
});

$loop->run();

// echo "
// <!DOCTYPE html>
// <html lang='en'>
// <head>
//     <meta charset='UTF-8'>
//     <meta http-equiv='X-UA-Compatible' content='IE=edge'>
//     <meta name='viewport' content='width=device-width, initial-scale=1.0'>
//     <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
//     <title>$uri</title>
// </head>
// <body>
//     <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script>
//     <script src='https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js'></script>
//     <h1>$title</h1>
//     <div class='container'>
//         <div class='row'>
//             <div class='col'>
//                 <div class='form-group'>
//                     <label for='test-message1'>Test 1</label>
//                     <input type='text' class='form-control' id='test-message1' name='test-message1'>
//                 </div>
//             </div>
//             <div class='col'>
//                 <div class='form-group'>
//                     <label for='test-message2'>Test 2</label>
//                     <input type='text' class='form-control' id='test-message2' name='test-message2'>
//                 </div>
//             </div>
//         </div>
//         <div class='row'>
//             <div class='col mt-2'>
//                 <button type='button' class='btn btn-primary' id='test-btn-1'>Primary</button>
//             </div>
//             <div class='col mt-2'>
//                 <button type='button' class='btn btn-secondary'>Secondary</button>
//             </div>
//         </div>
//         <div class='row'>
//             <div class='col'><span id='text-1'></span></div>
//         </div>
//     </div>
//     <script type='text/javascript'>
//         document.addEventListener('DOMContentLoaded', function() {
//             const socket = new WebSocket('$reverbUrl');
//             const csrfToken = document.cookie.split('; ').find(row => row.startsWith('csrfToken=')).split('=')[1];
//             console.log('CSRF Token', csrfToken);
//             socket.addEventListener('open', function(event) {
//                 console.log('Connected to the server');
//                 // Subscribe to the channel
//                 const subscribeMessage = JSON.stringify({
//                     event: 'pusher:subscribe',
//                     data: {
//                         auth: '',
//                         channel: 'pingme'
//                     }
//                 });
//                 socket.send(subscribeMessage);
//             });

//             socket.addEventListener('message', function(event) {
//                 const eventobj = JSON.parse(event.data);
//                 console.log(eventobj);
//                 if (eventobj.event && eventobj.event === 'pusher_internal:subscription_succeeded') {
//                     console.log('Subscribed to the channel');
//                 }
//                 if (eventobj.event && eventobj.event === 'App\\\\Events\\\\PingEvent') {
//                     document.getElementById('text-1').innerText = event.data;
//                     console.log('Message from the server', event.data);
//                 }   
//             });

//             document.getElementById('test-btn-1').addEventListener('click', function(event) {
//                 const message = document.getElementById('test-message1').value;
//                 const csrfToken = document.getElementById('csrf-token').value;

//                 axios.post('http://localhost:8000/broadcast', 
//                 {
//                     event: 'PingEvent',
//                     data: JSON.stringify({
//                         message: message,
//                     })
//                 }, {
//                     headers: {
//                         'Content-Type': 'application/json',
//                         'X-CSRF-TOKEN': csrfToken
//                     }
//                 })
//                 .then(function (response) {
//                     console.log('Message sent successfully', response.data);
//                 })
//                 .catch(function (error) {
//                     console.error('Error sending message', error);
//                 });

//                 socket.send(message);
//             });
//         });
//     </script>
// </body>
// </html>
// ";
?>