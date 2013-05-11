<?php
/*
    Unreal IRCd Whitelist Editor let's you edit the channel whitelist of an Unreal IRCd
    Copyright (C) 2013 Simon Plasger

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    See COPYING for details.
    
    See README for informations about Setup and Usage.
*/
require "configuration.php";
if(!isset($_GET['post']))
{
    $channels = readconfig();
    $textarea = '';
    $i = 1;
    foreach($channels as $value)
    {
        if($i == 1)
        {
            $textarea .= $value;
        }
        else
        {   
            $textarea .= ','.$value;
        }
        $i++;
    }
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>Edit whitelist</title>
            </head>
            <body>
            <p>Enter all the channels that may be entered, seperated by commas. Example: "#channel1,#channel2,#channel3". Important: Don\'t put a comma at the end.</p>
            <form action="index.php?post" method="POST">
            <textarea name="channel" rows="15" cols="150">'.$textarea.'</textarea><br /><br />
            <input type="submit" value="Absenden" />
            </form>
            </body>
            </html>';
}
else
{
    $channels = explode(',', $_POST['channel']);
    makeconfig($channels);
    rehash();
    echo "Settings have been saved";
}
function makeconfig($channels)
{
    $out = '';
    foreach ($channels as $value)
    {
        $out .= "allow channel { \n";
        $out .= 'channel "'.$value.'";'."\n";
        $out .= '};'."\n";
    }
    file_put_contents(IRC_CONFIGFILE, $out);
    chmod(IRC_CONFIGFILE, 0666);
}    
function readconfig()
{
    $config = file_get_contents(IRC_CONFIGFILE);
    $configs = explode('allow channel ', $config);
    $channels = array();
    foreach($configs as $key => $value)
    {
        if($value != "")
        {
            $temp = explode('"', $value);
            $channels[] = $temp[1];
        }
    }
    return $channels;
}
function rehash() /* To Do: Error checking */
{
    $socket = fsockopen(IRC_SERVER, IRC_PORT, $errno, $errstr);
    $out = 'PASS '.IRC_IRCPASS."\n";
    fwrite($socket, $out);
    sleep(1);
    $out = 'NICK '.IRC_USER."\n";
    fwrite($socket, $out);
    sleep(1);
    for($i = 1; $i <= 2; $i++)
    {
        $get = "";
        while($get != "\n")
        {
            $get = fgets($socket, 2);
        }
    }
    $get = fgets($socket, 7);
    $get = "";
    $pong = "";
    while($get != "\n")
    {
            $get = fgets($socket, 2);
            $pong .= $get;
    }
    $out = 'PONG :'.$pong;
    fwrite($socket, $out);
    sleep(1);
    $out = 'USER '.IRC_USER.' '.IRC_USER.' '.IRC_USER.' :'.IRC_USER.' '.IRC_USER."\n";
    fwrite($socket, $out);
    sleep(1);
    $out = 'OPER '.IRC_USER.' '.IRC_PASS."\n";
    fwrite($socket, $out);
    sleep(1);
    $out = "REHASH \n";
    fwrite($socket, $out);
    sleep(1);
    $out = "QUIT \n";
    fwrite($socket, $out);
    fclose($socket);
}
