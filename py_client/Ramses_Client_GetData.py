import json
from ramserverclient import ram_client

client = ram_client.RamClient("https://ramses.rxlab.io/sic4", showReceivedData=True)
client.login()

projects = client.getProjects()
for project in projects:
    if project["removed"]:
        continue
    projectUuid = project["uuid"]
    projectData = json.loads(project["data"])
    projectID = projectData["shortName"]
    projectName = projectData["name"]
    print(projectUuid + " >>> " + projectID + " | " + projectName)

usersForFirstProj = client.getUsers(project = projects[0]["uuid"])

for user in usersForFirstProj:
    if user["removed"]:
        continue
    userUuid = user["uuid"]
    userData = json.loads(user["data"])
    userID = userData["shortName"]
    userName = userData["name"]
    print(userUuid + " >>> " + userID + " | " + userName)

currentProjectUuid = client.setCurrentProject(projects[0]["uuid"])
print(currentProjectUuid)

steps = client.getTable("RamStep")
for step in steps:
    if step["removed"]:
        continue
    stepUuid = step["uuid"]
    stepData = json.loads(step["data"])
    stepID = stepData["shortName"]
    stepName = stepData["name"]
    print(stepUuid + " >>> " + stepID + " | " + stepName)

#client.unassignUser("d0064bf7-0d33-54cd-8054-479b8e84b839", "98301af3-3b50-50ad-9803-82c44664c379")