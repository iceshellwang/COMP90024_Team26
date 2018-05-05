import json
import couchdb


geojson_file = open('Total_Attributes_In_Percent_Form.geojson', 'r')
geojson_data = json.load(geojson_file)
new_features = []
for feature in geojson_data['features']:
  properties = feature['properties']
  properties['health_care_and_social_assistance'] = float(properties['health_care_and_social_assistance'][:-1])/100
  properties['did_not_go_to_schllo_Percent'] = float(properties['did_not_go_to_schllo_Percent'][:-1])/100
  properties['place_of_usual_residence_1_year_proportion'] = float(properties['place_of_usual_residence_1_year_proportion'][:-1])/100
  properties['bicycle_to_work'] = float(properties['bicycle_to_work'][:-1])/100
  properties['unemployed_Percent'] = float(properties['unemployed_Percent'][:-1])/100
  properties['volunteer_proportion'] = float(properties['volunteer_proportion'][:-1])/100
  properties['weekly_income_4000_more_proportion'] = float(properties['weekly_income_4000_more_proportion'][:-1])/100

geojson_file.close()

geojson_file = open('Total_Attributes_In_Percent_Form.geojson', 'w')
geojson_file.write(json.dumps(geojson_data))
geojson_file.close()

