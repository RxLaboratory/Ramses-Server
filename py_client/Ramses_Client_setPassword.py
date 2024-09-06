import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/dev", showReceivedData=True)
response = client.login("tech@rxlaboratory.org")
uuid = response['content']['uuid']
client.setPassword(current="password", new="password", userUuid=uuid)
