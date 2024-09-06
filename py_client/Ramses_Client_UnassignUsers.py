import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/sic4", showReceivedData=True)
client.login()
client.unassignUsers(
    users=(
        "b9cf403e-8590-4217-a1f8-f1d837eb33aa",
        "6a71ebab-593f-4fa9-b4b4-478a768783a4",
        "12e3140d-641d-403f-95d2-33034f89f705"
        ),
    projectUuid="fde598e0-7a73-4754-996f-2a8afb150c8f"
)
