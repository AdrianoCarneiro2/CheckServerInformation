<!DOCTYPE html>
<html lang="en">

<head>
    <title>Server status</title>
    <meta content="text/html" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        pre {
            overflow-x: auto;
            max-width: 60vw;
        }

        pre code {
            word-wrap: normal;
            white-space: pre;
        }
    </style>
</head>
<html>
<div class="container">
    <?php
    //Get ram usage
    $total_mem = preg_split('/ +/', @exec('grep MemTotal /proc/meminfo'));
    $total_mem = $total_mem[1];
    $free_mem = preg_split('/ +/', @exec('grep MemFree /proc/meminfo'));
    $cache_mem = preg_split('/ +/', @exec('grep ^Cached /proc/meminfo'));

    $percentvalue = $free_mem[1] / $total_mem;
    $percentvalue = $percentvalue * 100;

    $free_mem = $free_mem[1] + $cache_mem[1];

/*
* The disks array list all mountpoint you wan to check freespace
* Display name and path to the moutpoint have to be provide, you can 
*/
 
    $diskfree = round(disk_free_space(".") / 1000000000);
    $disktotal = round(disk_total_space(".") / 1000000000);
    $diskused = round($disktotal - $diskfree);
    $diskusage = round($diskused/$disktotal*100);
    $disk_free_precent = 100 - round($diskfree*1.0 / $disktotal*100, 2);
    ?>

    <body>
        <div class="card mb-2">
            <h4 class="card-header text-center">
                Server information
            </h4>
            <div>
                Ram Memory
            <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" 
                aria-valuenow="<?php echo $free_mem ?>" 
                aria-valuemin="0" 
                aria-valuemax="<?php echo $total_mem ?>" 
                style="width:<?php echo $percentvalue ."%" ?>">
                <?php echo $percentvalue ."%" ?>
                </div>
            </div>
            </div>

            <div>
                Hard Drive Space
            <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" 
                aria-valuenow="<?php echo $diskfree ?>" 
                aria-valuemin="0" 
                aria-valuemax="<?php echo $disktotal ?>" 
                style="width:<?php echo $disk_free_precent ."%" ?>">
                <?php echo $diskused ." Gb" ?>
                </div>
            </div>
            </div>
           
        </div>

        <div class="card mb-2">
            <h4 class="card-header text-center">
                Service Status
            </h4>
        </div>
    </body>
</div>

</html>