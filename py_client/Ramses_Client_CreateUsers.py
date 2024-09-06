import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/sic4", showReceivedData=True)
client.login()
client.createUsers((
    {
        'email': "user@example.com",
        'data' : json.dumps({
            'shortName': "duduf",
            'name': "Duduf",
            'role': "admin"
        })
    },
    {
        'email': "other@example.comg",
        'data' : json.dumps({
            'shortName': "duf-rxlab",
            'name': "Duduf RxLab",
            'role': "standard"
        })
    },
))
