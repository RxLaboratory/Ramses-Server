import requests
import json
import uuid
import hashlib

import html2text

token = ""
version = "0.7.0-Beta"

url = "http://127.0.0.1:8001/ramses"
clientKey = "drHSV2XQ"

session = requests.Session()

headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json;charset=utf-8',
    'User-Agent': 'RamServer Dev Tools'
}

def installServer():
    global token, session, version
    r = session.get(url + "/install/index.php", headers=headers)
    print( html2text.html2text( r.text ) )

def ping():
    global token, session, version
    data = {
        "version": version
    }
    r = session.post(url + "/?ping", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

def login(username, password):
    global token, session, version
    data = {
        "version": version,
        "username": username,
        "password": password
    }
    r = session.post(url + "/dev/?login", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )
    data = json.loads( r.text )
    token = data["content"]["token"]
    print(token)

def sync():
    global token, session, version
    data = {
        "version": version,
        "token": token
    }
    r = session.post(url + "/?sync", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

def push( tableName, rows = (), date = "1818-05-05 12:00:00", commit = False ):
    global token, session, version
    data = {
        "version": version,
        "token": token,
        "previousSyncDate": date,
        "table": tableName,
        "rows": rows,
        "commit": commit
    }
    r = session.post(url + "/?push", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

def commit():
    global token, session, version
    data = {
        "version": version,
        "token": token,
        "commit": True
    }
    r = session.post(url + "/?push", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

def fetch():
    global token, session, version
    data = {
        "version": version,
        "token": token
    }
    r = session.post(url + "/?fetch", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

def pull( tableName, page = 1):
    global token, session, version
    data = {
        "version": version,
        "token": token,
        "table": tableName,
        "page": page,
    }
    r = session.post(url + "/?pull", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

def setPassword( uuid, pswd, current = "" ):
    global token, session, version, clientKey, url
    # Client hash
    pswd = url.replace('https://', '').replace('/','') + pswd + clientKey
    pswd = hashlib.sha3_512(pswd.encode()).hexdigest()

    if (current != ""):
        # Client hash
        current = url.replace('https://', '').replace('/','') + current + clientKey
        current = hashlib.sha3_512(current.encode()).hexdigest()
 
    data = {
        "version": version,
        "token": token,
        "uuid": uuid,
        "newPassword": pswd,
        "currentPassword": current
    }
    r = session.post(url + "/?setPassword", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

def setUserName( uuid, username, name ):
    sync( (
        {
            "name": "RamUser",
            "modifiedRows": (
                {
                    "uuid": uuid,
                    "userName": username,
                    "data": '{"name":"' + name + '", "userName":"' + username + '"}',
                    "modified": "2022-08-29 15:00:00",
                    "removed": 0
                },
            )
        },
        ), "1970-01-01 00:00:00"
    )

def clean( tables ):
    global token, session, version
    data = {
        "version": version,
        "token": token,
        "tables": tables
    }
    r = session.post(url + "/?clean", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

# TESTS Here

def testSync():
    
    uuid1 = str(uuid.uuid4())
    uuid2 = str(uuid.uuid4())
    uuid3 = str(uuid.uuid4())
    uuid4 = str(uuid.uuid4())
    uuid5 = str(uuid.uuid4())
    uuid6 = str(uuid.uuid4())
    uuid7 = str(uuid.uuid4())

    # 1.
    print("=== Start sync session")
    sync()

    # 2.
    print("=== Push some data.")

    push( "RamApplication",
        (
            {
                "uuid": uuid1,
                "data": '{"name":"After Effects", "shortName":"WRONG" }',
                "modified": "2022-07-15 01:00:00",
                "removed": 0
            },
            {
                "uuid":  uuid2,
                "data": '{"name":"Photoshop", "shortName":"Ps" }',
                "modified": "2022-07-15 02:00:00",
                "removed": 0
            }
        ),
        "2022-07-15 00:00:00"
    )

    push( "RamFileType",
        (
            {
                "uuid": uuid3,
                "data": '{"name":"After Effects Project, "shortName":"aep" }',
                "modified": "2022-07-15 01:30:00",
                "removed": 0
            },
            {
                "uuid": uuid4,
                "data": '{"name":"After Effects Template, "shortName":"aet" }',
                "modified": "2022-07-15 01:40:00",
                "removed": 0
            }
        ),
        "2022-07-15 00:00:00",
        True
    )

    # 3.
    print("=== Fetch.")
    fetch()

    # 4.
    print("=== Pull RamUser")
    pull("RamUSer")

    print("=== Pull RamApplication")
    pull("RamApplication")

    print("=== Pull RamFileType")
    pull("RamFileType")

    # 5.
    downloadTables(("RamUser", "RamApplication", "RamFileType"))


def testSyncUser():
    uuid1 = str(uuid.uuid4())
    uuid2 = str(uuid.uuid4())

    # 1. 
    print("Create an item in the tables. Should return an empty list")
    sync( ({
        "name": "RamUser",
        "modifiedRows": (
            {
                "uuid": uuid1,
                "data": '{"name":"Nicolas Dufresne", "shortName":"Duf" }',
                "modified": "2022-07-15 01:00:00",
                "removed": 0,
                "userName": "Duf"
            },
        )
    },
    ), "2022-07-15 00:00:00")

    # 2. 
    print("Update User. Should return an empty list")
    sync( (
        {
            "name": "RamUser",
            "modifiedRows":
            (
                {
                    "uuid": uuid1,
                    "data": '{"name":"Nicolas Dufresne", "shortName":"Duduf" }',
                    "modified": "2022-07-16 06:00:00",
                    "removed": 0,
                    "userName": "Duduf"
                },
            )
        },
    ), "2022-07-16 00:00:00")

def downloadTables(tableNames):
    print("=== New Sync session to download all")

    sync()
    for tableName in tableNames:
        push(tableName)
    commit()
    fetch()
    for tableName in tableNames:
        pull(tableName)

def testClean():
    clean( ( 
            {
                "name": "RamAsset",
                "rows": [ "9f9abb25-c404-53a1-bd1f-d38ad16a668b", ]
            },
        ))

installServer()

# Always start a session with a ping
ping()
# We need to login before everything else
login("Admin", "password")

# Sync methods
#sync()
#push( "RamUser", (), "1818-05-05 12:00:00", True)
#fetch()
#pull( "RamUser" )

# Test Sync
#testSync()
downloadTables(("RamUser", "RamApplication", "RamFileType"))

#setUserName( "dda85817-34a4-4a97-a1ae-43e9b04da031", "Duf", "Nicolas Dufresne" )
#login("Admin", "pass")
#setPassword( "cac400e4-dfe1-4005-949e-a085f9aa43bd", "password", "pass" )
#testClean()


"""downloadTables((
    "RamUser",
    ))"""



"""sync( (
        {
            "name": "RamApplication",
            "modifiedRows": (
                {
                    "uuid": "b1287769-7645-4702-b593-000a2d1e3771",
                    "data": '{"name":"After Effects", "shortName":"WRONG" }',
                    "modified": "2022-07-16 00:00:00",
                    "removed": 0
                },
                {
                    "uuid":  "92316258-67f5-4f18-a8a6-46d284577f66",
                    "data": '{"name":"Photoshop", "shortName":"Ps" }',
                    "modified": "2022-07-16 00:00:00",
                    "removed": 0
                }
            )
        },
    ), "2022-07-15 00:00:00")"""