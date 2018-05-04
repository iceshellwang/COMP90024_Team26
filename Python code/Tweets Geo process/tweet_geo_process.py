import geopandas as gpd
from shapely.geometry import Point, Polygon
import couchdb
import json

COUCHDB_ADDRESS = 'http://admin:admin@localhost:5984/'
COUCHDB_TWEETS_DBNAME = 'twitter'

couch = couchdb.Server()
couch = couchdb.Server(COUCHDB_ADDRESS)
db = couch[COUCHDB_TWEETS_DBNAME]
otherdb = couch['other_twitter']

melbourne = gpd.read_file('Simplify_Melbourne_SA2.geojson')

json_file = open("tweet_geo_count.json", "w")
place_sa2_file = open("place_sa2_maping_result.txt", "r")


def process_geo_location_data(db, geojson_data):
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
            break

def place_sa2_file_preprocess(place_sa2_file):
  result = {}
  for line in place_sa2_file.readlines():
    temp = line.split("//")
    key = temp[0].strip()
    value = temp[1].strip()
    result[key] = value
  return result


def process_geo_place_data(db, otherdb, place_sa2_data, geojson_data):
  counter = 0
  while True:
    mango = {'selector':{'_id':{"$gt":None}}, "sort":[{"_id":"asc"}], "limit":1000, "skip": counter*1000}
    results = list(otherdb.find(mango))
    if len(results) == 0:
      break

    counter += 1
    for result in results:
      try:
        place_name = result['place']['name']
        sa2_main = place_sa2_data[place_name]
        for index, row in geojson_data.iterrows():
          main_16 = row['SA2_MAIN16']
          name_16 = row['SA2_NAME16']
          if sa2_main == main_16:
            print(tweet_id)
            doc = otherdb[tweet_id]
            doc['SA2_MAIN16'] = main_16
            doc['SA2_NAME16'] = name_16
            otherdb[tweet_id] = doc
            del doc['_rev']
            del doc ['_id']
            try:
              db[tweet_id] = doc
            except:
              pass
            break

      except:
        pass

def tweets_geo_sentiment_count(db):
  tweet_geo_sentment_dict = {}
  for item in db.view('sentiment/sa2_sentiment', group=True):
    print(item.key, item.value)
    sa2_main = item.key[0]
    sentiment = item.key[1]
    temp = tweet_geo_sentment_dict.get(sa2_main, {})
    temp[sentiment] = item.value
    tweet_geo_sentment_dict[sa2_main] = temp

  json_obj = {}
  json_obj['type'] = 'FeatureCollection'
  json_obj['bbox'] = [0.0,0.0,-1.0,-1.0]
  json_list = []
  for key, value in tweet_geo_sentment_dict.items():
    temp = {}
    temp['SA2_MAIN16'] = key
    temp['pos'] = value.get('pos', 0)
    temp['neg'] = value.get('neg', 0)
    temp['pos_rate'] = float(temp['pos'])/max((temp['pos']+temp['neg']),1)
    json_list.append(temp)
  json_obj['features'] = json_list
  json_file.write(json.dumps(json_obj))
  json_file.close()

if __name__ == '__main__':
  # place_sa2_data = place_sa2_file_preprocess(place_sa2_file)
  # process_geo_place_data(db, otherdb, place_sa2_data, melbourne)
  # process_geo_location_data(db, melbourne)
  tweets_geo_sentiment_count(db)





