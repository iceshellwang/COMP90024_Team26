import couchdb
import json
import tweepy
import time
import timeit
import datetime
from datetime import timedelta

MAX_TWEET_ID          = 999999999999999999999999
QUERY_TEXT            = "*"
QUERY_LANGUAGE        = "en"
QUERY_GEOCODE         = "-37.810142,144.964302,250km"
ACCESS_TOKENS_FILE    = "tokens.json"
QUERY_TIME_LIMIT      = 16 * 60

couchdb_address = 'http://admin:admin@172.17.0.2:5984/'
couchdb_dbname = 'twitter'

couch = couchdb.Server()
couch = couchdb.Server(couchdb_address)
db = couch[couchdb_dbname]

couch2 = couchdb.Server()
couch2 = couchdb.Server('http://admin:admin@localhost:5984/')
original_db = couch2['other_twitter']


def searchTweets(consumer_key, consumer_secret, access_token, access_token_secret, t_id):
  auth = tweepy.auth.OAuthHandler(consumer_key, consumer_secret)
  auth.set_access_token(access_token, access_token_secret)
  api = tweepy.API(auth)
  min_t_id = MAX_TWEET_ID
  if t_id == 0:
    try:
      now = datetime.datetime.now()
      date_str = str(now.year) + "-" + str(now.month) + "-" + str(now.day)
      for tweet in tweepy.Cursor(api.search,
                                  q = QUERY_TEXT,
                                  lang = "en",
                                  until = date_str,
                                  geocode = QUERY_GEOCODE).items():
        temp_t_id = processTweet(tweet)
        if temp_t_id < min_t_id:
          min_t_id = temp_t_id
    except Exception as e:
      print(e)
      return min_t_id
  else:
    try:
      for tweet in tweepy.Cursor(api.search,
                                  q = QUERY_TEXT,
                                  lang = QUERY_LANGUAGE,
                                  max_id = str(t_id),
                                  geocode=QUERY_GEOCODE).items():
        temp_t_id = processTweet(tweet)
        if temp_t_id < min_t_id:
          min_t_id = temp_t_id
    except Exception as e:
      print(e)
      return min_t_id
  print("Error")
  return min_t_id

def getGeoData(tweet, json_data):
  if tweet.geo != None:
    json_data['geo'] = {}
    json_data['geo']['lat'] = tweet.geo['coordinates'][0]
    json_data['geo']['long'] = tweet.geo['coordinates'][1]
  return json_data

def getCoordinatesData(tweet, json_data):
  if tweet.coordinates != None:
    json_data['coordinates'] = {}
    json_data['coordinates']['long'] = tweet.coordinates['coordinates'][0]
    json_data['coordinates']['lat'] = tweet.coordinates['coordinates'][1]
  return json_data

def getCreatedTimeData(tweet, json_data):
  json_data['created_at'] = str(tweet.created_at - timedelta(hours=7))
  return json_data

def getTextData(tweet, json_data):
  json_data['text'] = tweet.text
  return json_data

def getUserData(tweet, json_data):
  json_data['user'] = {}
  json_data['user']['name'] = tweet.user.name
  json_data['user']['location'] = tweet.user.location
  return json_data

def getPlaceData(tweet, json_data):
  if tweet.place != None:
    json_data['place'] = {}
    json_data['place']['name'] = str(tweet.place.full_name)
    json_data['place']['coordinates'] = str(tweet.place.bounding_box.coordinates)
  return json_data

def processTweet(tweet):
  tweet_data = {}
  tweet_id = tweet.id
  tweet_data = getTextData(tweet, tweet_data)
  tweet_data = getUserData(tweet, tweet_data)
  tweet_data = getCreatedTimeData(tweet, tweet_data)
  tweet_data = getPlaceData(tweet, tweet_data)
  if tweet.geo != None or tweet.coordinates != None:
    tweet_data = getGeoData(tweet, tweet_data)
    tweet_data = getCoordinatesData(tweet, tweet_data)
    # print(tweet_id, json.dumps(tweet_data))
    try:
      # print(tweet_id, tweet_data['text'].encode('utf-8'))
      db[str(tweet_id)] = tweet_data
    except Exception as e:
      try:
        print("Update doc with geo-info",tweet_id, tweet_data['text'].encode('utf-8'))
        doc = db[str(tweet_id)]
        doc = getTextData(tweet, doc)
        doc = getUserData(tweet, doc)
        doc = getCreatedTimeData(tweet, doc)
        doc = getPlaceData(tweet, doc)
        doc = getGeoData(tweet, doc)
        doc = getCoordinatesData(tweet, doc)
        db[str(tweet_id)] = doc
      except Exception as e1:
        print(e1)
  else:
    try:
      # print(tweet_id, tweet_data['text'].encode('utf-8'))
      original_db[str(tweet_id)] = tweet_data
    except Exception as e:
      try:
        # print("Update doc without geo-info",tweet_id, tweet_data['text'].encode('utf-8'))
        doc = original_db[str(tweet_id)]
        doc = getTextData(tweet, doc)
        doc = getUserData(tweet, doc)
        doc = getCreatedTimeData(tweet, doc)
        doc = getPlaceData(tweet, doc)
        original_db[str(tweet_id)] = doc
      except Exception as e1:
        print(e1)
  return tweet_id


if __name__ == '__main__':
  mango = {'selector':{'_id':{"$gt":None}}, 'fields':['_id'],"sort":[{"_id":"asc"}]}
  tweet_id = int(list(db.find(mango))[0]['_id'])
  # tweet_id = 0
  print(tweet_id)
  while True:
    start = timeit.default_timer()

    while True:
      try:
        tokens_file = open("tokens.json", "r")
        tokens_file_str = tokens_file.read()
        tokens_json = json.loads(tokens_file_str)
        tokens = tokens_json['tokens']
        tokens_file.close()
        break
      except:
        continue

    for i in range(len(tokens)):
      try:
        tweet_id = searchTweets(tokens[i]['ConsumerKey'],
                                tokens[i]['ConsumerSecret'],
                                tokens[i]['AccessToken'],
                                tokens[i]['AccessTokenSecret'],
                                tweet_id)
        if tweet_id == MAX_TWEET_ID:
          tweet_id = 0
        print(tweet_id)
      except:
        print(tweet_id)
    stop = timeit.default_timer()
    print('Run out all access tokens, speed: ' + str(stop - start) + "s.")
    time.sleep(max(0, QUERY_TIME_LIMIT-(int(stop - start))))
    print('Keep seaching')

