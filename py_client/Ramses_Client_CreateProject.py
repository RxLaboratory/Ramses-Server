import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/sic4", showReceivedData=True)
client.login()
client.createProject(
    json.dumps({
        "name": "Test Project",
        "shortName" : "test-proj"
    })
)
