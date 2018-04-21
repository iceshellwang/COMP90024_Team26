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
db = couch['tweets']



def searchTweets(consumer_key, consumer_secret, access_token, access_token_secret):
  auth = tweepy.auth.OAuthHandler(consumer_key, consumer_secret)
  auth.set_access_token(access_token, access_token_secret)
  api = tweepy.API(auth)

  for tweet in tweepy.Cursor(api.search,q="*",lang = "en",geocode="-37.810142,144.964302,150km").items():
    tweet_data = {}
    tweet_id = tweet.id
    tweet_data['text'] = tweet.text
    tweet_data['user'] = tweet.user.name
    if tweet.geo != None:
      tweet_data['lat'] = tweet.geo['coordinates'][0]
      tweet_data['long'] = tweet.geo['coordinates'][1]
      try:
        db[str(tweet_id)] = tweet_data
      except:
        print('DB error')
      print(tweet_data)

if __name__ == '__main__':
  i = 0
  while True:
    pass
    try:
      searchTweets(consumer_key[i], consumer_secret[i], access_token[i], access_token_secret[i])
    except:
      i += 1
      print('sleep for' + str(15/len(consumer_key)+1))
      time.sleep((15/len(consumer_key)+1)*60)
      print('keep seaching')






# doc['name'] = "Happy O'Keeffe"
# doc['text'] = "Flinders Street Station @igersmelbourne instamelbourne abcweather #flinderstation #facade… https://t.co/aY2ZdF6YnT"
# doc['lat'] = -37.81807
# doc['long'] = 144.96681
# db['987196365750132736'] = doc

