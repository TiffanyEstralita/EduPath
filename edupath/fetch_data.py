import requests

dataset_id = "d_3c55210de27fcccda2ed0c63fdd2b352"  # Dataset ID
url = "https://data.gov.sg/api/action/datastore_search?resource_id=" + dataset_id  # API URL

# Send GET request to the API
response = requests.get(url)

# Check if the response is successful
if response.status_code == 200:
    # Print the JSON response
    print(response.json())
else:
    print(f"Failed to retrieve data. Status code: {response.status_code}")
