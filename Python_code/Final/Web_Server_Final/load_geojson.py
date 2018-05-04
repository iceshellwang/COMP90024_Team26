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

geojson_data = gpd.read_file('FinalTotalAttribute.geojson')
for index, row in geojson_data.iterrows():
  data = {}
  data['main_16'] = row['SA2_MAIN16']
  data['name_16'] = row['SA2_NAME16']
  data['unemployed'] = int(row['data2630464559989593264_p_tot_unemp_tot'])
  data['income_over_4000'] = int(row['Income of 4000 or more Total_fi_4000_more_tot'])
  data['person_did_not_go_to_school_total'] = int(row['data7892875890288123329_Person_Did_Not_Go_To_School_Total'])
  data['person_medical_help'] = int(row['data1854632499827315136_hc_sa_med_oth_hcs_p'])
  data['non_school_qualifications_total'] = int(row['Non-school qualifications Certificate Level Total Persons_non_sc_quals_certtot_level_p'])
  data['voluntary_work_total'] = int(row['Persons Total Voluntary work_p_total_total'])
  data['moving_house'] = int(row['Same usual address 1 year ago as in 2016 Persons_sme_usl_ad_1_yr_ago_as_2016_p'])
  data['person_participate_educational_institution'] = int(row['Type of educational institution Total Persons_tot_totp'])
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