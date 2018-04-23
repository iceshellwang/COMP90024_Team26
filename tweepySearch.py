import couchdb
import json
import tweepy
import time
import timeit


consumer_key = ["fx8DdDJ72wJfygl89KMFuJglK",
                "EqbRMtxKufAL7k8IUmwMkyXF3",
                "BwX4hR8X7xLDW5HsNVEvhdpII",
                "KS7OVti6JQmjmjjdOFcHuOTf0"]
consumer_secret = ["nbty0xkOPR6vzkrchHPI9PxStppGj0i92RVdGCgolihMxhF9zp",
                    "pbn19hHX25c9RdlVopx5IEd55ZMgJGoMexPzkKx13rHRXZDx0Y",
                    "vAG93V2x5ap8xW03J2sdJUres2DWNvlpVtOAwpLC6wzpx1XTwq",
                    "ax4xAXNHwKZRgsAZMNPMGk7voainFnEUpZlXtLt5krm918BulW"]
access_token = ["1206790807-Qk3tsBTAnlFv0ntcQMAWS6jy4j0cNFitUBDHFwt",
                "1206790807-Lnx4FsK2cRRfp5iZ6m3Dkkpf0bwzfjEBa5S7IGG",
                "1206790807-pT1mmfxu8z3pgFBbqKdyQd83QHjVAZYQJILDQdo",
                "1206790807-2Ohfa2e8CjtvQIldipOXo8IHivPtUdIgdYoF0cF"]
access_token_secret = ["AkKsz3McpIXSmsrEPVKc27jmMsfUSRIlwIaN2pW7AjnrN",
                        "daNdhzK4a7GQaSV8fQf9dyCLLB8wT66hFYApJ8UvhkQQc",
                        "HGGQrK216AHo8lAQx27bxC3zpxyPk9AxLq4CCtqXdoFDK",
                        "DO1cDgikLxmGPGqvGl7DbO8wLZtVJVqQfu4q7IcGsHCwk"]
file = open("DBErrorTweets.txt", "w")


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
  min_t_id = 999999999999999999999999
  if t_id == 0:
    try:
      for tweet in tweepy.Cursor(api.search,q="*",lang = "en",until="2018-04-20",geocode="-37.810142,144.964302,150km").items():
        temp_t_id = processTweet(tweet)
        if temp_t_id < min_t_id:
          min_t_id = temp_t_id
    except:
      return min_t_id
  else:
    try:
      for tweet in tweepy.Cursor(api.search,q="*",lang = "en",max_id=str(t_id),geocode="-37.810142,144.964302,150km").items():
        temp_t_id = processTweet(tweet)
        if temp_t_id < min_t_id:
          min_t_id = temp_t_id
    except:
      return min_t_id
  print("Error")
  return min_t_id

def processTweet(tweet):
  tweet_data = {}
  tweet_id = tweet.id
  tweet_data['text'] = tweet.text
  tweet_data['user'] = tweet.user.name
  if tweet.geo != None:
    tweet_data['lat'] = tweet.geo['coordinates'][0]
    tweet_data['long'] = tweet.geo['coordinates'][1]
    # print(tweet_id, tweet_data['text'].encode('utf-8'))
    try:
      # print(tweet_id, tweet_data['text'].encode('utf-8'))
      db[str(tweet_id)] = tweet_data
    except Exception as e:
      print(e)
      tweet_data['id'] = tweet_id
      file.write(json.dumps(tweet_data))
      file.write("\n")
      file.flush()
  else:
    try:
      # print(tweet_id, tweet_data['text'].encode('utf-8'))
      original_db[str(tweet_id)] = tweet_data
    except Exception as e:
      print(tweet_id, tweet_data['text'].encode('utf-8'))
      print(e)
  return tweet_id

if __name__ == '__main__':
  mango = {'selector':{'_id':{"$gt":None}}, 'fields':['_id'],"sort":[{"_id":"asc"}]}
  tweet_id = int(db.find(mango)[0]['_id'])
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
        print(tweet_id)
      except:
        print(tweet_id)
    stop = timeit.default_timer()
    print('Run out all access tokens, speed: ' + str(stop - start) + "s.")
    if 15*60-(int(stop - start)) > 0:
      time.sleep(16*60-(int(stop - start)))
    print('Keep seaching')

