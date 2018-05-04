import couchdb
import timeit
import time
import csv
import json
from tweepy import Stream
from tweepy import OAuthHandler
from tweepy.streaming import StreamListener
import timeit
import sys
from datetime import timedelta
from datetime import datetime


couch = couchdb.Server()
couch = couchdb.Server('http://admin:admin@localhost:5984/')
try:
  db = couch.create('raw_twitter')
except:
  db = couch['raw_twitter']




class listener(StreamListener):

  def on_data(self, data):
    try:
      all_data = json.loads(data)
      if all_data['lang'] == 'en':
        # print("Getting Data", all_data)
        tweet = {}
        tweet_id = all_data["id"]
        tweet['text'] = all_data["text"]
        tweet['user'] = {}
        tweet['user']['name'] = all_data["user"]["name"]
        tweet['user']['location'] = all_data["user"]["location"]
        if all_data['place'] != None:
          tweet['place'] = {}
          tweet['place']['name'] = str(all_data['place']['full_name'])
          tweet['place']['coordinates'] = str(all_data['place']['bounding_box']['coordinates'])
        try:
          tweet['created_at'] = str(datetime.strptime(all_data["created_at"],'%a %b %d %H:%M:%S +0000 %Y') + timedelta(hours=10))
          # print(json.dumps(tweet))
        except Exception as e:
          print(e)
        if all_data["geo"] != None:
          tweet['geo'] = {}
          tweet['geo']['lat'] = all_data["geo"]['coordinates'][0]
          tweet['geo']['long'] = all_data["geo"]['coordinates'][1]
        if all_data['coordinates'] != None:
          tweet['coordinates'] = {}
          tweet['coordinates']['lat'] = all_data["coordinates"]['coordinates'][1]
          tweet['coordinates']['long'] = all_data["coordinates"]['coordinates'][0]
        try:
          db[str(tweet_id)] = tweet
        except Exception as e:
          tweet['id'] = tweet_id
          print(tweet)
      return True
    except:
      return True

  def on_error(self, status):
      print(status)


def read_arguments(argv):
  # Initialise Variables
  total_nodes = 1
  node_rank = 0
  # Try to read in arguments
  for opt, arg in zip(argv[0::2], argv[1::2]):
    if opt in ("-t"):
      total_nodes = int(arg)
    if opt in ("-r"):
      node_rank = int(arg)
  total_nodes = min(total_nodes, 4)
  total_nodes = max(total_nodes, 1)
  node_rank = min(total_nodes, node_rank)
  node_rank = max(0, node_rank)
  # Return all the arguments
  return total_nodes - 1, node_rank




if __name__ == '__main__':
  argv = sys.argv[1:]
  total_nodes, node_rank = read_arguments(argv)

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

    i = node_rank
    auth = OAuthHandler(tokens[i]['ConsumerKey'], tokens[i]['ConsumerSecret'],)
    auth.set_access_token(tokens[i]['AccessToken'], tokens[i]['AccessTokenSecret'])
    twitterStream = Stream(auth, listener())
    long_diff = 146.1 - 143.9
    each_diff = long_diff / (total_nodes + 1)
    try:
      twitterStream.filter(locations=[143.9 + each_diff * node_rank,
                                      -38.5,
                                      143.9 + each_diff * (node_rank + 1),
                                      -37.1])
    except Exception as e:
      print(e)
      twitterStream.disconnect()
    stop = timeit.default_timer()
    time.sleep(max(0, 60*60-(int(stop - start))))

