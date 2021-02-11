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
    $total_mem_ram = preg_split('/ +/', @exec('grep MemTotal /proc/meminfo'));
    $total_mem_ram = round($total_mem_ram[1] / 1000000, 2);

    $free_mem_ram = preg_split('/ +/', @exec('grep MemFree /proc/meminfo'));
    $free_mem_ram = round($free_mem_ram[1] / 1000000, 2);

    $cache_mem_ram = preg_split('/ +/', @exec('grep ^Cached /proc/meminfo'));
    $cache_mem_ram = round($cache_mem_ram[1] / 1000000, 2);

    $free_mem_with_cache = $free_mem_ram + $cache_mem_ram;

    $percent_ram_with_cache = round($free_mem_ram / $total_mem_ram, 2);
    $percent_ram_with_cache = $percent_ram_with_cache * 100;

    $percent_value_ram = $free_mem_ram / $total_mem_ram;
    $percent_value_ram = $percent_value_ram * 100;

    /*The disks array list all mountpoint you wan to check freespace
* Display name and path to the moutpoint have to be provide 
*/

    $disk_free = round(disk_free_space(".") / 1000000000);
    $disk_total = round(disk_total_space(".") / 1000000000);
    $disk_used = round($disk_total - $disk_free);
    $disk_usage = round($disk_used / $disk_total * 100);
    $percent_disk_free = 100 - round($disk_free * 1.0 / $disk_total * 100, 2);

    $phpload = round(memory_get_usage() / 1000000, 2);
    $start_time = microtime(TRUE);
    $end_time = microtime(TRUE);
    $time_taken = $end_time - $start_time;
    $total_time = round($time_taken, 4);

    ?>

    <body>
        <div class="card mb-2">
            <h4 class="card-header text-center">
                Server information
            </h4>
            <div>
                <h6>Ram Memory (<?php echo $free_mem_ram . "GB" . "/" ?> <?php echo $total_mem_ram . "GB" ?>)</h6>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $free_mem_ram ?>" aria-valuemin="0" aria-valuemax="<?php echo $total_mem_ram ?>" style="width:<?php echo $percent_value_ram . "%" ?>">
                        <?php echo round($percent_value_ram, 2) . "%" ?> (<?php echo $total_mem_ram . "GB in total" ?>)
                    </div>
                </div>
            </div>

            <div>
                <h6>Ram Memory with cache (<?php echo $free_mem_with_cache . "GB" . "/" ?> <?php echo $total_mem_ram . "GB" ?>)</h6>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $free_mem_with_cache ?>" aria-valuemin="0" aria-valuemax="<?php echo $total_mem_ram ?>" style="width:<?php echo $percent_ram_with_cache . "%" ?>">
                        <?php echo round($percent_ram_with_cache, 2) . "%" ?> (<?php echo $total_mem_ram . "GB in total" ?>)
                    </div>
                </div>
            </div>

            <div>
                <h6>Hard Drive Space (<?php echo $disk_used . "GB" . "/" ?> <?php echo $disk_total . "GB" ?>)</h6>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $disk_used ?>" aria-valuemin="0" aria-valuemax="<?php echo $disk_total ?>" style="width:<?php echo $percent_disk_free . "%" ?>">
                        <?php echo round($percent_disk_free, 2) . "%" ?> (<?php echo $disk_used . "GB in total" ?>)
                    </div>
                </div>
            </div>
            </br>
            <div>
                <table class='table table-striped table-sm'>
                    <tr>
                        <th>Others</th>
                    </tr>
                    <tbody>
                        <tr>
                            <td><span>Server Address:</span> <span><?php echo $_SERVER['SERVER_ADDR']; ?></span></td>
                        </tr>
                        <tr>
                            <td><span>PHP Version:</span> <span><?php echo phpversion(); ?></span></td>
                        </tr>
                        <tr>
                            <td><span>PHP Load:</span> <span><?php echo $phpload . "GB"; ?></span></td>
                        </tr>
                        <tr>
                            <td><span>Load Time:</span> <span><?php echo $total_time . "Sec"; ?></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-2">
            <h4 class="card-header text-center">
                Service Status
            </h4>
            <div>
                <table class='table table-striped table-sm'>
                    <tr>
                        <th>Service</th>
                        <th>Port</th>
                        <th>Status</th>
                    </tr>
                    <tbody>
                        <?php
                        $services = array();

                        $services[] = array("port" => "80",       "service" => "Web server",                  "ip" => "");
                        $services[] = array("port" => "21",       "service" => "FTP",                     "ip" => "");
                        $services[] = array("port" => "3306",     "service" => "MYSQL",                   "ip" => "");
                        $services[] = array("port" => "22",       "service" => "Open SSH",                "ip" => "");
                        $services[] = array("port" => "58846",     "service" => "Deluge",                 "ip" => "");
                        $services[] = array("port" => "8112",     "service" => "Deluge Web",                 "ip" => "");
                        $services[] = array("port" => "80",       "service" => "Internet Connection",     "ip" => "google.com");
                        $services[] = array("port" => "8083",     "service" => "Vesta panel",                 "ip" => "");

                        foreach ($services as $service) : ?>
                            <tr>
                                <td><?php echo $service['service']; ?></td>
                                <td><?php echo $service['port']; ?></td>
                                <td><?php
                                    if ($service['ip'] == "") {
                                        $service['ip'] = "localhost";
                                    }

                                    $fp = @fsockopen($service['ip'], $service['port'], $errno, $errstr, $timeout);
                                    if (!$fp) {
                                        $status_offline = "Offline";
                                        echo $status_offline;
                                    } else {
                                        $status_online = "Online";
                                        echo $status_online;
                                    }

                                    ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <?php

                    ?>
                </table>
            </div>
        </div>
    </body>
</div>

</html>