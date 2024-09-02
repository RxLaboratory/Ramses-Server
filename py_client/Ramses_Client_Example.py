import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/sic4")
client.login()

projects = client.getProjects()
for project in projects:
    projectUuid = project["uuid"]
    projectData = project["data"]
    projectID = projectData["shortName"]
    projectName = projectData["name"]
    print(projectUuid + " >>> " + projectID + " | " + projectName)

usersForFirstProj = client.getUsers(project = projects[0]["uuid"])

for user in usersForFirstProj:
    userUuid = user["uuid"]
    userData = user["data"]
    userID = userData["shortName"]
    userName = userData["name"]
    print(userUuid + " >>> " + userID + " | " + userName)
