<?php defined('CORE_PATH') OR exit('No direct script access allowed'); ?>
<?php header("HTTP/1.0 500 HTTP-Internal Server Error"); ?>
<html>
<head>
    <title>A PHP<?php echo $type_name ?></title>
    <style type="text/css">
        body {
            text-align: left;
            margin: auto 10%;
            font-size: 16px;
            font-style: initial;
            color: #333;
            font-family: "Fira Sans", "Source Sans Pro", Helvetica, Arial, sans-serif;
            font-weight: lighter;
            line-height: 1.5rem;
            font-weight: 400;
        }
    </style>
</head>
<body>
<div>
    Type: <?php echo $type, "\n"; ?></br>
    TypeName: <?php echo $type_name, "\n"; ?></br>
    Message: <?php echo $message, "\n"; ?></br>
    Filename: <?php echo $file, "\n"; ?></br>
    Line Number: <?php echo $line; ?></br>
</div>
</body>
</html>

