Unreal IRCd Whitelist Editor
============================

Setup
-----

Note: The tool was tested with PHP 5.3.3 and Unreal IRCd 3.2.10. Other setups may cause problems or not work at all.

1.  Set up your Unreal IRCd. You can get the sources for this via http://www.unrealircd.com/

2.  Copy the files `index.php` and `configuration.php.dist` to a directory that can be accessed by your webserver. You may want to use HTTP Authentification to protect the directory from unwanted access.

3.  Create an empty file `whitelist.conf` or copy the one that was delivered to a directory, that can be accessed both by your webserver and your IRCd.

4.  Adjust your `unrealircd.conf`

    4.1     Deny all the channels:
    
        deny channel {
            channel "#*";
            reason "Channel not allowed";
        };
    4.2     Add an oper account that can rehash:

        oper rehashbot {
            class clients;
            from {
                userhost *;
            };
            password "password";
            flags
            {
                can_rehash;
            };
        };

    4.3     Include the file with the whitelist definitions.

        include "/path/to/directory/whitelist.conf";

5. Copy `configuration.php.dist` to `configuration.php`.

6. Adjust `configuration.php`. The value of `IRC_PASS` can be something random.

7. You're done.

Usage
-----

Access the file `index.php` via HTTP. Everything else should be clear.

How it works
------------

The tool maintains a file that is included in the users's `unrealircd.conf`. 
In this file there are Allow-Statements for all the channels that may be accessed. 
Every time the file is changed, the tool connects to the IRC Server, authenticates as an oper 
and let's the demon rehash the configuration.

Requirements
------------

PHP 5 or later, allowed to use fsockopen to connect to remote hosts. (Tested with 5.3.3)

UnrealIRCd 3 or later (Tested with 3.2.10)

License
-------

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

