<head>
    <title>LeaseWeb Tech Summit Demo</title>
    <style type="text/css">
        body {
            font-size: 24px;
        }

        input {
            font-size: 24px;
        }
    </style>
</head>
<body>
<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$client = new Predis\Client(getenv('DB_PORT'));

// maintain a count
$count = ($client->exists('count') ? $client->get('count') : 1);

// name change
$app->post('/name', function() use($client, $app) {

    $name = 'stranger';

    if ($app->request->post('name')) {
        $name = $app->request->post('name');
    }

    $client->set('name', $name);
    display("Name changed to $name");
    echo '<br /><br />';
    display('<a href="/">Back</a>', 'h2');
});

// display a set name
$app->get('/', function () use($client, $count){
    $name = ($client->exists('name') ?  $client->get('name') : 'stranger');
    display(sprintf("Hello, %s! You have visited me %s time(s).", $name, $count));
    display(sprintf('<br />PS: i\'m running on %s', getenv('TUTUM_NODE_HOSTNAME')), 'h2');
    ?>
    <form action="/name" method="post">
        <input type="text" size="25" placeholder="stranger" name="name"/>
        <input type="submit" value="Change name">
    </form>
    <?php
});

// increase the counter
$client->incr('count');

// run the app
$app->run();

function display($text, $header = 'h1')
{
    echo sprintf('<%s>%s</%s>', $header, $text, $header);
}
?>
</body>