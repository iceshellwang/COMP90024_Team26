import geopandas as gpd
from shapely.geometry import Point, Polygon
import couchdb
import json

COUCHDB_ADDRESS = 'http://admin:admin@localhost:5984/'
COUCHDB_TWEETS_DBNAME = 'twitter'

couch = couchdb.Server()
couch = couchdb.Server(COUCHDB_ADDRESS)
db = couch[COUCHDB_TWEETS_DBNAME]

melbourne = gpd.read_file('Simplify_Melbourne_SA2.geojson')

json_file = open("tweet_geo_count.json", "w")


def process_geo_location_data(db, geojson_data):
  tweet_geo_count = {}
  counter = 0
  while True:
    mango = {'selector':{'_id':{"$gt":None}}, "sort":[{"_id":"asc"}], "limit":1000, "skip": counter*1000}
    results = list(db.find(mango))
    if len(results) == 0:
      break

    counter += 1
    for result in results:
      print (result['_id'])
      tweet_id = result['_id']
      lon = 0.0
      lat = 0.0
      try:
        lon = result['long']
        lat = result['lat']
      except:
        pass
      try:
        lon = result['geo']['long']
        lat = result['geo']['lat']
      except:
        pass
      try:
        lon = result['coordinates']['long']
        lat = result['coordinates']['lat']
      except:
        pass

      if lon != 0 and lat != 0:
        location = Point(lon, lat)
        for index, row in geojson_data.iterrows():
          main_16 = row['SA2_MAIN16']
          name_16 = row['SA2_NAME16']
          geometry = row['geometry']
          if geometry.contains(location):
            doc = db[tweet_id]
            doc['SA2_MAIN16'] = main_16
            doc['SA2_NAME16'] = name_16
            db[tweet_id] = doc
            tweet_geo_count[main_16] = tweet_geo_count.get(main_16, 0) + 1
            break
  return tweet_geo_count


def process_geo_place_data(db, geojson_data):
  tweet_geo_count = {}
  temp = []
  counter = 0
  while True:
    mango = {'selector':{'_id':{"$gt":None}}, "sort":[{"_id":"asc"}], "limit":1000, "skip": counter*1000}
    results = list(db.find(mango))
    if len(results) == 0:
      break

    counter += 1
    for result in results:
      try:
        place_name = result['place']['name']
        print(place_name)
        temp.append(place_name)
      except:
        pass
  print(set(temp))

process_geo_place_data(db, melbourne)
# tweet_geo_count = process_geo_location_data(db, melbourne)
# json_obj = {}
# json_obj['type'] = 'FeatureCollection'
# json_obj['bbox'] = [0.0,0.0,-1.0,-1.0]
# json_list = []
# for key, value in tweet_geo_count.items():
#   temp = {}
#   temp['SA2_MAIN16'] = key
#   temp['tweet_count'] = value
#   json_list.append(temp)
# json_obj['features'] = json_list
# json_file.write(json.dumps(json_obj))





