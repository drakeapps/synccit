




API Docs:




api.php

Simple Post Data:

- username    -- synccit username
- auth        -- device auth code
- dev         -- developer name
- devauth     -- developer authentication (not implemented yet)
- links       -- links id, comma separated. d3i29d,dk9123,dkok12
- comments    -- links id with comment count. link_id:comment_count,link2_id:comment2_count  etc (only for update)
- mode        -- read/update
- time        -- optional. unix time of when to update. default is when request is made
- output      -- text, xml, json (only for read)


Return:

update:
> success
>>-    authorized          -- username and authcode valid, but no links to update
>>-    xx links updated    -- links updated. number of updated

> error
>>-    no post data        -- no post data sent or incorrect data sent
>>-    not authorized      -- username and authcode not valid


read:
> success
>>-    linkid:time;commentcount:commenttime,linkid2:time;commentcount:commenttime,

> error
>>-    no links found      -- links submitted, but none found in db
>>-    no links requested  -- no links submitted to be checked

XML:

Send on POST variable data. data=<?xml version="1.0"?><synccit><xml></xml></synccit>

Send GET or POST variable type=xml.

    <?xml version="1.0"?>
    <synccit>
        <username>sk33t</username>
        <auth>3xad3h</auth>
        <dev>chrome sync</dev>
        <devauth>kd20ck0asd1</devauth>
        <api>1</api>
        <mode>update</mode>

        <links>
            <link>
                <id>16aeok</id>
            </link>
            <link>
                <id>15hnia</id>
                <comments>1190</comments>
            </link>
            <link>
                <id>15htdg</id>
                <comments>488</comments>
                <both>true</both>
            </link>
        </links>
    <synccit>


Output:
    <?xml version="1.0"?>
    <synccit>
        <links>
            <link>
                <id>121212</id>
                <lastvisit>1357881500</lastvisit>
                <comments>0</comments>
                <commentvisit>0</commentvisit>
            </link>
            <link>
                <id>232323</id>
                <lastvisit>0</lastvisit>
                <comments>1190</comments>
                <commentvisit>1357881500</commentvisit>
            </link>
            <link>
                <id>343434</id>
                <lastvisit>1357881500</lastvisit>
                <comments>488</comments>
                <commentvisit>1357881500</commentvisit>
            </link>
        </links>
    </synccit>


JSON:

Send on POST variable data. data={your json}

Sending GET or POST variable type=json is recommended. By default goes to JSON if variable data is present
   
    {
        "username"  : "sk33t",
        "auth"      : "3xad3h",
        "dev"       : "chrome sync",
        "devauth"   : "kd20ck0asd1",
        "mode"      : "update",
        "api"       : 1,
        
        "links"     : [
            
            {
                "id"    : "15hnia"
            },

            {
                "id"        : "16eaok",
                "comments"  : "1190",
                "both"      : true
            },

            {
                "id"        : "15siwa",
                "comments"  : "321",
                "both"      : false
            }
        ]
    }

Read return:

    [
        {
            "id"            : "15hnia",
            "lastvisit"     : "123412123",
            "comments"      : "0",
            "commentvisit"  : "0"
        },
        {
            "id"            : "16kfoe",
            "lastvisit"     : "0",
            "comments"      : "512",
            "commentvisit"  : "132313123"
        },
        {
            "id"            : "16weas",
            "lastvisit"     : "124412324",
            "comments"      : "124",
            "commentvisit"  : "141231233"
        }
    ]




PBKDF2 file from https://defuse.ca/php-pbkdf2.htm
Released under public domain

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

