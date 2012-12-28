





API Docs:




api.php

Simple Post Data:

username    -- synccit username
auth        -- device auth code
dev         -- developer name
devauth     -- developer authentication (not implemented yet)
links       -- links id, comma separated. d3i29d,dk9123,dkok12
comments    -- links id with comment count. link_id:comment_count,link2_id:comment2_count  etc (only for update)
mode        -- read/update
time        -- optional. unix time of when to update. default is when request is made
output      -- text, xml, json (only for read)


Return:

update:
success
    authorized          -- username and authcode valid, but no links to update
    xx links updated    -- links updated. number of updated
error
    no post data        -- no post data sent or incorrect data sent
    not authorized      -- username and authcode not valid


read:
success
    linkid:time;commentcount:commenttime,linkid2:time;commentcount:commenttime,
error
    no links found      -- links submitted, but none found in db
    no links requested  -- no links submitted to be checked

XML:

<?xml version="1.0"?>
<user>
    <username>sk33t</username>
    <auth>3xad3h</auth>
    <dev>chrome sync</dev>
    <devauth>kd20ck0asd1</devauth>
    <mode>update</mode>
</user>
<links>
    <link>
        <id>15hnia</id>
        <comments>1190</comments>
    </link>
    <link>
        <id>15htdg</id>
        <comments>488</comments>
    </link>
</links>








PBKDF2 file from https://defuse.ca/php-pbkdf2.htm
Released under public domain