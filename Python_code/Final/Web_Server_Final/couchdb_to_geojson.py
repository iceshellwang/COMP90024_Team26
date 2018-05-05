import json
import couchdb
import time

COUCHDB_ADDRESS = 'http://admin:admin@localhost:5984/'
GEO_DBNAME = 'geo_db'


couch = couchdb.Server()
couch = couchdb.Server(COUCHDB_ADDRESS)

geo_db = couch[GEO_DBNAME]

while True:
  geojson_file = open('Total_Attributes_In_Percent_Form.geojson', 'r')
  geojson_data = json.load(geojson_file)
  new_features = []
  for feature in geojson_data['features']:
    properties = feature['properties']
    doc = geo_db[properties['SA2_MAIN16']]
    properties['health_care_and_social_assistance'] = doc['health_care_and_social_assistance']
    properties['did_not_go_to_schllo_Percent'] = doc['did_not_go_to_schllo_Percent']
    properties['place_of_usual_residence_1_year_proportion'] = doc['place_of_usual_residence_1_year_proportion']
    properties['bicycle_to_work'] = doc['bicycle_to_work']
    properties['weekly_income_4000_more_proportion'] = doc['weekly_income_4000_more_proportion']
    properties['unemployed_Percent'] = doc['unemployed_Percent']
    properties['volunteer_proportion'] = doc['volunteer_proportion']
    properties['pos'] = doc['pos']
    properties['neg'] = doc['neg']
    properties['pos_rate'] = doc['pos_rate']

  geojson_file.close()

  geojson_file = open('Total_Attributes_In_Percent_Form.geojson', 'w')
  geojson_file.write(json.dumps(geojson_data))
  geojson_file.close()

  time.sleep(10*60)

