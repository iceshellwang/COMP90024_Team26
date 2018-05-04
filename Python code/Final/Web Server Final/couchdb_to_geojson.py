import json
import couchdb

COUCHDB_ADDRESS = 'http://admin:admin@localhost:5984/'
GEO_DBNAME = 'geo_db'


couch = couchdb.Server()
couch = couchdb.Server(COUCHDB_ADDRESS)

geo_db = couch[GEO_DBNAME]

while True:
  geojson_file = open('FinalTotalAttribute.geojson', 'r')
  geojson_data = json.load(geojson_file)
  new_features = []
  for feature in geojson_data['features']:
    properties = feature['properties']
    doc = geo_db[properties['SA2_MAIN16']]
    properties['data2630464559989593264_p_tot_unemp_tot'] = doc['unemployed']
    properties['Income of 4000 or more Total_fi_4000_more_tot'] = doc['income_over_4000']
    properties['data7892875890288123329_Person_Did_Not_Go_To_School_Total'] = doc['person_did_not_go_to_school_total']
    properties['data1854632499827315136_hc_sa_med_oth_hcs_p'] = doc['person_medical_help']
    properties['Non-school qualifications Certificate Level Total Persons_non_sc_quals_certtot_level_p'] = doc['non_school_qualifications_total']
    properties['Persons Total Voluntary work_p_total_total'] = doc['voluntaay_work_total']
    properties['Same usual address 1 year ago as in 2016 Persons_sme_usl_ad_1_yr_ago_as_2016_p'] = doc['moving_house']
    properties['Type of educational institution Total Persons_tot_totp'] = doc['person_participate_educational_institution']
    properties['pos'] = doc['pos']
    properties['neg'] = doc['neg']
    properties['pos_rate'] = doc['pos_rate']

  geojson_file.close()

  geojson_file = open('FinalTotalAttribute.geojson', 'w')
  geojson_file.write(json.dumps(geojson_data))
  geojson_file.close()

  sleep(10*60)

