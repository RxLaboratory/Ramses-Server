import requests
import json
import uuid

import html2text

token = "8d4b57014169bdc2c3db1ba555b7e1444f25de08"
version = "0.5.0-alpha"
url = "https://ramses.rxlab.io/tests"

session = requests.Session()

headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json;charset=utf-8',
    'User-Agent': 'RamServer Dev Tools'
}

def installServer():
    r = session.get(url + "/install", headers=headers)
    print( html2text.html2text( r.text ) )

def ping():
    data = {
        "version": version
    }
    r = session.post(url + "/?ping", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

def login(username, password):
    global token
    data = {
        "version": version,
        "username": username,
        "password": password
    }
    r = session.post(url + "/dev/?login", headers=headers, data=json.dumps(data))
    token = html2text.html2text( r.text ).strip()

def sync( tables, date ):
    global token
    data = {
        "version": version,
        "token": token,
        "previousSyncDate": date,
        "tables": tables
    }
    r = session.post(url + "/?sync", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )

# TESTS Here

#ping()
#installServer()

# Always start a session with a ping
ping()
# We need to login before everything else
login("Admin", "password")
# Test empty sync
# sync( (), "2022-07-15 00:00:00")
# Let's send some data
sync( (
    {
        "name": "RamApplication",
        "modifiedRows": (
            {
                "uuid": str(uuid.uuid4()),
                "data": '\{"name":"After Effects", "shortName":"Ae" \}',
                "modified": "2022-07-16 00:00:00",
                "removed": 0
            },
            {
                "uuid":  str(uuid.uuid4()),
                "data": '\{"name":"Photoshop", "shortName":"Ps" \}',
                "modified": "2022-07-16 00:00:00",
                "removed": 0
            }
        )
    },
    {
        "name": "RamFileType",
        "modifiedRows": (
            {
                "uuid": str(uuid.uuid4()),
                "data": '\{"name":"After Effects Project", "shortName":"aep" \}',
                "modified": "2022-07-16 00:00:00",
                "removed": 0
            },
            {
                "uuid":  str(uuid.uuid4()),
                "data": '\{"name":"Photoshop Document", "shortName":"psd" \}',
                "modified": "2022-07-16 00:00:00",
                "removed": 0
            }
        )
    }

), "2022-07-15 00:00:00")