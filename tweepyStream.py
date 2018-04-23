import couchdb
import timeit
import time
import csv
import json
from tweepy import Stream
from tweepy import OAuthHandler
from tweepy.streaming import StreamListener


consumer_key = "fx8DdDJ72wJfygl89KMFuJglK"
consumer_secret = "nbty0xkOPR6vzkrchHPI9PxStppGj0i92RVdGCgolihMxhF9zp"
access_token = "1206790807-Qk3tsBTAnlFv0ntcQMAWS6jy4j0cNFitUBDHFwt"
access_token_secret = "AkKsz3McpIXSmsrEPVKc27jmMsfUSRIlwIaN2pW7AjnrN"

couch = couchdb.Server()
couch = couchdb.Server('http://admin:admin@172.17.0.2:5984/')
db = couch['twitter']
couch2 = couchdb.Server()
couch2 = couchdb.Server('http://admin:admin@localhost:5984/')
original_db = couch2['other_twitter']




class listener(StreamListener):

  def on_data(self, data):
    try:
      all_data = json.loads(data)
      if all_data['lang'] == 'en':
        tweet = {}
        tweet_id = all_data["id"]
        tweet['text'] = all_data["text"]
        tweet['user'] = all_data["user"]["name"]
        tweet['created_at'] = all_data["created_at"]
        print(tweet)
        geo = all_data["geo"]
        if geo != None:
          tweet['lat'] = all_data["geo"]['coordinates'][0]
          tweet['long'] = all_data["geo"]['coordinates'][1]
          print(tweet)
          try:
            db[str(tweet_id)] = tweet
          except Exception as e:
            tweet['id'] = tweet_id
            print(tweet)
        else:
          try:
            original_db[str(tweet_id)] = tweet
          except Exception as e:
            print(e)
            tweet['id'] = tweet_id
            print(tweet)
      return True
    except:
      return True

  def on_error(self, status):
      print(status)


if __name__ == '__main__':

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
      auth = OAuthHandler(tokens[i]['ConsumerKey'], tokens[i]['ConsumerSecret'],)
      auth.set_access_token(tokens[i]['AccessToken'], tokens[i]['AccessTokenSecret'])
      twitterStream = Stream(auth, listener())
      try:
        twitterStream.filter(locations=[143.9,-38.5,146.1,-37.1])
      except:
        twitterStream.disconnect()
    stop = timeit.default_timer()
    if 60*60-(int(stop - start)) > 0:
      time.sleep(60*60-(int(stop - start)))

# auth = tweepy.auth.OAuthHandler(consumer_key, consumer_secret)
# auth.set_access_token(access_token, access_token_secret)
# api = tweepy.API(auth)

# for tweet in tweepy.Cursor(api.search,q="*",lang = "en",geocode="-37.810142,144.964302,150km").items(1000):
#   if tweet.geo != None:
#     print ([tweet.id, tweet.text, tweet.user.name, tweet.geo['coordinates'], tweet.coordinates])