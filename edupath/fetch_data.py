import requests
import json

dataset_id = "d_3c55210de27fcccda2ed0c63fdd2b352"  # Dataset ID
url = "https://data.gov.sg/api/action/datastore_search?resource_id=" + dataset_id  # API URL

# Send GET request to the API
response = requests.get(url)

# Check if the response is successful
if response.status_code == 200:
    # Get the JSON response
    data = response.json()
    #save the data to a json file
    with open('api_data.json', 'w') as f:
        json.dump(data, f)

else:
    print(f"Failed to retrieve data. Status code: {response.status_code}")