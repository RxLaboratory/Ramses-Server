import requests
from getpass import getpass
import hashlib

from .ram_meta import (
    RAMCLIENT_VERSION,
    RAMCLIENT_KEY
)

class RamClient(object):

    def __init__(self, server_adress:str, server_port=0):

        # Parse address to add server port if needed

        if server_port > 0:
            server_details  = server_adress.split("://")
            protocol = server_details[0]
            address = server_details[1]
            address_details = address.split("/")
            domain = address_details[0]
            path = ""
            if len(address_details) > 1:
                path = "/".join( address_details[1:] )
            self._server_adress = protocol + "://" + domain + ":" + str(server_port) + "/" + path
        else:
            self._server_adress = server_adress

        # Prepare token
        self._token = ""
        self._showData = True

        print("Connecting to: "+self._server_adress)
        self._session = requests.Session()
        self.ping()

    def setShowDataReceived(self, show:bool):
        self._showData = show

    def ping(self):
        self.__get("ping")

    def login(self):
        username = input("Username: ")
        password = getpass()
        # hash password
        password = self.__hashPassword(password)

        response = self.__get("login", {"username": username, "password": password})
        # keep token
        if response["success"]:
            self._token = response["content"]["token"]

    def getTable(self,table:str):
        # Start sync session
        self.__get("sync")

        # push empty table
        self.__get("push",{
            "table": table,
            "rows": [],
            "previousSyncDate": "1818-05-05 00:00:00",
            "commit": True
        })

        fetched = self.__get("fetch")
        if fetched["content"]["tableCount"] != 1:
            print("Something went wrong, we didn't get the right count of tables...")

        fetched_table = fetched["content"]["tables"][0]
        num_pages = fetched_table["pageCount"]

        items = []
        i = 1
        while i <= num_pages:
            page = self.__get("pull",{
                "table": table,
                "page": i
            })
            items = items + page["content"]["rows"]
            i = i+1
        return items

    def getUsers(self, project = ""):
        data = {}
        if project != "":
            data["project"] = project
        response = self.__get("getUsers", data)
        return response["content"]

    def assignUser(self, userUuid, projectUuid):
        self.__get("assignUser", {
            "user": userUuid,
            "project": projectUuid
        })

    def __hashPassword(self, password:str):
        prefix = self._server_adress.replace("http://","").replace("https://","").replace("/","")
        password = prefix+password+RAMCLIENT_KEY
        h = hashlib.sha3_512()
        h.update(password.encode())
        return h.hexdigest()

    def __get(self, query:str, data={}, bufsize = 0):

        params = {}
        params[query] = ""
        data["version"] = RAMCLIENT_VERSION
        if self._token != "":
            data["token"] = self._token

        response = self._session.get(
            self._server_adress + "/index.php",
            params=params,
            json=data,
            headers= {"Content-Type":"application/json"},
            timeout=60
            )
        
        if self._showData:
            print("\n======== " + query.upper() + " =======")
            print(response.text)
            print("===============\n")

        return response.json()
