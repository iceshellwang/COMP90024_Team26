import couchdb
import json
import tweepy
import time


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


couch = couchdb.Server()
couch = couchdb.Server('http://admin:admin@172.17.0.2:5984/')
db = couch['twitter']

file = open("DBErrorTweets.txt","w")


def searchTweets(consumer_key, consumer_secret, access_token, access_token_secret, t_id):
  auth = tweepy.auth.OAuthHandler(consumer_key, consumer_secret)
  auth.set_access_token(access_token, access_token_secret)
  api = tweepy.API(auth)
  min_t_id = t_id
  if t_id == 0:
    try:
      for tweet in tweepy.Cursor(api.search,q="*",lang = "en",until="2018-04-18",geocode="-37.810142,144.964302,150km").items():
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
  print "Error"
  return min_t_id

def processTweet(tweet):
  tweet_data = {}
  tweet_id = tweet.id
  tweet_data['text'] = tweet.text.encode('utf-8')
  tweet_data['user'] = tweet.user.name.encode('utf-8')
  if tweet.geo != None:
    tweet_data['lat'] = tweet.geo['coordinates'][0]
    tweet_data['long'] = tweet.geo['coordinates'][1]
    try:
      print str(tweet_id) + "      " + tweet_data['text']
      db[str(tweet_id)] = tweet_data
    except:
      tweet_data['id'] = tweet_id
      file.write(json.dumps(tweet_data).encode('utf-8') + "\n")
      print 'DB error'
  return tweet_id

if __name__ == '__main__':
  mango = {'selector':{'_id':{"$gt":None}}, 'fields':['_id'],"sort":[{"_id":"asc"}]}
  #tweet_id = int(db.find(mango)[0]['_id'])
  tweet_id = 986383018397859840
  print tweet_id
  while True:
    for i in range(len(consumer_key)):
      try:
        tweet_id = searchTweets(consumer_key[i], consumer_secret[i], access_token[i], access_token_secret[i], tweet_id)
        print tweet_id
      except:
        print tweet_id
    print 'sleep for 15 mins'
    time.sleep(15*60)
    print 'keep seaching'

