import geopandas as gpd
from shapely.geometry import Point, Polygon
import couchdb
import json
import sentiment_mod as sentiment
import sys
import nltk
import time

try:
  nltk.download('punkt')
except:
  pass

COUCHDB_ADDRESS = 'http://admin:admin@localhost:5984/'
COUCHDB_TWEETS_DBNAME = 'preprocess_twitter'
COUCHDB_RAW_TWEETS_DBNAME = 'raw_twitter'

couch = couchdb.Server()
couch = couchdb.Server(COUCHDB_ADDRESS)
if COUCHDB_TWEETS_DBNAME in couch:
    db = couch[COUCHDB_TWEETS_DBNAME]
else:
    db = couch.create(COUCHDB_TWEETS_DBNAME)

if COUCHDB_RAW_TWEETS_DBNAME in couch:
    rawdb = couch[COUCHDB_RAW_TWEETS_DBNAME]
else:
    rawdb = couch.create(COUCHDB_RAW_TWEETS_DBNAME)

geojson_data = gpd.read_file('Simplify_Melbourne_SA2.geojson')


def preprocess_twitter(db, rawdb, total_nodes, node_rank, geojson_data):
  temp = node_rank
  while True:
    results = rawdb.view('place/geo_cooradinate', stale="update_after", skip=1000*temp, limit=1000)
    # mango = {'selector':{'_id':{"$gt":None}}, "sort":[{"_id":"asc"}], "limit":1000, "skip": temp*1000}
    # results = list(rawdb.find(mango))
    if len(results) == 0:
      break
    for result in results:
      tweet_id = result.key
      tweet_doc = result.value
      del tweet_doc['_id']
      del tweet_doc['_rev']
      tweet_doc = process_sa2_location(tweet_doc, geojson_data)
      tweet_doc = process_sentiment(tweet_doc)
      # tweet_doc = process_textblob_sentiment(tweet_doc)
      try:
        db[tweet_id] = tweet_doc
      except:
        pass
    temp += total_nodes

def process_sentiment(tweet_doc):
  tweet = tweet_doc['text']
  tweet_doc['SENTIMENT'] = sentiment.sentiment(tweet)
  return tweet_doc

def process_textblob_sentiment(tweet_doc):
  tweet = tweet_doc['text']
  blob = TextBlob(tweet)
  if blob.sentiment[0]> 0.3:
     tweet_doc['SENTIMENT'] = 'pos'
  elif blob.sentiment[0]>= -0.3:
     tweet_doc['SENTIMENT'] = 'neu'
  else:
     tweet_doc['SENTIMENT'] = 'neg'
  return tweet_doc

def process_sa2_location(tweet_doc, geojson_data):
  lon = 0.0
  lat = 0.0
  try:
    lon = tweet_doc['long']
    lat = tweet_doc['lat']
  except:
    pass
  try:
    lon = tweet_doc['geo']['long']
    lat = tweet_doc['geo']['lat']
  except:
    pass
  try:
    lon = tweet_doc['coordinates']['long']
    lat = tweet_doc['coordinates']['lat']
  except:
    pass

  if lon != 0 and lat != 0:
    location = Point(lon, lat)
    for index, row in geojson_data.iterrows():
      main_16 = row['SA2_MAIN16']
      name_16 = row['SA2_NAME16']
      geometry = row['geometry']
      if geometry.contains(location):
        tweet_doc['SA2_MAIN16'] = main_16
        tweet_doc['SA2_NAME16'] = name_16
        break
  return tweet_doc

def read_arguments(argv):
  # Initialise Variables
  total_nodes = 1
  node_rank = 1
  # Try to read in arguments
  for opt, arg in zip(argv[0::2], argv[1::2]):
    if opt in ("-t"):
      total_nodes = int(arg)
    if opt in ("-r"):
      node_rank = int(arg)
  total_nodes = min(total_nodes, 4)
  total_nodes = max(total_nodes, 1)
  if node_rank > total_nodes:
    node_rank = total_nodes
  # Return all the arguments
  return total_nodes - 1, node_rank - 1


if __name__ == '__main__':
  argv = sys.argv[1:]
  total_nodes, node_rank = read_arguments(argv)
  while True:
    preprocess_twitter(db, rawdb, total_nodes, node_rank, geojson_data)




