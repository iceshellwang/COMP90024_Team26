import couchdb
import geopandas as gpd

COUCHDB_ADDRESS = 'http://admin:admin@localhost:5984/'
GEO_DBNAME = 'geo_db'

couch = couchdb.Server()
couch = couchdb.Server(COUCHDB_ADDRESS)
try:
  geo_db = couch.create(GEO_DBNAME)
except:
  geo_db = couch[GEO_DBNAME]

geojson_data = gpd.read_file('Total_Attributes_In_Percent_Form.geojson')
for index, row in geojson_data.iterrows():
  data = {}
  data['main_16'] = row['SA2_MAIN16']
  data['name_16'] = row['SA2_NAME16']
  data['health_care_and_social_assistance'] = row['health_care_and_social_assistance']
  data['did_not_go_to_schllo_Percent'] = row['did_not_go_to_schllo_Percent']
  data['place_of_usual_residence_1_year_proportion'] = row['place_of_usual_residence_1_year_proportion']
  data['bicycle_to_work'] = row['bicycle_to_work']
  data['weekly_income_4000_more_proportion'] = row['weekly_income_4000_more_proportion']
  data['unemployed_Percent'] = row['unemployed_Percent']
  data['volunteer_proportion'] = row['volunteer_proportion']
  try:
    data['pos'] = int(row['pos'])
  except:
    data['pos'] = 0
  try:
    data['neg'] = int(row['neg'])
  except:
    data['neg'] = 0
  try:
    data['pos_rate'] = float(row['pos_rate'])
  except:
    data['pos_rate'] = 0
  try:
    geo_db[data['main_16']] = data
  except:
    pass