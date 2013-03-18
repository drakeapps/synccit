
# **[API Docs on Wiki](https://github.com/drakeapps/synccit/wiki/API)**

# Requirements

* PHP 5.3
* MySQL database

# Installing

1. Create new database (using mysql command, phpMyAdmin, etc.)
2. Run mysql.sql on database (using mysql command, phpMyAdmin, etc.)
3. Edit `config.php` with database info and API location
4. Site should up and running. Go to index.php in browser

#Updating

1. Replace all files except config.php
2. Run relevant part of `diff.sql` from your current version


# API Docs

API is called on api.php. This is located at [http://api.synccit.com/api.php](http://api.synccit.com/api.php). If not using synccit.com, this should be shown on devices page.

Basic idea. When someone clicks on a link on reddit, the ID of link clicked is sent here. When someone clicks on a comment thread, the number of comments that thead has and it's ID is sent here. So when looking at reddit later, no matter what device, the link will show up read and if there are any new comments. 

The API includes 2 variables. The API version and revision. The version is only changed when major changes to the API occur and will break older uses of it. The revision is for smaller changes. This usually means adding features or small changes that don't break any older use of the API.

To determine the version and revision of the API being used, check the headers sent by api.php. `curl -I http://api.synccit.com/api.php` gives me `X-API: 1` and `X-Revision: 8`. To see how revisions change, you can check [the api.php history](https://github.com/drakeapps/synccit/commits/master/api/api.php). 

**Auth code and passwords**

For added security, instead of using the account password for each call, it uses an auth code. These can be created and deleted from the devices page on [synccit.com](http://synccit.com/). Auth codes are currently 6 character randomly generated strings.

For ease of use on the user's side, accounts can be created and auth codes added via the API. This can allow someone without an account to be up and running with synccit within a few seconds.

## Implementations

* **synccit-android** - [Android Library](https://github.com/talklittle/synccit-android) from reddit is fun
* **AFSynccitAPIClient** - [iOS/Cocoa Client](https://github.com/amleszk/AFSynccitAPIClient)
* **synccit-browser-extension** - [Javascript userscript](https://github.com/drakeapps/synccit-browser-extension)

## Variables

* **`username`**
 * synccit username
* **`auth`**
 * device auth code (users get this from devices page)
 * As of revision 11, password will be accepted (though auth code should still be used instead) 
* **`dev`**
 * Your developer name
 * Name you want to appear as, such as synccit-userscript or iReddit
* **`devauth`**
 * developer authentication
 * Note: Not implemented yet. This will allow you to ensure only you can use your developer name
* **`mode`**
 * Action you're taking.
  * `read` - get read links/comments
  * `history` - get read links/comments since `time` (only for JSON/XML)
  * `update` - update links/comments
  * `create` - create new account (only for JSON/XML)
  * `addauth` - add new authorization code (only for JSON/XML)
* **`api`**
 * API version you're using (not required)
 * Current version is `1`
* **`links`**
 * Array of links (JSON/XML)
 * Comma separated list of link ids (Plain Text)
* **`comments`**
 * Only for plain text mode
 * Comma separated list of link ids with comment count
 * Link id and comment count separated by `:`
* **`time`**
 * Only for history call
 * Unix timestamp to get links since that time
 * Optional. Defaults to 0
* **`offset`**
 * Only for history call
 * History returns at most 100 links per call
 * To get further, set `offset` a multiple of 100
 * Ex. links 101-200, `offset` needs to be 100
 * Optional. Defaults to 0

### Link update variables (JSON/XML)

* **`id`**
 * Reddit link id. 6 character (usually) unique id for each reddit link
 * Ex: `http://www.reddit.com/r/Android/comments/16bond/amazon_introduces_autorip_a_new_service_that/`
 * `id` would be `16bond`
* **`comments`**
 * Number of reddit comments
 * If not present, only link will update
* **`both`**
 * `true`/`false`
 * Only matters when `comments` set
 * If `true`, link will be marked as visited and comment count updated (for self posts)
 * If `false` (assumed), only comment count will be updated
* **`time`**
 * Not implemented yet
 * Custom time to mark link as read

### Link (returned) read variables (JSON/XML)

* **`id`**
 * Reddit link id (see above)
* **`lastvisit`**
 * Unix time stamp of when link was last visited
 * Defaults to `0` if link has never been visited
* **`comments`**
 * Number of comments read
 * Defaults to `0` if comments have never been viewed
* **`commentvisit`**
 * Unix time stamp of when comments were last viewed
 * Defaults to `0` if comments have never been viewed

### Create account / add authorization variables (JSON/XML)

* **`password`**
 * Password for account
* **`email`**
 * Email to be associated with account (create account only)
 * Not required
* **`device`**
 * Device name for the authorization code (add auth only)
* **`auth`**
 * The created auth code (add auth return)


## JSON

JSON data is sent of POST variable `data`

The GET or POST variable `type` should be json (though not required)

### Example JSON update call

    {
        "username" 	: "james",
    	"auth"		: "9m89x0",
    	"dev"		: "synccit json",
    	"mode"		: "update",
    	"links"		: [
    		{
    			"id" : "111111"
    		},
    		{
    			"id" : "222222",
    			"comments" : "132"
    		},
    		{
    			"id" : "333333",
    			"comments" : "313",
    			"both" : true
    		},
    		{
    			"id" : "444444"
    		}
    	]
    }

synccit username is `james`. Auth code is `9m89x0`. The developer is `synccit json`. Mode is `update`

This will update 4 links.

* `111111` - Link marked as read at current time
* `222222` - 132 comments marked as read. Link still unread
* `333333` - Link marked as read at current time. 313 comments marked as read
* `444444` - Link marked as read at current time

**Returns**

Success

    {
    	"success"	: "4 links updated"
    }

Error

    {
    	"error"	: "ERROR_CODE"
    }

***

### Example JSON read call

    {
    	"username" 	: "james",
    	"auth"		: "9m89x0",
    	"dev"		: "synccit json",
    	"mode"		: "read",
    	"links"		: [
    		{
    			"id" : "111111"
    		},
    		{
    			"id" : "222222"
    		},
    		{
    			"id" : "333333"
    		},
    		{
    			"id" : "555555"
    		}
    	]
    }

Nearly same as update call. Mode is now `read` instead of `update`

4 links are checked. Only need `id`

**Returns**

    [
        {
            "id"            : "111111",
            "lastvisit"     : "1357891889",
            "comments"      : "0",
            "commentvisit"  : "0"
        },
        {
            "id"            : "222222",
            "lastvisit"     : "0",
            "comments"      : "132",
            "commentvisit"  : "1357891889"
        },
        {
            "id"            : "333333",
            "lastvisit"     : "1357891889",
            "comments"      : "313",
            "commentvisit"  : "1357891889"
        }
    ]

3 links are returned.

* `111111` - Link visited at `1357891889`. Comments never viewed
* `222222` - 132 comments read. Link never visited
* `333333` - Link visited at `1357891889`. 313 comments read

Link `555555` not returned since it was never updated.

***

### Example JSON history call

    {
        "username"  : "james",
        "auth"      : "9m89x0",
        "dev"       : "synccit json",
        "mode"      : "history",
        "offset"    : "0",
        "time"      : "1357891889"

    }

Nearly same as read call. Mode is now `history` instead of `read`

Gets the links visited since `time`. Will return at most 100 links. To get links 101-200, make `offset` 100. For 201-300, `offset` = 200, etc.

`time` and `offset` are optional, and will default to 0.

**Returns**

    [
        {
            "id"            : "111111",
            "lastvisit"     : "1357891889",
            "comments"      : "0",
            "commentvisit"  : "0"
        },
        {
            "id"            : "222222",
            "lastvisit"     : "0",
            "comments"      : "132",
            "commentvisit"  : "1357891889"
        },
        {
            "id"            : "333333",
            "lastvisit"     : "1357891889",
            "comments"      : "313",
            "commentvisit"  : "1357891889"
        },
        {
            "id"            : "444444",
            "lastvisit"     : "1357891889",
            "comments"      : "0",
            "commentvisit"  : "0"
        }
    ]

4 links are returned.

* `111111` - Link visited at `1357891889`. Comments never viewed
* `222222` - 132 comments read. Link never visited
* `333333` - Link visited at `1357891889`. 313 comments read
* `444444` - Link visited at `1357891889`. Comments never viewed

***

### Example JSON create account call

    {
        "username"  : "newuser",
        "password"  : "thebestpasswordever",
        "dev"       : "synccit demo",
        "email"     : "newuser@synccit.com",
        "mode"      : "create"
    }

Creates new user with username `newuser` and password `thebestpasswordever`. And an email of `newuser@synccit.com`, though email is not required. Mode is `create`

**Returns**


Success

    {
        "success"   : "account created"
    }

Error

    {
        "error"     : "ERROR_CODE"
    }

***

### Example JSON add authorization call

    {
        "username"  : "newuser",
        "password"  : "thebestpasswordever",
        "dev"       : "synccit demo",
        "device"    : "developer API device",
        "mode"      : "addauth"
    }

Creates a new auth code for the user `newuser` with password `thebestpasswordever`. Device name is `developer API device`. Mode is `addauth`

**Returns**

Success

    {
        "success"   : "device key added",
        "device"    : "developer API device",
        "auth"      : "409ssj"
    }

New device key added with auth code of `409ssj`. Device name is also returned back

Error

    {
        "error" : "ERROR_CODE"
    }

## XML

XML data is sent on POST variable data.

The GET or POST variable `type` has to be set to xml or, as of API revision 10, the first 4 charaters of POST data are `<?xml`

### Example XML update call

    <?xml version="1.0"?>
    <synccit>
        <username>james</username>
        <auth>9m89x0</auth>
        <dev>synccit xml</dev>
        <mode>update</mode>
    
        <links>
            <link>
                <id>111111</id>
            </link>
            <link>
                <id>222222</id>
                <comments>132</comments>
            </link>
            <link>
                <id>333333</id>
                <comments>313</comments>
                <both>true</both>
            </link>
            <link>
                <id>444444</id>
            </link>
     
        </links>
    </synccit>

synccit username is `james`. Auth code is `9m89x0`. The developer is `synccit xml`. Mode is `update`

This will update 4 links.

* `111111` - Link marked as read at current time
* `222222` - 132 comments marked as read. Link still unread
* `333333` - Link marked as read at current time. 313 comments marked as read
* `444444` - Link marked as read at current time

**Returns**

Success

    <?xml version="1.0"?>
    <synccit>
	    <success>4 links updated</success>
    </synccit>

Error

     <?xml version="1.0"?>
     <synccit>
    	    <error>ERROR_CODE</error>
     </synccit>

***

### Example XML read call

    <?xml version="1.0"?>
    <synccit>
        <username>james</username>
        <auth>9m89x0</auth>
        <dev>synccit xml</dev>
        <mode>read</mode>
    
        <links>
            <link>
                <id>11111</id>
            </link>
            <link>
                <id>222222</id>
            </link>
            <link>
                <id>333333</id>
            </link>
            <link>
                <id>555555</id>
            </link>
        </links>
    </synccit>

Nearly same as update call. Mode is now `read` instead of `update`

4 links are checked. Only need `id`

**Returns**

    <?xml version="1.0"?>
    <synccit>
    	<links>
    		<link>
    			<id>111111</id>
    			<lastvisit>1357881500</lastvisit>
    			<comments>0</comments>
    			<commentvisit>0</commentvisit>
    		</link>
    		<link>
    			<id>222222</id>
    			<lastvisit>0</lastvisit>
    			<comments>132</comments>
    			<commentvisit>1357881500</commentvisit>
    		</link>
    		<link>
    			<id>333333</id>
    			<lastvisit>1357881500</lastvisit>
    			<comments>313</comments>
    			<commentvisit>1357881500</commentvisit>
    		</link>
    	</links>
    </synccit>

3 links are returned.

* `111111` - Link visited at `1357881500`. Comments never viewed
* `222222` - 132 comments read. Link never visited
* `333333` - Link visited at `1357891889`. 313 comments read

Link `555555` not returned since it was never updated.

***

### Example XML history call

    <?xml version="1.0"?>
    <synccit>
        <username>james</username>
        <auth>9m89x0</auth>
        <dev>synccit xml</dev>
        <mode>history</mode>
        <offset>0</offset>
        <time>1357881500</time>
    </synccit>

Nearly same as read call. Mode is now `history` instead of `read`

Gets the links visited since `time`. Will return at most 100 links. To get links 101-200, make `offset` 100. For 201-300, `offset` = 200, etc.

`time` and `offset` are optional, and will default to 0.


**Returns**

    <?xml version="1.0"?>
    <synccit>
        <links>
            <link>
                <id>111111</id>
                <lastvisit>1357881500</lastvisit>
                <comments>0</comments>
                <commentvisit>0</commentvisit>
            </link>
            <link>
                <id>222222</id>
                <lastvisit>0</lastvisit>
                <comments>132</comments>
                <commentvisit>1357881500</commentvisit>
            </link>
            <link>
                <id>333333</id>
                <lastvisit>1357881500</lastvisit>
                <comments>313</comments>
                <commentvisit>1357881500</commentvisit>
            </link>
            <link>
                <id>444444</id>
                <lastvisit>1357881500</lastvisit>
                <comments>0</comments>
                <commentvisit>0</commentvisit>
            </link>
        </links>
    </synccit>

4 links are returned.

* `111111` - Link visited at `1357881500`. Comments never viewed
* `222222` - 132 comments read. Link never visited
* `333333` - Link visited at `1357891889`. 313 comments read
* `444444` - Link visited at `1357881500`. Comments never viewed

***

### Example XML create account call

    <?xml version="1.0"?>
    <synccit>
        <username>newuser</username>
        <password>thebestpasswordever</password>
        <dev>synccit demo</dev>
        <email>newuser@synccit.com</email>
        <mode>create</mode>
    </synccit>

Creates the user `newuser` with a password of `thebestpasswordever`. And an email of `newuser@synccit.com`, though email is not required. Mode is `create`

**Returns**

Success

    <?xml version="1.0"?>
    <synccit>
        <success>account created</success>
    </synccit>

Error

    <?xml version="1.0"?>
    <synccit>
        <error>ERROR_CODE</error>
    </synccit>

***

### Example XML add authorization 

    <?xml version="1.0"?>
    <synccit>
        <username>newuser</username>
        <password>thebestpasswordever</password>
        <dev>synccit demo</dev>
        <device>developer API device</device>
        <mode>addauth</mode>
    </synccit>

Creates a new auth code for the user `newuser` with password `thebestpasswordever`. Device name is `developer API device`. Mode is `addauth`

**Returns**

Success

    <?xml version="1.0"?>
    <synccit>
        <success>device key added</success>
        <device>developer API device</device>
        <auth>303b09</auth>
    </synccit>

Returns auth code under `auth`. Use this for future API calls for this user. 

Error

    <?xml version="1.0"?>
    <synccit>
        <error>ERROR_CODE</error>
    </synccit>

## Plain Text

Data just sent as POST variables

### Example update call

    username=james&auth=9m89x0&dev=stext&mode=update&links=111111,333333,444444&comments=222222:132,333333:313

synccit username is `james`. Auth code is `9m89x0`. The developer is `stext`. Mode is `update`

This will update 4 links.

* `111111` - Link marked as read at current time
* `222222` - 132 comments marked as read. Link still unread
* `333333` - Link marked as read at current time. 313 comments marked as read
* `444444` - Link marked as read at current time

**Returns**

Success

    success: 4 links updated

Error

    error: ERROR_CODE

***

### Example read call

    username=james&auth=9m89x0&dev=stext&mode=read&links=111111,222222,333333,555555

Nearly same as update call. Mode is now `read` instead of `update`

4 links are checked. Only need `links` variable. Comments are automatically checked

**Returns**

    111111:1356731259;0:0,
    222222:0;132:1356731259,
    333333:1356731259;313:1356731259,

Format is:

    link_id:link_visited_time;comment_count:comment_visited_time,

3 links are returned.

* `111111` - Link visited at `1356731259`. Comments never viewed
* `222222` - 132 comments read. Link never visited
* `333333` - Link visited at `1356731259`. 313 comments read

Link `555555` not returned since it was never updated.

## Error Codes

* `no post data`
 * No post data sent or at least none that we know what to do with
* `not authorized`
 * Username and auth code combination doesn't work
* `no links requested` 
 * No links submitted to be checked
* `no links found`
 * None of links requested have history (only in plain text mode)
* `database error`
 * Error executing query. Likely something on our end
* `username or password wrong`
 * That username and password combination isn't valid

**Create account errors**

* `email not valid`
 * Not valid email given. Only checks it '@' exists
* `username needs to be at least 3 characters long`
* `password needs to be at least 6 characters long`
* `username must consist of letters, numbers, or underscores`
* `username already exists`
 * Username is taken. Try something else



# License


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


PBKDF2 file from https://defuse.ca/php-pbkdf2.htm
Released under public domain

Blue icons from http://mebaze.com/freebies/bunch-of-cool-bluish-icons

1140 css grid from http://cssgrid.net/

laptop/iphone icon http://brsev.deviantart.com/
