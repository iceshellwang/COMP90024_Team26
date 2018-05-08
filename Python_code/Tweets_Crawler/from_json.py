import couchdb
import requests
import json
from datetime import datetime
from datetime import timedelta

COUCHDB_ADDRESS = 'http://admin:admin@localhost:5984/'
COUCHDB_RAW_TWEETS_DBNAME = 'raw_twitter'

couch = couchdb.Server()
couch = couchdb.Server(COUCHDB_ADDRESS)
if COUCHDB_RAW_TWEETS_DBNAME in couch:
    db = couch[COUCHDB_RAW_TWEETS_DBNAME]
else:
    db = couch.create(COUCHDB_RAW_TWEETS_DBNAME)

def getCoordinatesData(feature, json_data):
  if feature['geometry']['coordinates'] != None:
    json_data['coordinates'] = {}
    json_data['coordinates']['long'] = feature['geometry']['coordinates'][0]
    json_data['coordinates']['lat'] = feature['geometry']['coordinates'][1]
  return json_data

def get_tweets_from_json(fileName):
  with open(fileName, encoding='utf-8') as f:
    json_data = json.load(f)
    rows = json_data['rows']
    for row in rows:
      tweet = {}
      tweet_id = row['id']
      print(tweet_id)
      doc = row['doc']
      if doc['lang'] == 'en':
        tweet['text'] = doc["text"]
        tweet['user'] = {}
        tweet['user']['name'] = doc["user"]["name"]
        tweet['user']['location'] = doc["user"]["location"]
        if doc['place'] != None:
          tweet['place'] = {}
          tweet['place']['name'] = str(doc['place']['full_name'])
          tweet['place']['coordinates'] = str(doc['place']['bounding_box']['coordinates'])
        try:
          tweet['created_at'] = str(datetime.strptime(doc["created_at"],'%a %b %d %H:%M:%S +0000 %Y') + timedelta(hours=10))
          # print(json.dumps(tweet))
        except Exception as e:
          print(e)
        if doc["geo"] != None:
          tweet['geo'] = {}
          tweet['geo']['lat'] = doc["geo"]['coordinates'][0]
          tweet['geo']['long'] = doc["geo"]['coordinates'][1]
        if doc['coordinates'] != None:
          tweet['coordinates'] = {}
          tweet['coordinates']['lat'] = doc["coordinates"]['coordinates'][1]
          tweet['coordinates']['long'] = doc["coordinates"]['coordinates'][0]
        try:
          db[str(tweet_id)] = tweet
        except Exception as e:
          pass

if __name__ == '__main__':
  get_tweets_from_json('')