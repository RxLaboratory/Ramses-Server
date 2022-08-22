import requests
import json

import html2text

url = "https://ramses.rxlab.io/tests"

headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json;charset=utf-8',
    'User-Agent': 'RamServer Dev Tools'
}

def post( content ):
    r = requests.post(url, headers=headers, data=json.dumps(content))

def installServer():
    r = requests.get(url + "/install", headers=headers)
    print( html2text.html2text( r.text ) )

def login(username, password):
    data = {
        "version": "0.5.0",
        "username": username,
        "password": password
    }
    r = requests.post(url + "/dev/?login", headers=headers, data=json.dumps(data))
    print( html2text.html2text( r.text ) )


# TESTS Here


#installServer()
login("Admin", "password")