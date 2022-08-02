T_MODE = true;

$connection_options = [
    'live' => [
        'api_host_port' => 'https://rr-n1-tor.opensrs.net:55443',
        'api_key' => '39f78c55f61ed25e209ae3bdf09fe857dfdfe1eef6b1b636e9e72da3a10cca3b5b3be6d86c9eb8f288d3abbf80fce3b524f61bc154298f79',
        'reseller_username' => 'boracks'
    ],
    'test' => [
        'api_host_port' => 'https://horizon.opensrs.net:55443',
        'api_key' => '39f78c55f61ed25e209ae3bdf09fe857dfdfe1eef6b1b636e9e72da3a10cca3b5b3be6d86c9eb8f288d3abbf80fce3b524f61bc154298f79',
        'reseller_username' => 'boracks'
    ]
];

if ($TEST_MODE) {
    $connection_details = $connection_options['test'];
} else {
    $connection_details = $connection_options['live'];
}

$xml = <<<EOD
<?xml version='1.0' encoding='UTF-8' standalone='no' ?>
<!DOCTYPE OPS_envelope SYSTEM 'ops.dtd'>
<OPS_envelope>
<header>
    <version>0.9</version>
</header>
<body>
<data_block>
    <dt_assoc>
        <item key="protocol">XCP</item>
        <item key="action">LOOKUP</item>
        <item key="object">DOMAIN</item>
        <item key="attributes">
         <dt_assoc>
                <item key="domain">myfirstopensrsapitest.com</item>
         </dt_assoc>
        </item>
    </dt_assoc>
</data_block>
</body>
</OPS_envelope> 
EOD;

$data = [
    'Content-Type:text/xml',
    'X-Username:' . $connection_details['reseller_username'],
    'X-Signature:' . md5(md5($xml . $connection_details['api_key']) .  $connection_details['api_key']),
];

$ch = curl_init($connection_details['api_host_port']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $data);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

$response = curl_exec($ch);

echo 'Request as reseller: ' . $connection_details['reseller_username'] . "\n" .  $xml . "\n";

echo "Response\n";
echo $response . "\n";
?>
