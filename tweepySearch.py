import couchdb
import json
import tweepy
import time
import timeit
from datetime import timedelta


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
      for tweet in tweepy.Cursor(api.search,q="*",lang = "en",until="2018-04-21",geocode="-37.810142,144.964302,150km").items():
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
  tweet_data['created_at'] = str(tweet.created_at - timedelta(hours=7))
  # print(tweet_data)
  if tweet.geo != None:
    tweet_data['lat'] = tweet.geo['coordinates'][0]
    tweet_data['long'] = tweet.geo['coordinates'][1]
    print(tweet_id, tweet_data['text'].encode('utf-8'))
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
      print("Other twitter error")
      print(tweet_id, tweet_data['text'].encode('utf-8'))
      print(e)
  return tweet_id

if __name__ == '__main__':
  mango = {'selector':{'_id':{"$gt":None}}, 'fields':['_id'],"sort":[{"_id":"asc"}]}

#  for tweet in db.find(mango):
#    print(tweet['_id'])

  # tweet_id = int(list(db.find(mango))[0]['_id'])
  tweet_id = 0
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
    time.sleep(max(0, 16*60-(int(stop - start))))
    print('Keep seaching')

