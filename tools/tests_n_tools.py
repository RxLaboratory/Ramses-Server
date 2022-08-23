import requests
import json

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
    data = {
        "version": version,
        "username": username,
        "password": password
    }
    r = session.post(url + "/dev/?login", headers=headers, data=json.dumps(data))
    token = html2text.html2text( r.text )
    print( token )

def sync( tables, date ):
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
installServer()
#ping()
login("Admin", "password")
#sync( (), "2022-07-15 00:00:00")