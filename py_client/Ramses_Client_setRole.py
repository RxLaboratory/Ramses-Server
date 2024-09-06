import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/sic4", showReceivedData=True)
client.login()
client.setUserRole("56be6b9c-1e30-5d60-82d2-a6ecc68b4162", "admin")
