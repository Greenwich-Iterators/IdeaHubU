<!DOCTYPE html>
<html>
    <head>
        <title>PHP Test</title>
    </head>
    <body>
        <?php 
        echo '<p>Hello World From PHP</p>';
        $test = 'http://localhost:9000/api/test'; 
        $json_api_url = 'http://localhost:9000/api/json_test';

        // Fetch JSON data from the API
        $json_data = file_get_contents($json_api_url);
        $test_data = file_get_contents($test);
        echo $test_data;
        echo '<br>';
        echo $json_data;
        ?>

        
        
    </body>
</html>