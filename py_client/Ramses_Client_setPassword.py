import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/dev", showReceivedData=True)
response = client.login()
uuid = response['content']['uuid']
client.setPassword(current="d38bf99d83", new="password", userUuid=uuid)
