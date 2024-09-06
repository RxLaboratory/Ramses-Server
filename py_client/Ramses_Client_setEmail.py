import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/dev", showReceivedData=True)
response = client.login("duduf@duduf.com")
uuid = response['content']['uuid']

print(client.getEmail(uuid))

client.setEmail(uuid=uuid, email='tech@rxlaboratory.org')
print(client.getEmail(uuid))
