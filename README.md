
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


# API Docs:

API is called on api.php. This is located at [http://api.synccit.com/api.php](http://api.synccit.com/api.php). If not using synccit.com, this should be shown on devices page.

## Variables

* **`username`**
 * synccit username
* **`auth`**
 * device auth code (users get this from devices page)
* **`dev`**
 * Your developer name
 * Name you want to appear as, such as synccit-userscript or iReddit
* **`devauth`**
 * developer authentication
 * Note: Not implemented yet. This will allow you to ensure only you can use your developer name
* **`mode`**
 * Action you're taking.
  * `read` - get read links/comments
  * `update` - update links/comments
* **`api`**
 * API version you're using
 * Current version is `1`
* **`links`**
 * Array of links (JSON/XML)
 * Comma separated list of link ids (Plain Text)
* **`comments`**
 * Only for plain text mode
 * Comma separated list of link ids with comment count
 * Link id and comment count separated by `:`

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


## JSON

JSON data is sent of POST variable `data`

The GET or POST variable `type` should be json (though not required currently)

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

## XML

XML data is sent on POST variable data.

The GET or POST variable `type` has to be set to xml

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
