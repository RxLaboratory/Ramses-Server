import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/sic4")
client.login()

projects = client.getTable("RamProject")
for project in projects:
    projectUuid = project["uuid"]
    projectData = json.loads(project["data"])
    projectID = projectData["shortName"]
    projectName = projectData["name"]
    print(projectUuid + " >>> " + projectID + " | " + projectName)

users = client.getTable("RamUser")
for user in users:
    userUuid = user["uuid"]
    # User data is encrypted and the ramses python client can't decrypt it
    print(userUuid)
