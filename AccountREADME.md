# Account API Docs

The Account API is called on account.php. This is located at [http://api.synccit.com/account.php](http://api.synccit.com/account.php). If not using synccit.com, this should be shown on devices page.

Basic idea. When someone clicks on a link on reddit, the ID of link clicked is sent here. When someone clicks on a comment thread, the number of comments that thead has and it's ID is sent here. So when looking at reddit later, no matter what device, the link will show up read and if there are any new comments. 

The API includes 2 variables. The API version and revision. The version is only changed when major changes to the API occur and will break older uses of it. The revision is for smaller changes. This usually means adding features or small changes that don't break any older use of the API.

To determine the version and revision of the API being used, check the headers sent by api.php. `curl -I http://api.synccit.com/account.php` gives me `X-API: 1` and `X-Revision: 1`. To see how revisions change, you can check [the account.php history](https://github.com/drakeapps/synccit/commits/master/api/account.php). 

**Login code and passwords**

For added security, instead of using the account password for each call, it uses an login code. These are created and returned by doing a login call. This is similar to the auth code of the standard API, but is longer and the user never interacts with it.

## Variables

* **`username`**
 * synccit username
* **`login`**
 * login code returned by login command
 * Like auth code in standard API, but much longer and user never interacts with it
 * Note: not needed for create and login calls
* **`dev`**
 * Your developer name
 * Name you want to appear as, such as synccit-userscript or iReddit
* **`devauth`**
 * developer authentication
 * Note: Not implemented yet. This will allow you to ensure only you can use your developer name
* **`mode`**
 * Action you're taking.
  * `create` - create new account
  * `login` - check username/password and get login code
  * `delete` - delete auth code 
  * `history` - returns last 20 links visited
  * `devices` - returns list of devices with auth codes
  * `addauth` - add new device/auth code
* **`api`**
 * API version you're using (not required)
 * Current version is `1`

### Devices (returned) variables

* **`auth`**
 * The auth code of the device
 * Usually a 6 character string
* **`device`**
 * Device name
 * The device name the user entered while setting up the auth codes
* **`created`**
 * Unix timestamp of when the device was added

### History (returned) read variables

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

### Create account variables (JSON/XML)

* **`password`**
 * Password for account
* **`email`**
 * Email to be associated with account (create account only)
 * Not required


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