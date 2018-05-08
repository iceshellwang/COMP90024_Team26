
import requests
from datetime import datetime
from datetime import timedelta



def get_tweets_from_couchdb(fileName, start_geohash, end_geohash):
  url = 'http://readonly:ween7ighai9gahR6@45.113.232.90/couchdbro/twitter/_design/twitter/_view/geoindex?include_docs=true&reduce=false&start_key=["' + start_geohash + '",2014,1,1]&end_key=["' + end_geohash + '",2017,12,31]'
  response = requests.get(url, stream=True)
  handle = open(fileName, "wb")
  for chunk in response.iter_content(chunk_size=512):
    if chunk:  # filter out keep-alive new chunks
      handle.write(chunk)
  handle.close()


if __name__ == '__main__':
  geohash_list = [('r1qb', 'r1qg'), ('r1r0', 'r1r9'), ('r1pj', 'r1pr')]
  fileNames = ['r1qb_g.json','r1r0_9.json', 'r1pj_r.json']
  for i in range(len(geohash_list)):
    start_geohash, end_geohash = geohash_list[i]
    fileName = fileNames[i]
    get_tweets_from_couchdb(fileName, start_geohash, end_geohash)